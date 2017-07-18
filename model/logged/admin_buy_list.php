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
				<td class="nag">Przedłuż</td>
			
			</tr>';
			
			$Query = $MySQL->query("SELECT * FROM `premium_cache` WHERE `premium_id`!='0' ORDER BY `server` ASC");
			
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
		
				$QueryTwo = $MySQL->prepare("SELECT `name` FROM `buy` WHERE `id`=:one");
				$QueryTwo->bindValue(":one", $Fetch['premium_id'], PDO::PARAM_INT);
				$QueryTwo->execute();
				
				$FetchB = $QueryTwo->fetch();
		
				$Info .= '<tr>
					<td>'.$Fetch['nick'].'</td>
					<td>'.$FetchA['login'].'</td>
					<td>'.$FetchB['name'].'</td>
					<td>'.$FetchTwo['name'].'</td>
					<td>'.$Time.'</td>
					<td><a href="admin_buy_list-1:'.$Fetch['id'].'.html">[X]</a></td>
					<td><a href="admin_buy_list-2:'.$Fetch['id'].'.html">[X]</a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
			
			$View->Load("admin_members");
			$View->Add('title', 'Lista zakupów');
			$View->Add('header', 'Lista zakupów');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			$ID = explode(":", $ID);
			
			if($ID[0] == 1)
			{
				
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `id`=:one");
				$Query->bindValue(":one", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Core->AddAdminLogs("Usunięto zakup o ID ".$ID[1]."");
				
				$View->Load("info");
				$View->Add("title", "Zakup usunięty");
				$View->Add("header", "Zakup usunięty!");
				$View->Add("info", "Zakup zostanie usunięty w ciągu minuty!");
				$View->Add("back", "admin_buy_list.html");
				$View->Out();
				
			}
			
			else
			{
				
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`=`time`+:one WHERE `id`=:two");
				$Query->bindValue(":one", 2592000, PDO::PARAM_INT);
				$Query->bindValue(":two", $ID[1], PDO::PARAM_INT);
				$Query->execute();
				
				$Core->AddAdminLogs("Przedłużono zakup o ID ".$ID[1]."");
				
				$View->Load("info");
				$View->Add("title", "Zakup przedłużony");
				$View->Add("header", "Zakup przedłużony!");
				$View->Add("info", "Zakup został poprawnie przedłużony!");
				$View->Add("back", "admin_buy_list.html");
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