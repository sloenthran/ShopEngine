<?php

	if($_POST['SAVE'])
	{
	
		$Telephone = $Core->ClearText($_POST['TELEPHONE']);
		$Notification = $Core->ClearText($_POST['NOTIFICATION']);
		
		if($Telephone == '')
		{
		
			$View->Load("info");
			$View->Add("title", "Błąd :: Puste pola");
			$View->Add("header", "Błąd! Puste pola!");
			$View->Add("info", "Pola formularza nie mogą być puste!");
			$View->Add("back", "sms_alerts.html");
			$View->Out();
		
		}
		
		else
		{
			
			$Telephone = str_replace(' ', '', $Telephone);
			
			if(strlen($Telephone) == 9 && is_numeric($Telephone))
			{
				
				$Query = $MySQL->prepare("UPDATE `users` SET `telephone`=:one, `sms_notification`=:two WHERE `id`=:three");
				
				$Query->bindValue(":one", $Telephone, PDO::PARAM_INT);
				$Query->bindValue(":two", $Notification, PDO::PARAM_INT);
				$Query->bindValue(":three", $_SESSION['ID'], PDO::PARAM_INT);
				
				$Query->execute();
				
				$View->Load("info");
				$View->Add("title", "Dane zapisane");
				$View->Add("header", "Dane zapisane!");
				$View->Add("info", "Dane zostały poprawnie zapisane!");
				$View->Add("back", "sms_alerts.html");
				$View->Out();
				
				$Core->AddOtherLogs('Zmieniono ustawienia powiadomień sms');
				
			}
			
			else
			{
				
				$View->Load("info");
				$View->Add("title", "Błąd :: Błędny numer");
				$View->Add("header", "Błąd! Błędny numer!");
				$View->Add("info", "Podany numer telefonu jest błędny!");
				$View->Add("back", "sms_alerts.html");
				$View->Out();
				
			}
		
		}
	
	}
	
	else
	{
		
		$Query = $MySQL->prepare("SELECT `telephone`,`sms_notification` FROM `users` WHERE `id`=:one");
		
		$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
		
		$Query->execute();
		
		$Fetch = $Query->fetch();
	
		$Info = '<form method="post" action="sms_alerts.html">
		
			<input type="hidden" name="SAVE" value="true">
			
			<br>Telefon<br><br><input type="text" name="TELEPHONE" value="'.$Fetch['telephone'].'"><br>
			<br>Powiadomienia<br><br><select name="NOTIFICATION">';
			
			if($Fetch['sms_notification'] == 1)
			{
				
				$Info .= '<option value="1" selected>Włączone</option>
				<option value="0">Wyłączone</option>';
				
			}
			
			else
			{
				
				$Info .= '<option value="1">Włączone</option>
				<option value="0" selected>Wyłączone</option>';
				
			}
			
		$Info .= '</select><br>
		
			<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
		</form>';
		
		$View->Load("logged_home");
		$View->Add('title', 'Powiadomienia SMS');
		$View->Add('header', 'Powiadomienia SMS');
		$View->Add('info', $Info);
		$View->Out();
	
	}

?>