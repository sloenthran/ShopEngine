<?php

	class SimPay
	{
		
		function GetPrice()
		{
			
			global $MySQL;
		
			$Pay = array(
				array(7136, 1.23, 1),
				array(7255, 2.46, 2),
				array(7355, 3.69, 3),
				array(7455, 4.92, 4),
				array(7555, 6.15, 6),
				array(7636, 7.38, 7),
				array(7936, 11.07, 11),
				array(91455, 17.22, 17),
				array(91955, 23.37, 23),
				array(92555, 30.75, 30)
			);
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='text_simpay'");
			
			$Fetch = $Query->fetch();
		
			foreach($Pay as $Key)
			{
				
				$Return[] = array(
					'text' => $Fetch['value'],
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
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='key_simpay'");
			
			$Fetch = $Query->fetch();
			
			$Key = $Fetch['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pass_simpay'");
			
			$Fetch = $Query->fetch();
			
			$Pass = $Fetch['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='service_simpay'");
			
			$Fetch = $Query->fetch();
			
			$Service = $Fetch['value'];
			
			$Options = array(
				CURLOPT_URL => 'https://simpay.pl/api/1/status',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_HEADER  => false
			);
			
			$Data = array(
				'auth' => array(
					'key' => $Key,
					'secret' => $Pass
				),
				'service_id' => $Service,
				'number' => $NumberSMS,
				'code' => $CodeSMS
			);
			
			$cURL = curl_init();
			
			curl_setopt_array($cURL, $Options);
			
			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode(array('params' => $Data)));
			
			$Query = curl_exec($cURL);
			
			curl_close($cURL);
			
			$Response = json_decode($Query, true);
			
			if(isset($Response['respond']['status']) && $Response['respond']['status'] == 'OK')
			{
				
				return true;
				
			}
			
			return false;
			
		}
		
	}

?>