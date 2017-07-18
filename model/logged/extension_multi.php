<?php

	$ID = $Core->ClearText($_GET['id']);
	
	$Query = $MySQL->prepare("SELECT `time`,`user_id` FROM `multi_admins` WHERE `id`=:one");
	
	$Query->bindValue(":one", $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$Data = $Fetch;
	
	if($Data['user_id'] == $_SESSION['ID'])
	{
		
		if($Data['time'] == 0)
		{
			
			header("Location: my_buy.html");
			
		}
		
		else
		{
		
			if($Core->GetMoney() >= 11)
			{
				
				$Query = $MySQL->prepare("UPDATE `multi_admins` SET `time`=:one WHERE `id`=:two");
			
				$Query->bindValue(":one", $Data['time'] + 2592000, PDO::PARAM_INT);
				$Query->bindValue(":two", $ID, PDO::PARAM_INT);
			
				$Query->execute();
			
				$Query = $MySQL->prepare("UPDATE `users` SET `money`=`money`-:one WHERE `id`=:two");
			
				$Query->bindValue(":one", 11, PDO::PARAM_INT);
				$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			
				$Query->execute();
			
				$Core->AddBuyLogs("Przedłużono multi admina o ID ".$ID."");
			
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
				$View->Add('info', 'Aby to kupić potrzebujesz 11 wPLN!');
				$View->Add('back', 'my_buy.html');
				$View->Out();
		
			}
			
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