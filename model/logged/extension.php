<?php

	$ID = $Core->ClearText($_GET['id']);
	
	$Query = $MySQL->prepare("SELECT `time`,`user_id`,`premium_id`, `server` FROM `premium_cache` WHERE `id`=:one");
	
	$Query->bindValue(":one", $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$Data = $Fetch;
	
	if($Data['user_id'] == $_SESSION['ID'])
	{
	
		if($Data['premium_id'] != 0)
		{
	
			$Query = $MySQL->prepare("SELECT `cash`, `days` FROM `buy` WHERE `id`=:one");
		
			$Query->bindValue(":one", $Data['premium_id'], PDO::PARAM_INT);
		
			$Query->execute();
		
			$Fetch = $Query->fetch();
			
			$Days = $Fetch['days'] * 86400;
		
		}
		
		else
		{
		
			$Fetch['cash'] = '11';
			$Days = '2592000';
		
		}
		
		if($Core->GetMoney() >= $Fetch['cash'])
		{
		
			$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`=:one WHERE `id`=:two");
			
			$Query->bindValue(":one", $Data['time'] + $Days, PDO::PARAM_INT);
			$Query->bindValue(":two", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("UPDATE `users` SET `money`=`money`-:one WHERE `id`=:two");
			
			$Query->bindValue(":one", $Fetch['cash'], PDO::PARAM_INT);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("INSERT INTO `server_cash` VALUES('', :one, :two, CURRENT_TIMESTAMP)");
			
			$Query->bindValue(":one", $Data['server'], PDO::PARAM_INT);
			$Query->bindValue(":two", $Fetch['cash'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Core->AddBuyLogs("Przedłużono zakup o ID ".$ID."");
			
			$View->Load("info");
			$View->Add('title', 'Przedłużono zakup');
			$View->Add('header', 'Przedłużono zakup!');
			$View->Add('info', 'Ważność twojego zakupu została przedłużona');
			$View->Add('back', 'my_buy.html');
			$View->Out();
		
		}
		
		else
		{
		
			$View->Load("info");
			$View->Add('title', 'Błąd :: Brak gotówki');
			$View->Add('header', 'Nie masz tyle gotówki!');
			$View->Add('info', 'Aby to kupić potrzebujesz '.$Fetch['cash'].' wPLN!');
			$View->Add('back', 'my_buy.html');
			$View->Out();
		
		}
	
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add("title", "Błąd :: Złe ID!");
		$View->Add("header", "Złe ID!");
		$View->Add("info", "Podałeś błędne ID!");
		$View->Add("back", "my_buy.html");
		$View->Out();
	
	}
	
?>