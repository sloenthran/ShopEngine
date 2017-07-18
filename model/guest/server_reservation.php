<?php

	$ID = $Core->ClearText($_GET['id']);
	$Nick = $Core->ClearText($_GET['nick']);
	
	$Query = $MySQL->prepare("SELECT `reservation` FROM `servers` WHERE `id`=:one");
	$Query->bindValue(":one", $ID, PDO::PARAM_INT);	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	if($Fetch['reservation'] == 1)
	{
		
		if($_POST['ADD'])
		{
			
			$User = $Core->ClearText($_POST['USER']);
			$Pass = $Core->ClearText($_POST['PASS']);
			
			$Query = $MySQL->prepare("SELECT `id` FROM `free_reservation` WHERE `nick`=:one AND `server`=:two");
			$Query->bindValue(":one", $User, PDO::PARAM_STR);
			$Query->bindValue(":two", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			if($Query->rowCount() > 0  || $User == '')
			{
				
				$View->Load('info');
				$View->Add('title', 'Rezerwacja istnieje');
				$View->Add('header', 'Rezerwacja istnieje!');
				$View->Add('info', 'Rezerwacja na takim nicku już istnieje!');
				$View->Add('back', 'server_reservation-'.$ID.'-'.$Nick.'.html');
				$View->Out();
				
			}
			
			else
			{
				
				$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='user_gest_buy'");
				$Fetch = $Query->fetch();
			
				$Query = $MySQL->prepare("INSERT INTO `free_reservation` VALUES('', :one, :two, :three, :four)");
				$Query->bindValue(":one", $User, PDO::PARAM_STR);
				$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
				$Query->bindValue(":three", $ID, PDO::PARAM_INT);
				$Query->bindValue(":four", $Fetch['value'], PDO::PARAM_INT);
				$Query->execute();
			
				$View->Load('info');
				$View->Add('title', 'Rezerwacja dodana');
				$View->Add('header', 'Rezerwacja dodana!');
				$View->Add('info', 'Rezerwacja została poprawnie dodana!');
				$View->Add('back', 'server_reservation-'.$ID.'-'.$Nick.'.html');
				$View->Out();
				
			}
			
		}
		
		else
		{
			
			$Info .= '<form method="post" action="server_reservation-'.$ID.'-'.$Nick.'.html">
		
				<input type="hidden" name="ADD" value="true">
			
				<br>Nick<br><input type="text" name="USER" value="'.$Nick.'" readonly><br>
				<br>Hasło<br><input type="text" name="PASS" required><br>
			
				<br><input type="submit" class="przycisk" value="Rezerwuję!">
		
			</form>';
			
			$View->Load('info');
			$View->Add('title', 'Darmowa rezerwacja nicku');
			$View->Add('header', 'Darmowa rezerwacja nicku');
			$View->Add('info', $Info);
			$View->Add('back', 'server_reservation-'.$ID.'-'.$Nick.'.html');
			$View->Out();
			
		}
		
	}
	
	else
	{
		
		$View->Load('info');
		$View->Add('title', 'Brak rezerwacji');
		$View->Add('header', 'Brak rezerwacji!');
		$View->Add('info', 'Ten serwer nie ma włączonej darmowej rezerwacji!');
		$View->Add('back', 'home.html');
		$View->Out();
		
	}

?>