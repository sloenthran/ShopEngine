<?php

	$ID = $Core->ClearText($_GET['id']);
	$ServerCheck = $Core->ClearText($_GET['nick']);
	
	$Query = $MySQL->prepare("SELECT * FROM `buy` WHERE `id`=:one");
	
	$Query->bindValue(':one', $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$DataBuy = $Fetch;
	
	if($_POST['BUY'])
	{
		
		$Extension = false;
		
		$Nick = $Core->ClearText($_POST['USER']);
		$Pass = $Core->ClearText($_POST['PASS']);
		$SMS = $Core->ClearText($_POST['SMS']);
	
		if(!$Nick || !$Pass)
		{
			
			$View->Load("info");
			$View->Add("title", "Błąd :: Puste pola");
			$View->Add("header", "Błąd! Puste pola!");
			$View->Add("info", "Pola formularza nie mogą być puste!");
			
			if($ServerCheck == 'SERVER')
			{
				
				$View->Add("back", "buy_three-".$ID."-SERVER.html");
				
			}
			
			else
			{
			
				$View->Add("back", "buy_three-".$ID.".html");
				
			}
			
			$View->Out();
			
		}

		else
		{
			
			$BAD = 0;
			
			$Query = $MySQL->prepare("SELECT `id`, `pass`, `premium_id` FROM `premium_cache` WHERE `nick`=:one AND `server`=:two");
			
			$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
			$Query->bindValue(":two", $DataBuy['server'], PDO::PARAM_INT);
			
			$Query->execute();
			
			if($Query->rowCount() > 0)
			{
				
				$Fetch = $Query->fetch();
			
				if($Fetch['pass'] != $Pass)
				{
					
					$BAD++;
			
					$View->Load("info");
					$View->Add('title', 'Błąd :: Błędne hasło');
					$View->Add('header', 'Błędne hasło!');
					$View->Add('info', 'Podane przez Ciebie hasło nie zgadza się z tym zapisanym w bazie danych!');
					
					if($ServerCheck == 'SERVER')
					{
						
						$View->Add('back', 'buy_three-'.$ID.'-SERVER.html');
						
					}
					
					else
					{
					
						$View->Add('back', 'buy_three-'.$ID.'.html');
						
					}
					
					$View->Out();
					
				}
		
				else if($Fetch['premium_id'] == $ID)
				{
			
					$Extension = true;
				
				}
			
			}
			
			if($BAD == 0)
			{
				
				$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
				$Fetch = $Query->fetch();
		
				$Pay = new $Fetch['value']();
				
				$Price = $Pay->GetPrice();
		
				foreach($Price as $Key => $Value)
				{
			
					if($DataBuy['cash'] == $Value['amount'])
					{
				
						$Data = $Value;
			
						break;
			
					}
			
				}
				
				if($Pay->CheckSMS($SMS, $Data['number']))
				{
					
					if($Extension)
					{
						
						$QueryTwo = $MySQL->prepare("SELECT `days` FROM `buy` WHERE `id`=:one");
						$QueryTwo->bindValue(":one", $ID, PDO::PARAM_INT);
						$QueryTwo->execute();
					
						$FetchTwo = $QueryTwo->fetch();
					
						$Days = $FetchTwo['days'] * 86400;
						
						$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`=`time`+:three WHERE `nick`=:one AND `server`=:two");
						
						$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
						$Query->bindValue(":two", $DataBuy['server'], PDO::PARAM_INT);
						$Query->bindValue(":three", $Days, PDO::PARAM_INT);
			
						$Query->execute();
						
						$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					
						$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
					
						$Query->execute();
					
						$Fetch = $Query->fetch();
						
						$Core->AddGuestBuyLogs("[GUEST] Przedłużono premium na nick <b>".$Nick."</b> na serwerze <b>".$Fetch['name']."</b> kodem SMS <b>".$SMS."</b>");
				
						$View->Load("info");
						$View->Add('title', 'Zakup przedłużony');
						$View->Add('header', 'Twój zakup został przedłużony!');
						$View->Add('info', 'Twój zakup został poprawnie przedłużony.');
						$View->Add('back', 'home.html');
						$View->Out();
						
					}
					
					else
					{
						
						$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='user_gest_buy'");
						$Fetch = $Query->fetch();
				
						$Buy = new Buy();
						
						$QueryTwo = $MySQL->prepare("SELECT `days` FROM `buy` WHERE `id`=:one");
						$QueryTwo->bindValue(":one", $ID, PDO::PARAM_INT);
						$QueryTwo->execute();
					
						$FetchTwo = $QueryTwo->fetch();
					
						$Days = $FetchTwo['days'];
					
						$Buy->AddBuy($Nick, $Pass, $ID, $Fetch['value'], $Days);
					
						$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					
						$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
					
						$Query->execute();
					
						$Fetch = $Query->fetch();
					
						$Core->AddGuestBuyLogs("[GUEST] Kupiono premium na nick <b>".$Nick."</b> na serwerze <b>".$Fetch['name']."</b> kodem SMS <b>".$SMS."</b>");
				
						$View->Load("info");
						$View->Add('title', 'Zakup dodany');
						$View->Add('header', 'Twój zakup został dodany!');
						$View->Add('info', 'Twój zakup został poprawnie dodany. Na serwerze będzie aktywny po około minucie!<br>Nie zapomnij o wpisaniu w konsoli<br><b>setinfo _pw "'.$Pass.'"</b>');
						$View->Add('back', 'home.html');
						$View->Out();
						
					}
					
				}
				
				else
				{
					
					$View->Load("info");
					$View->Add('title', 'Błąd :: Błędny kod SMS');
					$View->Add('header', 'Błędny kod SMS!');
					$View->Add('info', 'Podany przez Ciebie kod SMS jest błędny!');
					
					if($ServerCheck == 'SERVER')
					{
					
						$View->Add('back', 'buy_three-'.$ID.'-SERVER.html');
						
					}
					
					else
					{
						
						$View->Add('back', 'buy_three-'.$ID.'.html');
						
					}
					
					$View->Out();
					
				}
		
			}
	
		}
		
	}

	else
	{
		
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
		$Fetch = $Query->fetch();
		
		if($Fetch['value'] == 'ProfitSMS')
		{
		
			$ProfitSMS = true;
		
		}
	
		else
		{
		
			$ProfitSMS = false;
		
		}
		
		$Pay = new $Fetch['value']();
	
		$Price = $Pay->GetPrice();
		
		foreach($Price as $Key => $Value)
		{
			
			if($DataBuy['cash'] == $Value['amount'])
			{
				
				$Data = $Value;
			
				break;
			
			}
			
		}
		
		if($ServerCheck == 'SERVER')
		{
			
			$Info = 'Aby sfinalizować zakup wyślij SMS-a o treści <b>'.$Data['text'].'</b> na numer <b>'.$Data['number'].'</b>.<br>
			Otrzymany kod SMS wpisz w pole poniżej.<br>
			Całkowity koszt SMS-a wynosi <b>'.$Data['cost'].' PLN</b>!
		
			<form method="post" action="buy_three-'.$ID.'-SERVER.html">
		
				<input type="hidden" name="BUY" value="true">
			
				<br>Nick lub SID<br><input type="text" name="USER" required><br>
				<br>Hasło<br><input type="text" name="PASS" required><br>
				<br>Kod SMS<br><input type="text" name="SMS" required><br>
			
				<br><input type="submit" class="przycisk" value="Kupuję!">
		
			</form>
	
			<br><br>
		
			<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&choe=UTF-8&chl=smsto:'.$Data['number'].':'.$Data['text'].'">';
			
		}
		
		else
		{
		
			$Info = 'Aby sfinalizować zakup wyślij SMS-a o treści <b>'.$Data['text'].'</b> na numer <b>'.$Data['number'].'</b>.<br>
			Otrzymany kod SMS wpisz w pole poniżej.<br>
			Całkowity koszt SMS-a wynosi <b>'.$Data['cost'].' PLN</b>!
		
			<form method="post" action="buy_three-'.$ID.'.html">
		
				<input type="hidden" name="BUY" value="true">
			
				<br><input type="text" name="USER" placeholder="Nick lub SID" required><br>
				<br><input type="text" name="PASS" placeholder="Hasło" required><br>
				<br><input type="text" name="SMS" placeholder="Kod SMS" required><br>
			
				<br><button type="submit" class="przycisk">Kupuję! <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>
	
			<br><br>
		
			<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&choe=UTF-8&chl=smsto:'.$Data['number'].':'.$Data['text'].'">';
			
		}
	
		if($ProfitSMS)
		{
		
			$Info .= '<br><br><br>Regulamin serwisu ProfitSMS.pl - <a href="http://profitsms.pl/page/index/5">link</a>.<br>
			Wszelkie reklamacje możesz zgłosić - <a href="http://profitsms.pl/page/kontakt/reklamacje">tutaj</a>.

			<br><br>

			<img src="./view/images/profit.png">';
			
		}
	
		$View->Load("info");
		$View->Add("title", "Zakup :: ".$DataBuy['name']."");
		$View->Add("header", "".$DataBuy['name']." [".$DataBuy['cash']." PLN]");
		$View->Add("info", $Info);
		
		if($ServerCheck == 'SERVER')
		{
		
			$View->Add('back', 'buy_two-'.$ID.'-SERVER.html');
			
		}
		
		else
		{
			
			$View->Add('back', 'buy_two-'.$ID.'.html');
			
		}
		
		$View->Out();
	
	}

?>