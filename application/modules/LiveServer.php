<?php

	class LiveServer
	{
	
		function GetPrice()
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='id_liveserver'");
			
			$Fetch = $Query->fetch();
			
			$Text = 'TC.LIVS.'.$Fetch['value'].'.SLOESHOP';
		
			$Pay = array(
				array(71068, 1.23, 1),
				array(72068, 2.46, 2),
				array(73068, 3.69, 3),
				array(74068, 4.92, 4),
				array(75068, 6.15, 6),
				array(76068, 7.38, 7),
				array(79068, 11.07, 11),
				array(91958, 23.37, 23),
				array(92578, 30.75, 30)
			);
		
			foreach($Pay as $Key)
			{
				
				$Return[] = array(
					'text' => $Text,
					'number'    => $Key[0],
					'cost'     => $Key[1],
					'amount'   => $Key[2]
				);
				
			}
	
			return $Return;
		
		}
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
		
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='id_liveserver'");
			
			$ID = $Query->fetch();
			$ID = $ID['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pin_liveserver'");
			
			$Key = $Query->fetch();
			$Key = $Key['value'];
			
			$Query = $this->QueryAPI($ID, $Key, $CodeSMS);
			
			if($Query[0] == 'status=OK')
			{
				
				if($Query[4] == 'number='.$NumberSMS.'')
				{
					
					if($Query[7] == 0)
					{
						
						return true;
						
					}
					
					else
					{
						
						return false;
						
					}
					
				}
				
				else
				{
					
					return false;
					
				}
				
			}
			
			else
			{
				
				return false;
				
			}
		
		}
		
		function QueryAPI($ID, $Key, $CodeSMS)
		{
			
			$cURL = curl_init();
			
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			
			curl_setopt($cURL, CURLOPT_POSTFIELDS, array('client_id' => $ID, 'pin' => $Key, 'code' => $CodeSMS));
			curl_setopt($cURL, CURLOPT_URL, "http://rec.liveserver.pl/api.php?channel=sms&return_method=http");
			
			$Return = curl_exec($cURL);
			
			curl_close($cURL);
			
			$Return = explode('&', $Return);
			
			return $Return;
		
		}
		
	}

?>