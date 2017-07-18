<?php

	$ID = $Core->ClearText($_GET['id']);
	
	$Query = $MySQL->prepare("SELECT `name`,`server`,`description`,`cash` FROM `buy` WHERE `id`=:one");
	
	$Query->bindValue(":one", $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$Info .= ''.$Fetch['description'].'<br><br><br><br><a href="buy_three-'.$ID.'.html"><button class="przycisk">Kupuję!</button></a>';

	$View->Load("info");
	$View->Add("title", "Zakup :: ".$Fetch['name']."");
	$View->Add("header", "".$Fetch['name']." [".$Fetch['cash']." wPLN]");
	$View->Add("info", $Info);
	$View->Add("back", "buy-".$Fetch['server'].".html");
	$View->Out();

?>