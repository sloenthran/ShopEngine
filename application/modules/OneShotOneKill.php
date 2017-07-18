<?php

	class OneShotOneKill
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
					'text' => 'SHOT',
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
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='api_oneshotonekill'");
			
			$Fetch = $Query->fetch();
		
			$GET = file_get_contents("http://www.1shot1kill.pl/api?type=sms&key=".$Fetch['value']."&sms_code=".$CodeSMS."&comment=Sloenthran.pl [Shop Engine]");
    
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
							
							$CheckMoney = $this->CheckMoney($GET->amount, $NumberSMS);
							
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
			
				case 7136:
				
					if($Money == 0.65)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7255:
				
					if($Money == 1.30)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7355:
				
					if($Money == 1.95)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7455:
				
					if($Money == 2.60)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7555:
				
					if($Money == 3.25)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7636:
				
					if($Money == 3.90)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7936:
				
					if($Money == 5.85)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91455:
				
					if($Money == 9.10)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91955:
				
					if($Money == 12.35)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 92555:
				
					if($Money == 16.25)
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