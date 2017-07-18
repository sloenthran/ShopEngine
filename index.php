<?php

	error_reporting(E_ALL ^ E_NOTICE);  

	ob_start();
	
		if(!file_exists('config.php'))
		{
		
			header("Location: ./install/");
		
		}
	
		session_start();
		
		require_once('./config.php');
		
		$MySQL = new PDO('mysql:host='.$DB[0].'; dbname='.$DB[3].'; charset=utf8;',  $DB[1],  $DB[2]);
		
		require_once('./application/modules/SystemLoader.php');
		
		$MySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$MySQL->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		
		$Core = new Core();
		$View = new View();
		
		$Pages = $Core->ClearText($_GET['pages']);
		$Pages = str_replace('/', '', $Pages);

		if(!$Pages || $Pages == '') { $Pages = 'home'; }
		
		$Status = $_SESSION['LOGGED'] <> true ? 'guest':'logged';
		
		if(file_exists('./model/'.$Status.'/'.$Pages.'.php'))
		{
			
			require_once('./model/'.$Status.'/'.$Pages.'.php');
		
		}
		
		else
		{
		
			$View->Load('info');
			$View->Add('title', 'Błąd 404');
			$View->Add('header', 'Błąd 404!');
			$View->Add('info', 'Taki zasób nie istnieje!');
			$View->Add('back', 'home.html');
			$View->Out();
		
		}
	
	ob_end_flush();

?>