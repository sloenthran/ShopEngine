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
		
					$Info .= '<a href="admin_edit_servers-'.$Fetch['id'].'.html"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
					
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
			
				$Name = $Core->ClearText($_POST['NAME']);
				$Host = $Core->ClearText($_POST['HOST']);
				$User = $Core->ClearText($_POST['USER']);
				$Pass = $Core->ClearText($_POST['PASS']);
				$Path = $Core->ClearText($_POST['PATH']);
				$Reservation = $Core->ClearText($_POST['RESERVATION']);
				$Rcon = $Core->ClearText($_POST['RCON']);
				$IP = $Core->ClearText($_POST['IP']);
				$Port = $Core->ClearText($_POST['PORT']);
			
				if(!$Name || !$Host || !$User || !$Pass || !$Path)
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "admin_edit_servers-".$ID.".html");
					$View->Out();
		
				}
				
				else
				{
				
					$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					
					$Query->execute();
					
					$Fetch = $Query->fetch();
					
					$Core->AddAdminLogs("Zmieniono dane serwera <b>".$Fetch['name']."</b>");
			
					$Query = $MySQL->prepare("UPDATE `servers` SET `name`=:one, `host`=:two, `user`=:three, `pass`=:four, `path`=:six, `reservation`=:seven, `rcon_pass`=:eight, `ip`=:nine, `port`=:teen WHERE `id`=:five");
				
					$Query->bindValue(":one", $Name, PDO::PARAM_STR);
					$Query->bindValue(":two", $Host, PDO::PARAM_STR);
					$Query->bindValue(":three", $User, PDO::PARAM_STR);
					$Query->bindValue(":four", $Pass, PDO::PARAM_STR);
					$Query->bindValue(":five", $ID, PDO::PARAM_INT);
					$Query->bindValue(":six", $Path, PDO::PARAM_STR);
					$Query->bindValue(":seven", $Reservation, PDO::PARAM_INT);
					$Query->bindValue(":eight", $Rcon, PDO::PARAM_STR);
					$Query->bindValue(":nine", $IP, PDO::PARAM_STR);
					$Query->bindValue(":teen", $Port, PDO::PARAM_STR);
				
					$Query->execute();
					
					$View->Load("info");
					$View->Add("title", "Dane zapisane");
					$View->Add("header", "Dane zapisane!");
					$View->Add("info", "Dane serwera zostały zapisane!");
					$View->Add("back", "admin_edit_servers-".$ID.".html");
					$View->Out();
					
				}
			
			}
			
			else
			{
			
				$Query = $MySQL->prepare("SELECT * FROM `servers` WHERE `id`=:one");
				
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				
				$Query->execute();
				
				$Fetch = $Query->fetch();
			
				$Info = '<form method="post" action="admin_edit_servers-'.$ID.'.html">
		
					<input type="hidden" name="SAVE" value="true">
			
					<br><input type="text" name="NAME" placeholder="Nazwa serwera" value="'.$Fetch['name'].'"><br>
					<br><input type="text" name="HOST" placeholder="Host FTP" value="'.$Fetch['host'].'"><br>
					<br><input type="text" name="USER" placeholder="Użytkownik FTP" value="'.$Fetch['user'].'"><br>
					<br><input type="password" name="PASS" placeholder="Hasło FTP" value="'.$Fetch['pass'].'"><br>
					<br><input type="text" name="PATH" placeholder="Ścieżka" value="'.$Fetch['path'].'"><br>
					<br><input type="text" name="RESERVATION" placeholder="Darmowa rezerwacja nicku" value="'.$Fetch['reservation'].'"><br>
					<br><input type="text" name="IP" placeholder="IP" value="'.$Fetch['ip'].'"><br>
					<br><input type="text" name="PORT" placeholder="Port" value="'.$Fetch['port'].'"><br>
					<br><input type="text" name="RCON" placeholder="Hasło RCON" value="'.$Fetch['rcon_pass'].'"><br>
			
					<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
		
				$View->Load("admin_servers");
				$View->Add('title', 'Edytuj serwer');
				$View->Add('header', 'Edytuj serwer');
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