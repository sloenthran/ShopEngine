<?php

	class Core
	{
	
		function ClearText($Text)
		{
		
			if(get_magic_quotes_gpc())
			{
		
				$Text = stripslashes($Text);
			
			}
		
			$Text = trim($Text);
			$Text = htmlspecialchars($Text);
			$Text = htmlentities($Text);
			$Text = strip_tags($Text);
		
			return $Text;
		
		}
		
		function GetStyles()
		{
		
			$DIR = opendir('./view/');
		
			while($File = readdir($DIR))
			{
				
				if($File == '.' or $File == '..' or $File == 'images')
				{
					
					continue;
				
				}
		
				$Info = pathinfo($File);
			
				$Return[] = $Info['filename'];
				
			}
		
			return $Return;
			
		}
		
		function GetPay()
		{
		
			$DIR = opendir('./application/modules/');
		
			while($File = readdir($DIR))
			{
				
				if($File == '.' or $File == '..' or $File == 'SystemLoader.php')
				{
					
					continue;
				
				}
		
				$Info = pathinfo($File);
			
				$Pay[] = $Info['filename'];
				
			}
		
			return $Pay;
			
		}
		
		function GetMoney()
		{
		
			global $_SESSION, $MySQL;
		
			$Query = $MySQL->prepare("SELECT `money` FROM `users` WHERE `id`=:one");
			
			$Query->bindValue(':one', $_SESSION['ID'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			return $Fetch['money'];
		
		}
		
		function GetName()
		{
		
			global $_SESSION, $MySQL;
		
			$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
			
			$Query->bindValue(':one', $_SESSION['ID'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			return $Fetch['login'];
		
		}
		
		function AddAdminLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `admin_logs` VALUES('', :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddLoginLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `login_logs` VALUES('', :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddBuyLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `buy_logs` VALUES('', :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddGuestBuyLogs($Text)
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='user_gest_buy'");
			$Fetch = $Query->fetch();
			
			$Query = $MySQL->prepare("INSERT INTO `buy_logs` VALUES('', :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $Fetch['value'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddCashLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `cash_logs` VALUES('', :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddOtherLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `other_logs` VALUES('', :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function CheckAdmin()
		{
			
			global $_SESSION, $MySQL;
			
			if($_SESSION['ID_TIME'] + 300 < time())
			{
				
				$Query = $MySQL->prepare("SELECT `ranks` FROM `users` WHERE `id`=:one");
				$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$_SESSION['RANKS'] = $Fetch['ranks'];
			
			}
			
			if($_SESSION['RANKS'] == 1)
			{
				
				return true;
				
			}
			
			else
			{
				
				return false;
				
			}
			
		}
		
		function CheckGuard()
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("SELECT `server` FROM `guardians` WHERE `user_id`=:one LIMIT 1");
			$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->execute();
			
			if($Query->rowCount() > 0)
			{
				
				$Fetch = $Query->fetch();
				
				return $Fetch['server'];
				
			}
			
			else
			{
				
				return 0;
				
			}
			
		}
	
	}
	
?>