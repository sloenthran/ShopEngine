<?php

	if($Core->CheckAdmin())
	{

		if($_POST['ADD'])
		{
		
			$Name = $Core->ClearText($_POST['NAME']);
			$Value = $Core->ClearText($_POST['VALUE']);
	
			if(!$Name || !$Value)
			{
		
				$View->Load("info");
				$View->Add("title", "Błąd :: Puste pola");
				$View->Add("header", "Błąd! Puste pola!");
				$View->Add("info", "Pola formularza nie mogą być puste!");
				$View->Add("back", "admin_add_settings.html");
				$View->Out();
			
			}
		
			else
			{
		
				$Query = $MySQL->prepare("SELECT `id` FROM `settings` WHERE `name`=:one");
			
				$Query->bindValue(":one", $Name, PDO::PARAM_STR);
			
				$Query->execute();
			
				if($Query->rowCount() > 0)
				{
				
					$View->Load('info');
					$View->Add('title', 'Błąd :: Zajęta nazwa');
					$View->Add('header', 'Błąd! Zajęta nazwa!');
					$View->Add('info', 'Ustawienie o takiej nazwie już istnieje!');
					$View->Add('back', 'admin_add_settings.html');
					$View->Out();
			
				}
			
				else
				{
	
					$Query = $MySQL->prepare("INSERT INTO `settings` VALUES('', :one, :two)");
		
					$Query->bindValue(":one", $Name, PDO::PARAM_STR);
					$Query->bindValue(":two", $Value, PDO::PARAM_STR);
		
					$Query->execute();
					
					$Core->AddAdminLogs('Dodano ustawienie <b>'.$Name.'</b> o wartości <b>'.$Value.'</b>');
					
					$View->Load('info');
					$View->Add('title', 'Admin :: Ustawienia dodane');
					$View->Add('header', 'Ustawienia dodane');
					$View->Add('info', 'Ustawienia zostały poprawnie dodane!');
					$View->Add('back', 'admin_add_settings.html');
					$View->Out();
				
				}
			
			}
	
		}
	
		else
		{
	
			$View->Load("admin_add_settings");
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