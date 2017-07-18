<?php

	class Zabijaka
	{
	
		function GetPrice()
		{
		
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
		
			foreach($Pay as $Key)
			{
				
				$Return[] = array(
					'text' => 'ZABIJ',
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
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='api_zabijaka'");
			
			$Fetch = $Query->fetch();
			
			$Service = $this->DetermineTariff($NumberSMS);
		
			$QueryAPI  = file_get_contents("http://api.zabijaka.pl/1.1/".$Fetch['value']."/sms/".$Service."/".$CodeSMS."/sms.json/add");
			$ReturnAPI = json_decode($QueryAPI);
			
			if($ReturnAPI->{'error'})
			{
			
				return false;
			
			}
			
			else if ($ReturnAPI->{'success'})
			{
			
				return true;
			
			}
			
			return false;
		
		}
		
		function DetermineTariff($NumberSMS)
		{
		
			switch($NumberSMS)
			{
		
				case 7136: return 1;
				case 7255: return 2;
				case 7355: return 3;
				case 7455: return 4;
				case 7555: return 5;
				case 7636: return 6;
				case 7936: return 9;
				case 91455: return 14;
				case 91955: return 19;
				case 92555: return 25;
				
			}
			
		}
	
	}

?>