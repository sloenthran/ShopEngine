<?php

	if($Core->CheckAdmin())
	{
	
		$ID = $Core->ClearText($_GET['id']);
	
		if(!$ID || $ID == '')
		{
		
			$Info = '<a href="admin_members-1.html"><button class="przycisk">Admin</button></a><br>
			<a href="admin_members-2.html"><button class="przycisk">Multi Admin</button></a><br>
			<a href="admin_members-3.html"><button class="przycisk">Administrator</button></a><br>
			<a href="admin_members-4.html"><button class="przycisk">Opiekun serwera</button></a>';
		
			$View->Load("admin_members");
			$View->Add('title', 'Wybierz typ admina');
			$View->Add('header', 'Wybierz typ admina');
			$View->Add('info', $Info);
			$View->Out();
		
		}
		
		else
		{
		
			if($_POST['ADD'])
			{
			
				$Name = $Core->ClearText($_POST['NAME']);
				$Pass = $Core->ClearText($_POST['PASS']);
				$User = $Core->ClearText($_POST['USER']);
				$Server = $Core->ClearText($_POST['SERVER']);
				$AddFlags = $Core->ClearText($_POST['FLAGS']);
				$Days = $Core->ClearText($_POST['DAYS']);
			
				if(!$Name || !$Pass)
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "admin_members-".$ID.".html");
					$View->Out();
		
				}				
				
				else
				{
				
					$BAD = false;
				
					/*if($ID == 1 || $ID == 2)
					{
					
						$Query = $MySQL->prepare("SELECT `money` FROM `users` WHERE `id`=:one");
				
						$Query->bindValue(":one", $User, PDO::PARAM_INT);
						
						$Query->execute();
					
						$Fetch = $Query->fetch();
						
						if($Fetch['money'] < 11)
						{
						
							$BAD = true;
						
							$View->Load("info");
							$View->Add("title", "Brak kasy");
							$View->Add("header", "Błąd! Brak kasy!");
							$View->Add("info", "Nie możesz dodać admina tej osobie ponieważ ma zbyt mało wPLN!");
							$View->Add("back", "admin_members-".$ID.".html");
							$View->Out();
							
						}
						
						else
						{
						
							$BAD = false;
						
							$Query = $MySQL->prepare("UPDATE `users` SET `money`=:one WHERE `id`=:two");
							
							$Query->bindValue(":one", $Fetch['money'] - 11, PDO::PARAM_INT);
							$Query->bindValue(":two", $User, PDO::PARAM_INT);
							
							$Query->execute();
						
						}
						
					}*/
					
					if(!$BAD)
					{
						
						$DaysTime = $Days * 86400;
				
						$Time = time() + $DaysTime;
				
						if($ID == 1)
						{
					
							$Query = $MySQL->prepare("SELECT * FROM `premium` WHERE `nick`=:one AND `server`=:two");
			
							$Query->bindValue(":one", $Name, PDO::PARAM_STR);
							$Query->bindValue(":two", $Server, PDO::PARAM_INT);
			
							$Query->execute();
						
							if($Query->rowCount() > 0)
							{
						
								$QueryTwo = $MySQL->prepare("INSERT INTO `premium_cache` VALUES('', :one, :two, :six, :three, :four, :five, '0')");
				
								$QueryTwo->bindValue(":one", $Name, PDO::PARAM_STR);
								$QueryTwo->bindValue(":two", $Pass, PDO::PARAM_STR);
								$QueryTwo->bindValue(":three", $Server, PDO::PARAM_INT);
								$QueryTwo->bindValue(":four", $Time, PDO::PARAM_INT);
								$QueryTwo->bindValue(":five", $User, PDO::PARAM_INT);
								$QueryTwo->bindValue(":six", $AddFlags, PDO::PARAM_STR);
							
								$QueryTwo->execute();
							
								$Buy = new Buy();
								
								$QueryThree = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `nick`=:one AND `server`=:two");
			
								$QueryThree->bindValue(":one", $Name, PDO::PARAM_STR);
								$QueryThree->bindValue(":two", $Server, PDO::PARAM_INT);
								
								$QueryThree->execute();
							
								while($Fetch = $QueryThree->fetch())
								{
				
									$Flags .= $Buy->SumFlags($Flags, $Fetch['flags']);
				
								}
			
								$QueryFour = $MySQL->prepare("UPDATE `premium` SET `flags`=:one WHERE `nick`=:two AND `server`=:three");
				
								$QueryFour->bindValue(":one", $Flags, PDO::PARAM_STR);
								$QueryFour->bindValue(":two", $Name, PDO::PARAM_STR);
								$QueryFour->bindValue(":three", $Server, PDO::PARAM_INT);
				
								$QueryFour->execute();	
						
							}
						
							else
							{
				
								$Query = $MySQL->prepare("INSERT INTO `premium` VALUES('', :one, :two, :four, :three)");
				
								$Query->bindValue(":one", $Name, PDO::PARAM_STR);
								$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
								$Query->bindValue(":three", $Server, PDO::PARAM_INT);
								$Query->bindValue(":four", $AddFlags, PDO::PARAM_STR);
				
								$Query->execute();
				
								$Query = $MySQL->prepare("INSERT INTO `premium_cache` VALUES('', :one, :two, :six, :three, :four, :five, '0')");
				
								$Query->bindValue(":one", $Name, PDO::PARAM_STR);
								$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
								$Query->bindValue(":three", $Server, PDO::PARAM_INT);
								$Query->bindValue(":four", $Time, PDO::PARAM_INT);
								$Query->bindValue(":five", $User, PDO::PARAM_INT);
								$Query->bindValue(":six", $AddFlags, PDO::PARAM_STR);
				
								$Query->execute();
			
							}
						
							$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
						
							$Query->bindValue(":one", $Server, PDO::PARAM_INT);
						
							$Query->execute();
						
							$Fetch = $Query->fetch();
						
							$Data[0] = $Fetch['name'];
						
							$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
						
							$Query->bindValue(":one", $User, PDO::PARAM_INT);
						
							$Query->execute();
						
							$Fetch = $Query->fetch();
						
							$Data[1] = $Fetch['login'];
						
							$Core->AddAdminLogs("Dodano admina <b>".$Name."</b> na serwerze <b>".$Data[0]."</b> użytkownikowi <b>".$Data[1]."</b>");
					
						}
						
						else if($ID == 4)
						{

							$Query = $MySQL->prepare("INSERT INTO `guardians` VALUES('', :one, :two, :three, :four, :five)");

							$Query->bindValue(":one", $Name, PDO::PARAM_STR);
							$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
							$Query->bindValue(":three", $User, PDO::PARAM_INT);
							$Query->bindValue(":four", $Server, PDO::PARAM_INT);
							$Query->bindValue(":five", $AddFlags, PDO::PARAM_STR);
						
							$Query->execute();
						
							$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
						
							$Query->bindValue(":one", $Server, PDO::PARAM_INT);
						
							$Query->execute();
						
							$Fetch = $Query->fetch();
						
							$Data[0] = $Fetch['name'];
						
							$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
						
							$Query->bindValue(":one", $User, PDO::PARAM_INT);
						
							$Query->execute();
						
							$Fetch = $Query->fetch();
						
							$Data[1] = $Fetch['login'];
						
							$Core->AddAdminLogs("Dodano opiekuna <b>".$Name."</b> na serwerze <b>".$Data[0]."</b> użytkownikowi <b>".$Data[1]."</b>");
							
						}
					
						else
						{
					
							if($ID != 2)
							{
						
								$Time = 0;
						
							}
						
							$Query = $MySQL->prepare("INSERT INTO `multi_admins` VALUES('', :one, :two, :three, :four, :five)");

							$Query->bindValue(":one", $Name, PDO::PARAM_STR);
							$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
							$Query->bindValue(":three", $User, PDO::PARAM_INT);
							$Query->bindValue(":four", $Time, PDO::PARAM_INT);
							$Query->bindValue(":five", $ID, PDO::PARAM_INT);
						
							$Query->execute();
						
							$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
						
							$Query->bindValue(":one", $User, PDO::PARAM_INT);
						
							$Query->execute();
						
							$Fetch = $Query->fetch();
						
							$Core->AddAdminLogs("Dodano multi admina <b>".$Name."</b> użytkownikowi <b>".$Fetch['login']."</b>");
					
						}
					
						$View->Load("info");
						$View->Add("title", "Admin dodany");
						$View->Add("header", "Admin dodany!");
						$View->Add("info", "Admin został poprawnie dodany!");
						$View->Add("back", "admin_members-".$ID.".html");
						$View->Out();
				
					}
					
				}
			
			}
			
			else
			{
			
				$Query = $MySQL->query("SELECT `id`, `login` FROM `users` ORDER BY `login` ASC");
				
				while($Fetch = $Query->fetch())
				{
					
					$User .= '<option value="'.$Fetch['id'].'">'.$Fetch['login'].'</option>';
					
				}
			
				if($ID == 1 || $ID == 4)
				{
					
					if($ID == 4)
					{
						
						$ValueFlags = 'abcdefghijklmnopqrstuvwx';
						
					}
					
					else
					{
						
						$ValueFlags = 'bcdefiju';
						
					}
				
					$Query = $MySQL->query("SELECT `id`, `name` FROM `servers`");
					
					while($Fetch = $Query->fetch())
					{
					
						$Server .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
					}
				
					$Info = '<form method="post" action="admin_members-'.$ID.'.html">
		
						<input type="hidden" name="ADD" value="true">
			
						<br><input type="text" name="NAME" placeholder="SID lub Nick"><br>
						<br><input type="text" name="PASS" placeholder="Hasło"><br>
						<br><input type="text" name="FLAGS" placeholder="Flagi" value="'.$ValueFlags.'"><br>
						<br><input type="text" name="DAYS" placeholder="Ilość dni" value="30"><br>
						<br><select name="USER">'.$User.'</select><br>
						<br><select name="SERVER">'.$Server.'</select><br>
			
						<br><button type="submit" class="przycisk">Dodaj <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
				
				}
				
				else
				{
				
					$Info = '<form method="post" action="admin_members-'.$ID.'.html">
		
						<input type="hidden" name="ADD" value="true">
			
						<br><input type="text" name="NAME" placeholder="SID lub Nick"><br>
						<br><input type="text" name="PASS" placeholder="Hasło"><br>
						<br><input type="text" name="DAYS" placeholder="Ilość dni" value="30"><br>
						<br><select name="USER">'.$User.'</select><br>
			
						<br><button type="submit" class="przycisk">Dodaj <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
				
				}
				
				$View->Load("admin_members");
				$View->Add('title', 'Dodaj admina');
				$View->Add('header', 'Dodaj admina');
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