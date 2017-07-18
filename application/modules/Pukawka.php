<?php

	class Pukawka
	{
	
		function GetPrice()
		{
		
			$Pay = array(
				array(71480, 1.23, 1),
				array(72480, 2.46, 2),
				array(73480, 3.69, 3),
				array(74480, 4.92, 4),
				array(75480, 6.15, 6),
				array(76480, 7.38, 7),
				array(79480, 11.07, 11),
				array(91400, 17.22, 17),
				array(91900, 23.37, 23),
				array(92550, 30.75, 30)
			);
		
			foreach($Pay as $Key)
			{
				
				$Return[] = array(
					'text' => 'pukawka',
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
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='api_pukawka'");
			
			$Fetch = $Query->fetch();
		
			$GET = file_get_contents("https://admin.pukawka.pl/api/?keyapi=".$Fetch['value']."&type=sms&code=".$CodeSMS."");
    
			if($GET)
			{
				
				$GET = json_decode($GET);
	
				if(is_object($GET))
				{
					
					if($GET->error)
					{
						
						return false;
					
					}
					
					else
					{
						
						$Status = $GET->status;
		
						if($Status == "ok")
						{
							
							$CheckMoney = $this->CheckMoney($GET->kwota, $NumberSMS);
							
							if($CheckMoney)
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
		
		function CheckMoney($Money, $NumberSMS)
		{
		
			switch($NumberSMS)
			{
			
				case 71480:
				
					if($Money == 0.65)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 72480:
				
					if($Money == 1.30)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 73480:
				
					if($Money == 1.96)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 74480:
				
					if($Money == 2.61)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 75480:
				
					if($Money == 3.26)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 76480:
				
					if($Money == 3.91)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 79480:
				
					if($Money == 5.87)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91400:
				
					if($Money == 9.13)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91900:
				
					if($Money == 12.39)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 92550:
				
					if($Money == 16.30)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
			
			}
		
		}
	
	}

?>