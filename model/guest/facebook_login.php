<?php

	$ID = $Core->ClearText($_GET['id']);
	
	$cURL = curl_init('https://graph.facebook.com/me?access_token='.$ID.'');
	
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'GET');
   
	$Query = curl_exec($cURL);
	
	curl_close($cURL);
   
	$Data = json_decode($Query);
	
	$Email = $Data->{'email'};
	
	$Query = $MySQL->prepare('SELECT * FROM `users` WHERE `mail`=:one LIMIT 1');

	$Query->bindValue(':one', $Email, PDO::PARAM_STR);

	$Query->execute();
	
	if($Query->rowCount() > 0)
	{
	
		$Fetch = $Query->fetch();
		
		$_SESSION['LOGGED'] = true;
		$_SESSION['RANKS'] = $Fetch['ranks'];
		$_SESSION['ID'] = $Fetch['id'];
		$_SESSION['ID_TIME'] = time();
	
		$Core->AddLoginLogs('Zalogowano przez FB');
	
		header("Location: home.html");
	
	}
	
	else
	{
		
		$View->Load('info');
		$View->Add('title', 'Błąd :: Brak e-maila');
		$View->Add('header', 'Brak e-maila!');
		$View->Add('info', 'Nie znaleziono użytkownika z takim e-mailem w bazie danych!');
		$View->Add('back', 'login.html');
		$View->Out();
		
	}
	
?>