<?php

	$Buy = new Buy();
	$SMS = new JustSend();
	
	$SendAlarm = false;
	
	$Date = time();
	
	$CountSMS = $SMS->GetCountSMS();

	$Query = $MySQL->query("SELECT * FROM `premium_cache`");
	
	while($Fetch = $Query->fetch())
	{
		
		if($Fetch['time'] < $Date + 86400)
		{
			
			if($CountSMS > 1)
			{
			
				$CheckSMS = $MySQL->prepare("SELECT `telephone`, `sms_notification` FROM `users` WHERE `id`=:one");
			
				$CheckSMS->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
			
				$CheckSMS->execute();
			
				$FetchSMS = $CheckSMS->fetch();
			
				if($FetchSMS['sms_notification'] == 1)
				{
				
					$CheckDoubleSMS = $MySQL->prepare("SELECT * FROM `send_sms` WHERE `premium_id`=:one");
				
					$CheckDoubleSMS->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
				
					$CheckDoubleSMS->execute();
				
					if($CheckDoubleSMS->rowCount() == 0)
					{
						
						$ServerName = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
						$ServerName->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
						$ServerName->execute();
		
						$Server = $ServerName->fetch();
						
						if($Fetch['premium_id'] == 0)
						{
				
							$SMS->SendMessage("Waznosc Twojego admina na serwerze ".$Server['name']." wynosi okolo 24h! Prosze o przedluzenie!", $FetchSMS['telephone']);
							
						}
						
						else
						{
							
							$PremiumName = $MySQL->prepare("SELECT `name` FROM `buy` WHERE `id`=:one");
		
							$PremiumName->bindValue(":one", $Fetch['premium_id'], PDO::PARAM_INT);
		
							$PremiumName->execute();
		
							$Premium = $PremiumName->fetch();
							
							$SMS->SendMessage("Waznosc Twojego zakupu ".$Fetch['nick']." (".$Premium['name'].") na serwerze ".$Server['name']." wynosi okolo 24h! Prosze o przedluzenie!", $FetchSMS['telephone']);
							
						}
						
						$AntiDouble = $MySQL->prepare("INSERT INTO `send_sms` VALUES('', :one, :two)");
						
						$AntiDouble->bindValue(":one", $Date, PDO::PARAM_INT);
						$AntiDouble->bindValue(":two", $Fetch['id'], PDO::PARAM_INT);
						
						$AntiDouble->execute();
						
					}
					
					$CountSMS -= 1;
				
				}
				
			}
			
			else if($CountSMS != 0 && !$SendAlarm)
			{
				
				$SMS->SendAlarm();
				
				$SendAlarm = true;
				
			}
			
		}
	
		if($Date >= $Fetch['time'])
		{
		
			$QueryTwo = $MySQL->prepare("DELETE FROM `premium_cache` WHERE `id`=:one");
			
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			
			$QueryTwo->execute();
		
			$QueryTwo = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `nick`=:one AND `server`=:two");
			
			$QueryTwo->bindValue(":one", $Fetch['nick'], PDO::PARAM_STR);
			$QueryTwo->bindValue(":two", $Fetch['server'], PDO::PARAM_INT);
			
			$QueryTwo->execute();
			
			if($QueryTwo->rowCount() > 0)
			{
			
				while($FetchTwo = $QueryTwo->fetch())
				{
				
					$Flags = $Buy->SumFlags($Flags, $FetchTwo['flags']);
				
				}
			
				$QueryThree = $MySQL->prepare("UPDATE `premium` SET `flags`=:one WHERE `nick`=:two AND `server`=:three");
				
				$QueryThree->bindValue(":one", $Flags, PDO::PARAM_STR);
				$QueryThree->bindValue(":two", $Fetch['nick'], PDO::PARAM_STR);
				$QueryThree->bindValue(":three", $Fetch['server'], PDO::PARAM_INT);
				
				$QueryThree->execute();		
			
			}
			
			else
			{
			
				$QueryThree = $MySQL->prepare("DELETE FROM `premium` WHERE `nick`=:one AND `server`=:two");
				
				$QueryThree->bindValue(":one", $Fetch['nick'], PDO::PARAM_STR);
				$QueryThree->bindValue(":two", $Fetch['server'], PDO::PARAM_INT);
				
				$QueryThree->execute();
			
			}
		
		}
	
	}
	
	$Query = $MySQL->query("SELECT `id`, `time` FROM `multi_admins`");
	
	while($Fetch = $Query->fetch())
	{
	
		if($Date >= $Fetch['time'])
		{
		
			if($Fetch['time'] != 0)
			{
			
				$QueryTwo = $MySQL->prepare("DELETE FROM `multi_admins` WHERE `id`=:one");
				
				$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
				
				$QueryTwo->execute();
			
			}
		
		}
	
	}
	
	$File = new File();
	
	$Query = $MySQL->query("SELECT `id` FROM `servers`");
	
	while($Fetch = $Query->fetch())
	{
		
		$File->GenerateServers($Fetch['id']);
		
	}
	
	$Query = $MySQL->query("SELECT `id`,`time` FROM `send_sms`");
	
	while($Fetch = $Query->fetch())
	{
	
		if($Fetch['time'] < $Time)
		{
		
			$QueryTwo = $MySQL->prepare("DELETE FROM `send_sms` WHERE `id`=:one");
			
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			
			$QueryTwo->execute();
		
		}
	
	}
	
	$View->Load('info');
	$View->Add('title', 'Serwery przeładowane');
	$View->Add('header', 'Serwery przeładowane!');
	$View->Add('info', 'Serwery zostały poprawnie przeładowane!');
	$View->Add('back', 'home.html');
	$View->Out();

?>