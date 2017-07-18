<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		
		if($ID == '' || !$ID)
		{
			
			$Query = $MySQL->query("SELECT `name`, `id` FROM `servers`");
			
			while($Fetch = $Query->fetch())
			{
				
				$Info .= '<a href="admin_add_premium-'.$Fetch['id'].'.html"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
				
			}
			
			$View->Load("admin_members");
			$View->Add('title', 'Wybierz serwer');
			$View->Add('header', 'Wybierz serwer');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			if($_POST['ADD'])
			{
				
				$Nick = $Core->ClearText($_POST['NAME']);
				$Pass = $Core->ClearText($_POST['PASS']);
				$PremiumID = $Core->ClearText($_POST['BUY']);
				$UserID = $Core->ClearText($_POST['USER']);
				$Days = $Core->ClearText($_POST['DAYS']);
				
				$Buy = new Buy();
			
				$Buy->AddBuy($Nick, $Pass, $PremiumID, $UserID, $Days, false);
				
				$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$Query->bindValue(":one", $UserID, PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$User = $Fetch['login'];
				
				$Query = $MySQL->prepare("SELECT `server` FROM `buy` WHERE `id`=:one");
				$Query->bindValue(":one", $PremiumID, PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
				$Query->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Core->AddAdminLogs("Dodano użytkownikowi <b>".$User."</b> premium na nick <b>".$Nick."</b> na serwerze <b>".$Fetch['name']."</b>");
				
				$View->Load("info");
				$View->Add("title", "Zakup dodany");
				$View->Add("header", "Zakup dodany!");
				$View->Add("info", "Zakup został poprawnie dodany!");
				$View->Add("back", "admin_add_premium-".$ID.".html");
				$View->Out();
				
			}
			
			else
			{
				
				$Query = $MySQL->query("SELECT `id`, `login` FROM `users`  ORDER BY `login` ASC");
				
				while($Fetch = $Query->fetch())
				{
					
					$User .= '<option value="'.$Fetch['id'].'">'.$Fetch['login'].'</option>';
					
				}
				
				$Query = $MySQL->prepare("SELECT `id`, `name` FROM `buy` WHERE `server`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
	
				while($Fetch = $Query->fetch())
				{
					
					$Buy .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
				}
				
				$Info = '<form method="post" action="admin_add_premium-'.$ID.'.html">
		
					<input type="hidden" name="ADD" value="true">
			
					<br><input type="text" name="NAME" placeholder="SID lub Nick"><br>
					<br><input type="text" name="PASS" placeholder="Hasło"><br>
					<br><input type="text" name="DAYS" placeholder="Ilość dni"><br>
					<br><select name="USER">'.$User.'</select><br>
					<br><select name="BUY">'.$Buy.'</select><br>
			
					<br><button type="submit" class="przycisk">Dodaj <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
					
				$View->Load("admin_members");
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