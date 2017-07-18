<?php

	if($_POST['CHANGE'])
	{
		
		$Pass = $Core->ClearText($_POST['PASS']);
		
		$Query = $MySQL->prepare("SELECT `nick` FROM `premium_cache` WHERE `user_id`=:one");
		$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
		$Query->execute();
		
		while($Fetch = $Query->fetch())
		{
			
			$QueryTwo = $MySQL->prepare("UPDATE `premium` SET `pass`=:one WHERE `nick`=:two");
			$QueryTwo->bindValue(":one", $Pass, PDO::PARAM_STR);
			$QueryTwo->bindValue(":two", $Fetch['nick'], PDO::PARAM_STR);
			$QueryTwo->execute();
			
		}
		
		$Query = $MySQL->prepare("UPDATE `premium_cache` SET `pass`=:one WHERE `user_id`=:two");
		$Query->bindValue(":one", $Pass, PDO::PARAM_STR);
		$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
		$Query->execute();
		
		$Query = $MySQL->prepare("UPDATE `multi_admins` SET `pass`=:one WHERE `user_id`=:two");
		$Query->bindValue(":one", $Pass, PDO::PARAM_STR);
		$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
		$Query->execute();
		
		$Query = $MySQL->prepare("UPDATE `guardians` SET `pass`=:one WHERE `user_id`=:two");
		$Query->bindValue(":one", $Pass, PDO::PARAM_STR);
		$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
		$Query->execute();
		
		$Query = $MySQL->prepare("UPDATE `free_reservation` SET `pass`=:one WHERE `user_id`=:two");
		$Query->bindValue(":one", $Pass, PDO::PARAM_STR);
		$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
		$Query->execute();
		
		$View->Load("info");
		$View->Add("title", "Hasło zmienione");
		$View->Add("header", "Hasło zmienione!");
		$View->Add("info", 'Hasło zostało pomyślnie zmienione! Hasło na serwerze zostanie zaktualizowane w ciągu około 30 minut!<br>Nie zapomnij o wpisaniu w konsoli<br><b>setinfo _pw "'.$Pass.'"</b>');
		$View->Add("back", "premium_settings.html");
		$View->Out();
		
		$Core->AddOtherLogs("Zmieniono hasło do zakupów");
		
	}
	
	else
	{
		
		$Info = '<form method="post" action="premium_settings.html">
		
			<input type="hidden" name="CHANGE" value="true">
			
			<br>Nowe hasło<br><br><input type="text" name="PASS"><br>
			
			<br><button type="submit" class="przycisk">Zmień <i class="fa fa-chevron-circle-right"></i> </button>
		
		</form>';
		
		$View->Load('premium_settings');
		$View->Add('title', 'Zmiana hasła zakupów');
		$View->Add('header', 'Zmiana hasła zakupów');
		$View->Add('info', $Info);
		$View->Out();
		
	}

?>