<?php

	$Check = $Core->CheckGuard();

	if($Check > 0)
	{
		
		if($_POST['ADD'])
		{
			
			$User = $Core->ClearText($_POST['USER']);
			$Name = $Core->ClearText($_POST['NAME']);
			$Pass = $Core->ClearText($_POST['PASS']);
			
			$Time = time() + 2592000;
			
			$Query = $MySQL->prepare("SELECT * FROM `premium` WHERE `nick`=:one AND `server`=:two");
			
			$Query->bindValue(":one", $Name, PDO::PARAM_STR);
			$Query->bindValue(":two", $Check, PDO::PARAM_INT);
			
			$Query->execute();
		
			if($Query->rowCount() > 0)
			{
				
				$QueryTwo = $MySQL->prepare("INSERT INTO `premium_cache` VALUES('', :one, :two, 'bcdefiju', :three, :four, :five, '0')");
			
				$QueryTwo->bindValue(":one", $Name, PDO::PARAM_STR);
				$QueryTwo->bindValue(":two", $Pass, PDO::PARAM_STR);
				$QueryTwo->bindValue(":three", $Check, PDO::PARAM_INT);
				$QueryTwo->bindValue(":four", $Time, PDO::PARAM_INT);
				$QueryTwo->bindValue(":five", $User, PDO::PARAM_INT);
			
				$QueryTwo->execute();
		
				$Buy = new Buy();
				
				$QueryThree = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `nick`=:one AND `server`=:two");
			
				$QueryThree->bindValue(":one", $Name, PDO::PARAM_STR);
				$QueryThree->bindValue(":two", $Check, PDO::PARAM_INT);
				
				$QueryThree->execute();
				
				while($Fetch = $QueryThree->fetch())
				{
				
					$Flags .= $Buy->SumFlags($Flags, $Fetch['flags']);
				
				}
			
				$QueryFour = $MySQL->prepare("UPDATE `premium` SET `flags`=:one WHERE `nick`=:two AND `server`=:three");
				
				$QueryFour->bindValue(":one", $Flags, PDO::PARAM_STR);
				$QueryFour->bindValue(":two", $Name, PDO::PARAM_STR);
				$QueryFour->bindValue(":three", $Check, PDO::PARAM_INT);
				
				$QueryFour->execute();	
			
			}
		
			else
			{
				
				$Query = $MySQL->prepare("INSERT INTO `premium` VALUES('', :one, :two, 'bcdefiju', :three)");
				
				$Query->bindValue(":one", $Name, PDO::PARAM_STR);
				$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
				$Query->bindValue(":three", $Check, PDO::PARAM_INT);
				
				$Query->execute();
				
				$Query = $MySQL->prepare("INSERT INTO `premium_cache` VALUES('', :one, :two, 'bcdefiju', :three, :four, :five, '0')");
				
				$Query->bindValue(":one", $Name, PDO::PARAM_STR);
				$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
				$Query->bindValue(":three", $Check, PDO::PARAM_INT);
				$Query->bindValue(":four", $Time, PDO::PARAM_INT);
				$Query->bindValue(":five", $User, PDO::PARAM_INT);
				
				$Query->execute();
			
			}
		
			$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
			$Query->bindValue(":one", $Check, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
		
			$Data[0] = $Fetch['name'];
		
			$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
		
			$Query->bindValue(":one", $User, PDO::PARAM_INT);
		
			$Query->execute();
			
			$Fetch = $Query->fetch();
		
			$Data[1] = $Fetch['login'];
		
			$Core->AddAdminLogs("[GUARD] Dodano admina <b>".$Name."</b> na serwerze <b>".$Data[0]."</b> użytkownikowi <b>".$Data[1]."</b>");
			
			$View->Load("info");
			$View->Add("title", "Admin dodany");
			$View->Add("header", "Admin dodany!");
			$View->Add("info", "Admin został poprawnie dodany!");
			$View->Add("back", "guardian_admin.html");
			$View->Out();
			
		}
		
		else
		{
			
			$Query = $MySQL->query("SELECT `id`, `login` FROM `users` ORDER BY `login` ASC");
			
			while($Fetch = $Query->fetch())
			{
				
				$User .= '<option value="'.$Fetch['id'].'">'.$Fetch['login'].'</option>';
				
			}
		
			$Info = '<form method="post" action="guardian_admin.html">
		
				<input type="hidden" name="ADD" value="true">
			
				<br><input type="text" name="NAME" placeholder="SID lub Nick"><br>
				<br><input type="text" name="PASS" placeholder="Hasło"><br>
				<br><select name="USER">'.$User.'</select><br>
			
				<br><button type="submit" class="przycisk">Dodaj <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("guard_admin");
			$View->Add('header', 'Dodaj admina');
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