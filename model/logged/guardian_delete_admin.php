<?php

	$Check = $Core->CheckGuard();

	if($Check > 0)
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
			
			$Query = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `premium_id`='0' AND `server`=:one");
			$Query->bindValue(":one", $Check, PDO::PARAM_INT);
			$Query->execute();
	
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
		
				$QueryTwo->bindValue(":one", $Check, PDO::PARAM_INT);
		
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
					<td><a href="guardian_delete_admin-'.$Fetch['id'].'.html">[X]</a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
			
			$View->Load("guard_admin");
			$View->Add('header', 'Usuń admina');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
			$Query->bindValue(":one", $Check, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
		
			$Server = $Fetch['name'];
			
			$Query = $MySQL->prepare("SELECT `nick`, `user_id` FROM `premium_cache` WHERE `id`=:one");
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Name = $Fetch['nick'];
			
			$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
			$Query->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `id`=:one");
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$Core->AddAdminLogs("[GUARD] Usunięto admina <b>".$Name."</b> użytkownikowi <b>".$Fetch['login']."</b> na serwerze <b>".$Server."</b>");
			
			$View->Load("info");
			$View->Add("title", "Admin usunięty");
			$View->Add("header", "Admin usunięty!");
			$View->Add("info", "Admin zostanie usunięty za około minutę!");
			$View->Add("back", "guardian_delete_admin.html");
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