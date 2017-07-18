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
		
					$Info .= '<a href="admin_add_buy-'.$Fetch['id'].'.html"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
				
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
				$View->Add("header", "Błąd! Brak serwerów!");
				$View->Add("info", "Nie ma żadnych serwerów!");
				$View->Add("back", "admin_servers.html");
				$View->Out();
				
			}
		
		}
		
		else
		{
		
			if($_POST['ADD'])
			{
			
				$Name = $Core->ClearText($_POST['NAME']);
				$Cash = $Core->ClearText($_POST['CASH']);
				$Flags = $Core->ClearText($_POST['FLAGS']);
				$Days = $Core->ClearText($_POST['DAYS']);
				$Text = nl2br($_POST['TEXT']);
			
				if(!$Name || !$Cash || !$Flags || !$Text)
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "admin_add_buy-".$ID.".html");
					$View->Out();
		
				}
				
				else
				{
				
					$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					
					$Query->execute();
					
					$Fetch = $Query->fetch();
					
					$Core->AddAdminLogs("Dodano zakup na serwerze <b>".$Fetch['name']."</b>");
			
					$Query = $MySQL->prepare("INSERT INTO `buy` VALUES('', :one, :two, :three, :four, :five, :six)");
				
					$Query->bindValue(":one", $Name, PDO::PARAM_STR);
					$Query->bindValue(":two", $Cash, PDO::PARAM_INT);
					$Query->bindValue(":three", $Flags, PDO::PARAM_STR);
					$Query->bindValue(":four", $Text, PDO::PARAM_STR);
					$Query->bindValue(":five", $ID, PDO::PARAM_INT);
					$Query->bindValue(":six", $Days, PDO::PARAM_INT);
				
					$Query->execute();
					
					$View->Load("info");
					$View->Add("title", "Zakup dodany");
					$View->Add("header", "Zakup dodany!");
					$View->Add("info", "Zakup został poprawnie dodany!");
					$View->Add("back", "admin_add_buy-".$ID.".html");
					$View->Out();
					
				}
			
			}
			
			else
			{
			
				$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
				$Fetch = $Query->fetch();
				
				$Pay = new $Fetch['value']();
				
				$Price = $Pay->GetPrice();
				
				foreach($Price as $Key => $Value)
				{
				
					$Cash .= '<option value="'.$Value['amount'].'">'.$Value['cost'].' PLN</option>';
				
				}
			
				$Info = '<form method="post" action="admin_add_buy-'.$ID.'.html">
		
					<input type="hidden" name="ADD" value="true">
			
					<br><input type="text" name="NAME" placeholder="Nazwa"><br>
					<br><select type="text" name="CASH">'.$Cash.'</select><br>
					<br><input type="text" name="FLAGS" placeholder="Flagi"><br>
					<br><input type="text" name="DAYS" placeholder="Ilość dni" value="30"><br>
					<br><textarea name="TEXT" placeholder="Opis"></textarea><br>
			
					<br><button type="submit" class="przycisk">Dodaj zakup <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
		
				$View->Load("admin_servers");
				$View->Add('title', 'Dodaj zakup');
				$View->Add('header', 'Dodaj zakup');
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