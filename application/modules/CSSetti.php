<?php

	class CSSetti
	{
	
		function GetPrice()
		{
			
			$Pay = array(
				array(71624, 1.23, 1),
				array(72624, 2.46, 2),
				array(73624, 3.69, 3),
				array(74624, 4.92, 4),
				array(75624, 6.15, 6),
				array(76624, 7.38, 7),
				array(79624, 11.07, 11),
				array(91974, 23.37, 23),
				array(92574, 30.75, 30)
			);
		
			foreach($Pay as $Key)
			{
				
				$Return[] = array(
					'text' => 'DP CSSETTI',
					'number'    => $Key[0],
					'cost'     => $Key[1],
					'amount'   => $Key[2]
				);
				
			}
	
			return $Return;
		
		}
		
		function QueryAPI($SMS, $Key)
		{
			
			$Query = file_get_contents(sprintf('http://cssetti.pl/Api/SmsApiV2CheckCode.php?UserId=%d&Code=%s', $Key, $SMS));
			
			if($Query > 0)
			{
				
				return true;
				
			}
			
			return false;
		
		}
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
		
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='api_cssetti'");
			
			$Fetch = $Query->fetch();
		
			if($Query = $this->QueryAPI($CodeSMS, $Fetch['value']))
			{
				
				return true;
				
			}
			
			return false;
		
		}
	
	}

?>