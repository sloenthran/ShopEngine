<?php

	$ID = $Core->ClearText($_GET['id']);
	$Nick = $Core->ClearText($_GET['nick']);
	
	$Query = $MySQL->prepare("SELECT `name`, `server`, `description`, `cash` FROM `buy` WHERE `id`=:one");
	
	$Query->bindValue(":one", $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	if($Nick == 'SERVER')
	{
	
		$Info .= ''.$Fetch['description'].'<br><br><br><br><a href="buy_three-'.$ID.'-SERVER.html"><p class="przycisk" style="width: 100px; display: block; margin: 0 auto;">Kupuję!</p></a>';
		
	}
	
	else
	{
		
		$Info .= ''.$Fetch['description'].'<br><br><br><br><a href="buy_three-'.$ID.'.html"><button class="przycisk">Kupuję!</button></a>';
		
	}

	$View->Load("info");
	$View->Add("title", "Zakup :: ".$Fetch['name']."");
	$View->Add("header", "".$Fetch['name']." [".$Fetch['cash']." PLN]");
	$View->Add("info", $Info);
	
	if($Nick == 'SERVER')
	{
		
		$View->Add("back", "buy-".$Fetch['server']."-SERVER.html");
		
	}
	
	else
	{
	
		$View->Add("back", "buy-".$Fetch['server'].".html");
		
	}
	
	
	$View->Out();

?>