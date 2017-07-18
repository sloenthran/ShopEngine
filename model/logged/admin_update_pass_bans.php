<?php

	if($Core->CheckAdmin())
	{
		
		if($_POST['SAVE'])
		{
			
			$Host = $Core->ClearText($_POST['HOST']);
			$User = $Core->ClearText($_POST['USER']);
			$Pass = $Core->ClearText($_POST['PASS']);
			$Base = $Core->ClearText($_POST['BASE']);
			
			$File = new File();
			
			$File->UpdatePassBans($Host, $User, $Pass, $Base);
			
			$Core->AddAdminLogs('Zaktualizowano SQL.cfg');
			
			$View->Load('info');
			$View->Add('title', 'Admin :: Zaktualizowano SQL.cfg');
			$View->Add('header', 'SQL.cfg zaktualizowane');
			$View->Add('info', 'SQL.cfg zostało poprawnie zaktualizowane!');
			$View->Add('back', 'admin_settings.html');
			$View->Out();
			
		}
		
		else
		{
			
			$View->Load("admin_update_pass_bans");
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