<?php

	class File
	{
    
		function GenerateServers($ID)
		{
		
			global $MySQL;
    
			$Query = $MySQL->prepare("SELECT * FROM `servers` WHERE `id`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$DataFTP = $Fetch;
      
			$File = fopen('./cache/'.$ID.'.ini', 'w');
      
			flock($File, LOCK_EX);
      
			$Table[] = ';;; Administratorzy' . "\r\n";
			$Table[] = "\r\n";
			
			$Query = $MySQL->query("SELECT * FROM `multi_admins` WHERE `type`='3'");
			
			while($Fetch = $Query->fetch())
			{
				
				$QueryNick = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryNick->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$QueryNick->execute();
				
				$FetchNick = $QueryNick->fetch();
				
				$Table[] = '; '.$FetchNick['login'].'' . "\r\n";

				if(preg_match("/STEAM_/i", $Fetch['sid']))
				{
					
					if($Fetch['pass'] == '')
					{
						
						$Table[] = '"'.$Fetch['sid'].'" "" "abcdefghijklmnopqrstuvwx" "ce"' . "\r\n";
						
					}
					
					else
					{
			
						$Table[] = '"'.$Fetch['sid'].'" "'.$Fetch['pass'].'" "abcdefghijklmnopqrstuvwx" "ac"' . "\r\n";
						
					}
					
				}
				
				else
				{
					
					if($Fetch['pass'] == '')
					{
						
						$Table[] = '"'.$Fetch['sid'].'" "" "abcdefghijklmnopqrstuvwx" "c"' . "\r\n";
						
					}
					
					else
					{
				
						$Table[] = '"'.$Fetch['sid'].'" "'.$Fetch['pass'].'" "abcdefghijklmnopqrstuvwx" "a"' . "\r\n";
						
					}
				
				}
			
			}
			
			$Table[] = "\r\n";
			$Table[] = ';;; Opiekuni' . "\r\n";
			$Table[] = "\r\n";
			
			$Query = $MySQL->prepare("SELECT * FROM `guardians` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			while($Fetch = $Query->fetch())
			{
				
				$QueryNick = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryNick->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$QueryNick->execute();
				
				$FetchNick = $QueryNick->fetch();
				
				$Table[] = '; '.$FetchNick['login'].'' . "\r\n";
			
				if(preg_match("/STEAM_/i", $Fetch['sid']))
				{
					
					if($Fetch['pass'] == '')
					{
						
						$Table[] = '"'.$Fetch['sid'].'" "" "'.$Fetch['flags'].'" "ce"' . "\r\n";
						
					}
					
					else
					{
			
						$Table[] = '"'.$Fetch['sid'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'" "ac"' . "\r\n";
						
					}
					
				}
				
				else
				{
				
					if($Fetch['pass'] == '')
					{
						
						$Table[] = '"'.$Fetch['sid'].'" "" "'.$Fetch['flags'].'" "c"' . "\r\n";
						
					}
					
					else
					{
				
						$Table[] = '"'.$Fetch['sid'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'" "a"' . "\r\n";
						
					}
				
				}
			
			}
			
			$Table[] = "\r\n";
			$Table[] = ';;; Multi Admini' . "\r\n";
			$Table[] = "\r\n";
			
			$Query = $MySQL->query("SELECT * FROM `multi_admins` WHERE `type`='2'");
			
			while($Fetch = $Query->fetch())
			{
				
				$QueryNick = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryNick->bindValue(":one", $Fetch['user_id'], PDO::PARAM_INT);
				$QueryNick->execute();
				
				$FetchNick = $QueryNick->fetch();
				
				$Table[] = '; '.$FetchNick['login'].'' . "\r\n";
			
				if(preg_match("/STEAM_/i", $Fetch['sid']))
				{
					
					if($Fetch['pass'] == '')
					{
						
						$Table[] = '"'.$Fetch['sid'].'" "" "bcdefijumntsx" "ce"' . "\r\n";
						
					}
					
					else
					{
			
						$Table[] = '"'.$Fetch['sid'].'" "'.$Fetch['pass'].'" "bcdefijumntsx" "ac"' . "\r\n";
						
					}
					
				}
				
				else
				{
					
					if($Fetch['pass'] == '')
					{
						
						$Table[] = '"'.$Fetch['sid'].'" "" "bcdefijumntsx" "e"' . "\r\n";
						
					}
					
					else
					{
					
						$Table[] = '"'.$Fetch['sid'].'" "'.$Fetch['pass'].'" "bcdefijumntsx" "a"' . "\r\n";
						
					}
				
				}
			
			}
      
			$Query = $MySQL->prepare("SELECT * FROM `premium` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
      
			while($Fetch = $Query->fetch())
			{
				
				$PrepareQuery = $MySQL->prepare("SELECT `user_id` FROM `premium_cache` WHERE `server`=:one AND `nick`=:two LIMIT 1");
				$PrepareQuery->bindValue(":one", $ID, PDO::PARAM_INT);
				$PrepareQuery->bindValue(":two", $Fetch['nick'], PDO::PARAM_STR);
				$PrepareQuery->execute();
				
				$FetchPrepare = $PrepareQuery->fetch();
				
				$QueryNick = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
				$QueryNick->bindValue(":one", $FetchPrepare['user_id'], PDO::PARAM_INT);
				$QueryNick->execute();
				
				$FetchNick = $QueryNick->fetch();
				
				
				if(preg_match("/z/i", $Fetch['flags']))
				{
					
					$TableNick[] = '; '.$FetchNick['login'].'' . "\r\n";
					
					if(preg_match("/STEAM_/i", $Fetch['sid']))
					{
						
						if($Fetch['pass'] == '')
						{
          
							$TableNick[] = '"'.$Fetch['nick'].'" "" "'.$Fetch['flags'].'x" "ce"' . "\r\n";
							
						}
						
						else
						{
							
							$TableNick[] = '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'x" "ac"' . "\r\n";
							
						}

					}
        
					else
					{
						
						if($Fetch['pass'] == '')
						{
          
							$TableNick[] = '"'.$Fetch['nick'].'" "" "'.$Fetch['flags'].'x" "e"' . "\r\n";
							
						}
						
						else
						{
							
							$TableNick[] = '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'x" "a"' . "\r\n";
							
						}
          
					}
					
				}
				
				else
				{
			
					if(preg_match("/d/i", $Fetch['flags']))
					{
						
						$TableAdmin[] = '; '.$FetchNick['login'].'' . "\r\n";
				
						if(preg_match("/STEAM_/i", $Fetch['nick']))
						{
							
							if($Fetch['pass'] == '')
							{
								
								$TableAdmin[] = '"'.$Fetch['nick'].'" "" "'.$Fetch['flags'].'x" "ce"' . "\r\n";
								
							}
							
							else
							{
								
								$TableAdmin[] = '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'x" "ac"' . "\r\n";
								
							}
						
						}
        
						else
						{
							
							if($Fetch['pass'] == '')
							{
								
								$TableAdmin[] = '"'.$Fetch['nick'].'" "" "'.$Fetch['flags'].'x" "e"' . "\r\n";
								
							}
							
							else
							{
								
								$TableAdmin[] = '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'x" "a"' . "\r\n";
								
							}
							
						}
				
					}
				
					else
					{
						
						$TablePremium[] = '; '.$FetchNick['login'].'' . "\r\n";
					
						$Fetch['flags'] = str_replace('z', '', $Fetch['flags']);
				
						if(preg_match("/STEAM_/i", $Fetch['nick']))
						{
							
							if($Fetch['pass'] == '')
							{
								
								$TablePremium[] = '"'.$Fetch['nick'].'" "" "'.$Fetch['flags'].'xz" "ce"' . "\r\n";
								
							}
							
							else
							{
								
								$TablePremium[] = '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'xz" "ac"' . "\r\n";
								
							}
							
						}
        
						else
						{
							
							if($Fetch['pass'] == '')
							{
								
								$TablePremium[] = '"'.$Fetch['nick'].'" "" "'.$Fetch['flags'].'xz" "e"' . "\r\n";
								
							}
							
							else
							{
								
								$TablePremium[] = '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "'.$Fetch['flags'].'xz" "a"' . "\r\n";
								
							}
          
						}
				
					}
        
				}
        
			}
      
			for($Number = 0; $Number < count($Table); $Number++)
			{
      
				fwrite($File, $Table[$Number]);
        
			}
			
			fwrite($File, "\r\n");
			fwrite($File, ';;; Admini' . "\r\n");
			fwrite($File, "\r\n");
			
			for($Number = 0; $Number < count($TableAdmin); $Number++)
			{
      
				fwrite($File, $TableAdmin[$Number]);
        
			}
			
			fwrite($File, "\r\n");
			fwrite($File, ';;; Premki' . "\r\n");
			fwrite($File, "\r\n");
			
			for($Number = 0; $Number < count($TablePremium); $Number++)
			{
      
				fwrite($File, $TablePremium[$Number]);
        
			}
			
			$TableNick[] = "\r\n";
			$TableNick[] = ';;; Rezerwacje nicku' . "\r\n";
			$TableNick[] = "\r\n";
			
			for($Number = 0; $Number < count($TableNick); $Number++)
			{
      
				fwrite($File, $TableNick[$Number]);
        
			}
			
			$Query = $MySQL->prepare("SELECT * FROM `free_reservation` WHERE `server`=:one");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			
			$Query->execute();
			
			while($Fetch = $Query->fetch())
			{
				
				fwrite($File, '"'.$Fetch['nick'].'" "'.$Fetch['pass'].'" "xz" "a"' . "\r\n");
				
			}

			fwrite($File, "\r\n");
			fwrite($File, "\r\n");
			fwrite($File, "\r\n");
			fwrite($File, ";;; Ostatnia aktualizacja pliku odbyła się o: ".date("d.m.Y H:i:s")."");
      
			flock($File, LOCK_UN);
      
			fclose($File);
    
			$FTP = new FTP();
      
			$FTP->Upload($DataFTP['host'], $DataFTP['user'], $DataFTP['pass'], $ID, $DataFTP['path']);
    
		}
		
		function UpdatePassBans($Host, $User, $Pass, $Base)
		{
			
			global $MySQL;
			
			$FTP = new FTP();
			
			$Query = $MySQL->query("SELECT * FROM `servers`");
			
			while($Fetch = $Query->fetch())
			{
			
				$File = fopen('./cache/'.$Fetch['id'].'.cfg', 'w');
      
				flock($File, LOCK_EX);
				
				fwrite($File, 'amx_sql_host "'.$Host.'"' . "\r\n");
				fwrite($File, 'amx_sql_user "'.$User.'"' . "\r\n");
				fwrite($File, 'amx_sql_pass "'.$Pass.'"' . "\r\n");
				fwrite($File, 'amx_sql_db "'.$Base.'"' . "\r\n");
				fwrite($File, 'amx_sql_table "admins"' . "\r\n");
				fwrite($File, 'amx_sql_type "mysql"' . "\r\n");
				
				flock($File, LOCK_UN);
      
				fclose($File);
			
				$FTP->UpdatePassBans($Fetch['host'], $Fetch['user'], $Fetch['pass'], $Fetch['id'], $Fetch['path']);
				
				unset($File);
				
			}
			
		}
  
	}

?>