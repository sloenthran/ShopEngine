<?php

	if($Core->CheckAdmin())
	{
	
		if($_POST['DELETE'])
		{
		
			$ID = $Core->ClearText($_POST['ID']);
			
			$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Core->AddAdminLogs("Usunięto serwer <b>".$Fetch['name']."</b>");
			
			$Query = $MySQL->prepare("DELETE FROM `servers` WHERE `id`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("DELETE FROM `buy` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("DELETE FROM `premium` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("DELETE FROM `premium_cache` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("DELETE FROM `guardians` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("DELETE FROM `server_cash` WHERE `server_id`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$View->Load("info");
			$View->Add("title", "Admin :: Serwer usunięty");
			$View->Add("header", "Serwer usunięty!");
			$View->Add("info", "Serwer został poprawnie usunięty!");
			$View->Add("back", "admin_delete_servers.html");
			$View->Out();
		
		}
		
		else
		{
		
			$Query = $MySQL->query("SELECT `id`,`name` FROM `servers`");
			
			if($Query->rowCount() > 0)
			{
			
				$Info .= '<form method="post" action="admin_delete_servers.html">
		
					<input type="hidden" name="DELETE" value="true">
				
					<br><select name="ID">';
				
					while($Fetch = $Query->fetch())
					{
						
						$Info .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
					}
				
				$Info .= '</select><br>
					
					<br><button type="submit" class="przycisk">Usuń <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
		
				$View->Load("admin_servers");
				$View->Add('title', 'Usuń serwer');
				$View->Add('header', 'Usuń serwer');
				$View->Add('info', $Info);
				$View->Out();
			
			}
			
			else
			{
			
				$View->Load("info");
				$View->Add("title", "Błąd :: Brak serwerów");
				$View->Add("header", "Błąd! Brak serwerów do usunięcia!");
				$View->Add("info", "Nie ma żadnych serwerów do usunięcia!");
				$View->Add("back", "admin_servers.html");
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