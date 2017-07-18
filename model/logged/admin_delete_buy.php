<?php

	if($Core->CheckAdmin())
	{
	
		$ID = $Core->ClearText($_GET['id']);
	
		if(!$ID || $ID == '')
		{
		
			$Query = $MySQL->query("SELECT `id`, `name` FROM `servers`");
			
			if($Query->rowCount() > 0)
			{
			
				while($Fetch = $Query->fetch())
				{
		
					$Info .= '<a href="admin_delete_buy-'.$Fetch['id'].'.html"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
					
				}
		
				$View->Load("admin_servers");
				$View->Add('title', 'Wybierz serwer');
				$View->Add('header', 'Wybierz serwer');
				$View->Add('info', $Info);
				$View->Out();
				
			}
			
			else
			{
				
				$View->Load("info");
				$View->Add("title", "Błąd :: Brak serwerów");
				$View->Add("header", "Błąd! Brak serwerów do edycji!");
				$View->Add("info", "Nie ma żadnych serwerów do edycji!");
				$View->Add("back", "admin_servers.html");
				$View->Out();
				
			}
		
		}
		
		else
		{
		
			if($_POST['SAVE'])
			{
			
				$Name = $Core->ClearText($_POST['BUY']);
			
				if(!$Name)
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "admin_delete_buy-".$ID.".html");
					$View->Out();
		
				}
				
				else
				{
			
					$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `premium_id`=:one");
					$Query->bindValue(":one", $Name, PDO::PARAM_INT);
					$Query->execute();
					
					$Query = $MySQL->prepare("DELETE FROM `buy` WHERE `id`=:one");
			
					$Query->bindValue(":one", $Name, PDO::PARAM_INT);
			
					$Query->execute();
					
					$Core->AddAdminLogs('Usunięto zakup o ID '.$Name.'');
					
					$View->Load("info");
					$View->Add("title", "Zakup usunięty");
					$View->Add("header", "Zakup usunięty!");
					$View->Add("info", "Zakup został usunięty!");
					$View->Add("back", "admin_delete_buy-".$ID.".html");
					$View->Out();
					
				}
			
			}
			
			else
			{
			
				$Query = $MySQL->prepare("SELECT * FROM `buy` WHERE `server`=:one");
				
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				
				$Query->execute();
				
				while($Fetch = $Query->fetch())
				{
					
					$Data .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
				}
			
				$Info = '<form method="post" action="admin_delete_buy-'.$ID.'.html">
		
					<input type="hidden" name="SAVE" value="true">
			
					<br><br><br><select name="BUY">'.$Data.'</select>
			
					<br><button type="submit" class="przycisk">Usuń <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
		
				$View->Load("admin_servers");
				$View->Add('title', 'Usuń zakup');
				$View->Add('header', 'Usuń zakup');
				$View->Add('info', $Info);
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