<?php

	if($Core->CheckAdmin())
	{
	
		if($_POST['DELETE'])
		{
		
			$ID = $Core->ClearText($_POST['ID']);
		
			$Query = $MySQL->prepare("SELECT * FROM `settings` WHERE `id`=:one");
			
			$Query->bindValue(':one', $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Core->AddAdminLogs('Usunięto ustawienie <b>'.$Fetch['name'].'</b> o wartości <b>'.$Fetch['value'].'</b>');
		
			$Query = $MySQL->prepare("DELETE FROM `settings` WHERE `id`=:one");
			
			$Query->bindValue(':one', $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$View->Load("info");
			$View->Add("title", "Admin :: Ustawienia usunięte");
			$View->Add("header", "Ustawienia usunięte!");
			$View->Add("info", "Ustawienia zostały poprawnie usunięte!");
			$View->Add("back", "admin_delete_settings.html");
			$View->Out();
		
		}
		
		else
		{
		
			$Query = $MySQL->query("SELECT `name`, `id` FROM `settings`");
			
			if($Query->rowCount() > 5)
			{
			
				while($Fetch = $Query->fetch())
				{
			
					if($Fetch['name'] != 'pay' AND $Fetch['name'] != 'styles' AND $Fetch['name'] != 'user_gest_buy' AND $Fetch['name'] != 'logo_url' AND $Fetch['name'] != 'css_url')
					{
			
						$Info .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
					}
			
				}
		
				$View->Load('admin_delete_settings');
				$View->Add('info', $Info);
				$View->Out();
				
			}
			
			else
			{
			
				$View->Load("info");
				$View->Add("title", "Błąd :: Brak ustawień");
				$View->Add("header", "Błąd! Brak ustawień do usunięcia!");
				$View->Add("info", "Nie ma żadnych ustawień do usunięcia!");
				$View->Add("back", "admin_settings.html");
				$View->Out();
			
			}
		
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