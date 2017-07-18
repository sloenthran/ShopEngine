<?php

	$Info .= '<table>
	
	<tr>
		
		<td class="nag">Nick lub SID</td>
		<td class="nag">Nazwa premki</td>
		<td class="nag">Serwer</td>
		<td class="nag">Czas</td>
		<td class="nag">Przedłużenie</td>
		
	</tr>';
	
	$Query = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `user_id`=:one");
	
	$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
	
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
	
		if($Fetch['premium_id'] == 0)
		{
	
			$Data[0] = 'Admin';
			$Data[1] = '11';
			
		}
		
		else
		{
		
			$QueryTwo = $MySQL->prepare("SELECT `name`,`cash` FROM `buy` WHERE `id`=:one");
			
			$QueryTwo->bindValue(":one", $Fetch['premium_id'], PDO::PARAM_INT);
			
			$QueryTwo->execute();
			
			$FetchTwo = $QueryTwo->fetch();
			
			$Data[0] = $FetchTwo['name'];
			$Data[1] = $FetchTwo['cash'];
		
		}
		
		$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
		$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
		$QueryTwo->execute();
		
		$FetchTwo = $QueryTwo->fetch();
		
		$Info .= '<tr>
			<td>'.$Fetch['nick'].'</td>
			<td>'.$Data[0].'</td>
			<td>'.$FetchTwo['name'].'</td>
			<td>'.$Time.'</td>
			<td><a href="extension-'.$Fetch['id'].'.html">['.$Data[1].' wPLN]</a></td>
		</tr>';
	
	}
	
	$Query = $MySQL->prepare("SELECT * FROM `multi_admins` WHERE `user_id`=:one");
	
	$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
	
	$Query->execute();
	
	while($Fetch = $Query->fetch())
	{
	
		if($Fetch['time'] == 0)
		{

			$Data[2] = 'Bez limitu';
			
			$Data[3] = 0;
		
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
			
			$Data[2] = ''.$Days.' dni, '.$Hours.' godzin, '.$Minutes.' minut, '.$Seconds.' sekund'.'';
			
			$Data[3] = '11';
		
		}
	
		switch($Fetch['type'])
		{
		
			case 2:
			
				$Data[0] = 'Multi Admin';
			
			break;
			
			case 3:
			
				$Data[0] = 'Administrator';
			
			break;
		
		}
		
		$Info .= '<tr>
			<td>'.$Fetch['sid'].'</td>
			<td>'.$Data[0].'</td>
			<td>Wszystkie</td>
			<td>'.$Data[2].'</td>
			<td><a href="extension_multi-'.$Fetch['id'].'.html">['.$Data[3].' wPLN]</a></td>
		</tr>';
	
	}
	
	$Query = $MySQL->prepare("SELECT * FROM `guardians` WHERE `user_id`=:one");
	
	$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
	
	$Query->execute();
	
	while($Fetch = $Query->fetch())
	{
		
		$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
		$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
		$QueryTwo->execute();
		
		$FetchTwo = $QueryTwo->fetch();
		
		$Info .= '<tr>
			<td>'.$Fetch['sid'].'</td>
			<td>Opiekun</td>
			<td>'.$FetchTwo['name'].'</td>
			<td>Bez limitu</td>
			<td></td>
		</tr>';
		
	}
	
	$Query = $MySQL->prepare("SELECT * FROM `free_reservation` WHERE `user_id`=:one");
	
	$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
	
	$Query->execute();
	
	while($Fetch = $Query->fetch())
	{
		
		$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
		$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
		$QueryTwo->execute();
		
		$FetchTwo = $QueryTwo->fetch();
		
		$Info .= '<tr>
			<td>'.$Fetch['nick'].'</td>
			<td>Rezerwacja nicku</td>
			<td>'.$FetchTwo['name'].'</td>
			<td>Bez limitu</td>
			<td></td>
		</tr>';
		
	}
	
	$Info .= '</table>';
	
	$View->Load("logged_home");
	$View->Add('title', 'Moje zakupy');
	$View->Add('header', 'Moje zakupy');
	$View->Add('info', $Info);
	$View->Out();

?>