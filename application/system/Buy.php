<?php

	class Buy
	{
	
		function AddBuy($Nick, $Pass, $PremiumID, $ShopID, $Days, $AddCash = true)
		{
		
			global $MySQL;
			
			$Query = $MySQL->prepare("SELECT * FROM `buy` WHERE `id`=:one");
			
			$Query->bindValue(":one", $PremiumID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$BuyData = $Fetch;
			
			if($AddCash)
			{
			
				$Query = $MySQL->prepare("INSERT INTO `server_cash` VALUES('', :one, :two, CURRENT_TIMESTAMP)");
			
				$Query->bindValue(":one", $BuyData['server'], PDO::PARAM_INT);
				$Query->bindValue(":two", $BuyData['cash'], PDO::PARAM_INT);
			
				$Query->execute();
				
			}
			
			$Query = $MySQL->prepare("SELECT * FROM `premium` WHERE `nick`=:one AND `server`=:two");
			
			$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
			$Query->bindValue(":two", $BuyData['server'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Days = $Days * 86400;
			
			$Time = time() + $Days;
			
			if($Query->rowCount() > 0)
			{
			
				$Fetch = $Query->fetch();
			
				$Query = $MySQL->prepare("INSERT INTO `premium_cache` VALUES('', :one, :two, :three, :four, :five, :six, :seven)");
				
				$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['flags'], PDO::PARAM_STR);
				$Query->bindValue(":four", $BuyData['server'], PDO::PARAM_INT);
				$Query->bindValue(":five", $Time, PDO::PARAM_INT);
				$Query->bindValue(":six", $ShopID, PDO::PARAM_INT);
				$Query->bindValue(":seven", $PremiumID, PDO::PARAM_INT);
				
				$Query->execute();
			
				$NewFlags = $this->SumFlags($Fetch['flags'], $BuyData['flags']);
				
				$Query = $MySQL->prepare("UPDATE `premium` SET `flags`=:one WHERE `nick`=:two AND `server`=:three");
				
				$Query->bindValue(":one", $NewFlags, PDO::PARAM_STR);
				$Query->bindValue(":two", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['server'], PDO::PARAM_INT);
				
				$Query->execute();		
			
			}
			
			else
			{
				
				$Query = $MySQL->prepare("INSERT INTO `premium` VALUES('', :one, :two, :three, :four)");
				
				$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['flags'], PDO::PARAM_STR);
				$Query->bindValue(":four", $BuyData['server'], PDO::PARAM_INT);
				
				$Query->execute();
				
				$Query = $MySQL->prepare("INSERT INTO `premium_cache` VALUES('', :one, :two, :three, :four, :five, :six, :seven)");
				
				$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":two", $Pass, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['flags'], PDO::PARAM_STR);
				$Query->bindValue(":four", $BuyData['server'], PDO::PARAM_INT);
				$Query->bindValue(":five", $Time, PDO::PARAM_INT);
				$Query->bindValue(":six", $ShopID, PDO::PARAM_INT);
				$Query->bindValue(":seven", $PremiumID, PDO::PARAM_INT);
				
				$Query->execute();
			
			}
		
		}
	
		function SumFlags($FlagsA, $FlagsB)
		{
		
			for($Number = 0; $Number < strlen($FlagsB); $Number++) 
			{
				if(!(strlen(strstr($FlagsA, $FlagsB[$Number]))))
				{
				
					$Out .= $FlagsB[$Number];
					
				}
				
			}
			
			$Text = str_split($FlagsA . $Out);
			
			sort($Text);
			
			return implode('', $Text);
		
		}
	
	}

?>