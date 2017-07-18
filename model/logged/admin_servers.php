<?php

	if($Core->CheckAdmin())
	{
	
		$ID = $Core->ClearText($_GET['id']);
	
		if(!$ID || $ID == '')
		{
		
			$Info = '<a href="admin_servers-1.html"><button class="przycisk">CS 1.6</button></a><br>';
		
			$View->Load("admin_servers");
			$View->Add('title', 'Wybierz typ serwera');
			$View->Add('header', 'Wybierz typ serwera');
			$View->Add('info', $Info);
			$View->Out();
		
		}
		
		else
		{
		
			if($_POST['ADD'])
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
			
				if(!$Name || !$Host || !$User || !$Pass)
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "admin_servers-".$ID.".html");
					$View->Out();
		
				}
				
				else
				{
					
					if($ID == 1)
					{
			
						$Query = $MySQL->prepare("INSERT INTO `servers` VALUES('', :one, :two, :three, :four, :five, :six, :seven, :eight, :nine, :teen)");
				
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
					
						$Core->AddAdminLogs("Dodano serwer CS 1.6");
						
					}
					
					$View->Load("info");
					$View->Add("title", "Serwer dodany");
					$View->Add("header", "Serwer dodany!");
					$View->Add("info", "Serwer został poprawnie dodany!");
					$View->Add("back", "admin_servers-".$ID.".html");
					$View->Out();
					
				}
			
			}
			
			else
			{
			
				if($ID == 1)
				{
				
					$Info = '<form method="post" action="admin_servers-1.html">
		
						<input type="hidden" name="ADD" value="true">
			
						<br><input type="text" name="NAME" placeholder="Nazwa serwera"><br>
						<br><input type="text" name="HOST" placeholder="Host FTP"><br>
						<br><input type="text" name="USER" placeholder="Użytkownik FTP"><br>
						<br><input type="password" name="PASS" placeholder="Hasło FTP"><br>
						<br><input type="text" name="PATH" placeholder="Ścieżka" value="cstrike/addons/amxmodx/configs"><br>
						<br><input type="text" name="RESERVATION" placeholder="Darmowa rezerwacja nicku" value="0"><br>
						<br><input type="text" name="IP" placeholder="IP serwera"><br>
						<br><input type="text" name="PORT" placeholder="Port serwera"><br>
						<br><input type="text" name="RCON" placeholder="Hasło RCON"><br>
			
						<br><button type="submit" class="przycisk">Dodaj serwer <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
				
				}
		
				$View->Load("admin_servers");
				$View->Add('title', 'Dodaj serwer');
				$View->Add('header', 'Dodaj serwer');
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