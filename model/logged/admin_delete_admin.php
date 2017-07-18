<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
	
		if(!$ID || $ID == '')
		{
	
			$Info .= '<table>
			<tr>
		
				<td class="nag">Nick lub SID</td>
				<td class="nag">Użytkownik</td>
				<td class="nag">Nazwa premki</td>
				<td class="nag">Serwer</td>
				<td class="nag">Czas</td>
				<td class="nag">Usuń</td>
			
			</tr>';	
			
			$Query = $MySQL->query("SELECT * FROM `premium_cache` WHERE `premium_id`='0'  ORDER BY `server` ASC");
	
			while($Fetch = $Query->fetch())
			{
	
				$OldDate = $Fetch['time'];
		
				$Date = time();
		
				$Diff = $OldDate - $Date;
				$Days = floor($Diff / (24*60*60));

				$Diff  = $Diff - ($Days * 24*60*60);
				$Hours = floor($Diff / (60*60));
	
				$Diff    = $Diff - ($Hours * 60*60);
				$Minutes = floor($Diff / (60));

				$Seconds = $Diff - ($Minutes * 60);
				
				if($Days > 10000)
				{
					
					$Time = 'Bez limitu';
					
				}
				
				else
				{
					
					$Time = ''.$Days.' dni, '.$Hours.' godzin, '.$Minutes.' minut, '.$Seconds.' sekund'.'';
					
				}
		
				
				
				$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
				$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
				$QueryTwo->execute();
		
				$FetchTwo = $QueryTwo->fetch();
				
				$QueryThree = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryThree->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$QueryThree->execute();
				
				$FetchA = $QueryThree->fetch();
		
				$Info .= '<tr>
					<td>'.$Fetch['nick'].'</td>
					<td>'.$FetchA['login'].'</td>
					<td>Admin</td>
					<td>'.$FetchTwo['name'].'</td>
					<td>'.$Time.'</td>
					<td><a href="admin_delete_admin-1:'.$Fetch['id'].'.html">[X]</a></td>
				</tr>';
				
			}
			
			$Query = $MySQL->query("SELECT * FROM `multi_admins`  ORDER BY `type` ASC");
	
			while($Fetch = $Query->fetch())
			{
	
				if($Fetch['time'] == 0)
				{
					
					$Time = 'Bez limitu';
					
				}
				
				else
				{
					
					$OldDate = $Fetch['time'];
		
					$Date = time();
		
					$Diff = $OldDate - $Date;
					$Days = floor($Diff / (24*60*60));

					$Diff  = $Diff - ($Days * 24*60*60);
					$Hours = floor($Diff / (60*60));
	
					$Diff    = $Diff - ($Hours * 60*60);
					$Minutes = floor($Diff / (60));

					$Seconds = $Diff - ($Minutes * 60);
		
					$Time = ''.$Days.' dni, '.$Hours.' godzin, '.$Minutes.' minut, '.$Seconds.' sekund'.'';
					
				}
				
				switch($Fetch['type'])
				{
		
					case 2:
			
						$Type = 'Multi Admin';
			
					break;
			
					case 3:
			
						$Type = 'Administrator';
			
					break;
		
				}
				
				$QueryThree = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryThree->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$QueryThree->execute();
				
				$FetchA = $QueryThree->fetch();
				
				$Info .= '<tr>
					<td>'.$Fetch['sid'].'</td>
					<td>'.$FetchA['login'].'</td>
					<td>'.$Type.'</td>
					<td>Wszystkie</td>
					<td>'.$Time.'</td>
					<td><a href="admin_delete_admin-2:'.$Fetch['id'].'.html">[X]</a></td>
				</tr>';
				
			}
			
			$Query = $MySQL->query("SELECT * FROM `guardians`");
	
			while($Fetch = $Query->fetch())
			{
				
				$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
				$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
				$QueryTwo->execute();
				
				$FetchTwo = $QueryTwo->fetch();
				
				$QueryThree = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryThree->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$QueryThree->execute();
				
				$FetchA = $QueryThree->fetch();
				
				$Info .= '<tr>
					<td>'.$Fetch['sid'].'</td>
					<td>'.$FetchA['login'].'</td>
					<td>Opiekun</td>
					<td>'.$FetchTwo['name'].'</td>
					<td>Bez limitu</td>
					<td><a href="admin_delete_admin-3:'.$Fetch['id'].'.html">[X]</a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
			
			$View->Load("admin_members");
			$View->Add('title', 'Usuń admina');
			$View->Add('header', 'Usuń admina');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			$ID = explode(":", $ID);
			
			if($ID[0] == 1)
			{
				
				$Query = $MySQL->prepare("SELECT `nick`, `user_id` FROM `premium_cache` WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Name = $Fetch['nick'];
				
				$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$Query->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Core->AddAdminLogs("Usunięto admina <b>".$Name."</b> użytkownikowi <b>".$Fetch['login']."</b>");
				
			}
			
			else if($ID[0] == 3)
			{
				
				$Query = $MySQL->prepare("SELECT `sid`, `user_id` FROM `guardians` WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Name = $Fetch['sid'];
				
				$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$Query->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Query = $MySQL->prepare("DELETE FROM `guardians` WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Core->AddAdminLogs("Usunięto opiekuna <b>".$Name."</b> użytkownikowi <b>".$Fetch['login']."</b>");
				
			}
			
			else
			{
				
				$Query = $MySQL->prepare("SELECT `sid`, `user_id` FROM `multi_admins` WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Name = $Fetch['sid'];
				
				$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$Query->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Query = $MySQL->prepare("DELETE FROM `multi_admins` WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Core->AddAdminLogs("Usunięto multi admina <b>".$Name."</b> użytkownikowi <b>".$Fetch['login']."</b>");
				
			}
			
			$View->Load("info");
			$View->Add("title", "Admin usunięty");
			$View->Add("header", "Admin usunięty!");
			$View->Add("info", "Admin zostanie usunięty za około minutę!");
			$View->Add("back", "admin_delete_admin.html");
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