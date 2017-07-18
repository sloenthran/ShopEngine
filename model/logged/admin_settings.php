<?php

	if($Core->CheckAdmin())
	{

		if($_POST['SAVE'])
		{
	
			$Prepare = $MySQL->prepare("UPDATE `settings` SET `value`=:one WHERE `name`=:two");
	
			$Query = $MySQL->query("SELECT `name`, `value` FROM `settings`");
		
			while($Fetch = $Query->fetch())
			{
			
				$Value = $Core->ClearText($_POST[$Fetch['name']]);
				
				if($Fetch['value'] != $Value)
				{
			
					$Core->AddAdminLogs('Zmieniono wartość ustawienia <b>'.$Fetch['name'].'</b> z <b>'.$Fetch['value'].'</b> na <b>'.$Value.'</b>');
					
				}
		
				$Prepare->bindValue(':one', $Value, PDO::PARAM_STR);
				$Prepare->bindValue(':two', $Fetch['name'], PDO::PARAM_STR);
			
				$Prepare->execute();
		
			}
			
			$View->Load('info');
			$View->Add('title', 'Admin :: Ustawienia zapisane');
			$View->Add('header', 'Ustawienia zapisane');
			$View->Add('info', 'Ustawienia zostały poprawnie zapisane!');
			$View->Add('back', 'admin_settings.html');
			$View->Out();
		
		}
	
		else
		{
	
			$Query = $MySQL->query("SELECT * FROM `settings`");
		
			while($Fetch = $Query->fetch())
			{
		
				if($Fetch['name'] != 'pay' AND $Fetch['name'] != 'styles')
				{
			
					$Info .= '<br>'.$Fetch['name'].'<br><br><input type="text" name="'.$Fetch['name'].'" value="'.$Fetch['value'].'"><br>';
				
				}
			
				else if($Fetch['name'] == 'pay')
				{
				
					$Info .= '<br>Płatność<br><br><select name="pay">';
				
					$Pay = $Core->GetPay();
				
					foreach($Pay as $Key => $Value)
					{
				
						if($Value == $Fetch['value'])
						{
					
							$Info .= '<option value="'.$Value.'" selected>'.$Value.'</option>';
					
						}
					
						else
						{
					
							$Info .= '<option value="'.$Value.'">'.$Value.'</option>';
					
						}
				
					}
				
					$Info .= '</select><br>';
			
				}
				
				else if($Fetch['name'] == 'styles')
				{
				
					$Info .= '<br>Styl<br><br><select name="styles">';
				
					$Table = $Core->GetStyles();
				
					foreach($Table as $Key => $Value)
					{
				
						if($Value == $Fetch['value'])
						{
					
							$Info .= '<option value="'.$Value.'" selected>'.$Value.'</option>';
					
						}
					
						else
						{
					
							$Info .= '<option value="'.$Value.'">'.$Value.'</option>';
					
						}
				
					}
				
					$Info .= '</select><br>';
			
				}
			
			}
	
			$View->Load("admin_settings");
			$View->Add("info", $Info);
			$View->Out();
	
		}
		
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak uprawnień');
		$View->Add('header', 'Błąd! Brak uprawnień!');
		$View->Add('info', 'Nie posiadasz uprawnień administracyjnych!');
		$View->Add('back', 'home.html');
		$View->Out();
	
	}
	
?>