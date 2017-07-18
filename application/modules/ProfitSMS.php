<?php

	class ProfitSMS
	{
		
		public function CheckSMS($CodeSMS, $NumberSMS)
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='api_profitsms'");
			
			$Fetch = $Query->fetch();
		
			$Status = $this->QueryAPI('http://profitsms.pl/check.php?apiKey='.$Fetch['value'].'&code='.$CodeSMS.'&smsNr='.$NumberSMS.'', 'r'); 
            
			$Report = explode('|', $Status); 

			switch($Report['0']) 
			{  
				
				case 1: 
				return true;	
				break; 
            
				default:
				return false;
				break;
				
			}
			
			return false;

		}
		
		function QueryAPI($Adress) 
		{
	
			if(in_array('curl', get_loaded_extensions())) 
			{
		
				$CUrl = curl_init($Adress) ;
			
				curl_setopt($CUrl, CURLOPT_URL , $Adress);
				curl_setopt($CUrl, CURLOPT_RETURNTRANSFER, true);
            
				$Query = curl_exec($CUrl);
            
				curl_close($CUrl);
        
			} 
		
			else 
			{
		
				$Query = file_get_contents($Adress);
        
			}
		
			return $Query;     
    
		}
		
		function GetPrice()
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='text_profitsms'");
			
			$Fetch = $Query->fetch();
		
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
					'text' => $Fetch['value'],
					'number'    => $Key[0],
					'cost'     => $Key[1],
					'amount'   => $Key[2]
				);
				
			}
	
			return $Return;
		
		}
		
	}
	
?>