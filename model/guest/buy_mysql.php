<?php

	$ID = $Core->ClearText($_GET['id']);
	$ServerCheck = $Core->ClearText($_GET['nick']);
	
	$Query = $MySQL->prepare("SELECT * FROM `mysql_buy` WHERE `id`=:one");
	
	$Query->bindValue(':one', $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$DataBuy = $Fetch;
	
	if($_POST['BUY'])
	{
		
		$Nick = $Core->ClearText($_POST['USER']);
		$SMS = $Core->ClearText($_POST['SMS']);
		
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
			
			$Query = $MySQL->prepare("INSERT INTO `server_cash` VALUES('', :one, :two, CURRENT_TIMESTAMP)");
			
			$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
			$Query->bindValue(":two", $DataBuy['cash'], PDO::PARAM_INT);
			
			$Query->execute();
	
			$Query = $MySQL->prepare("SELECT * FROM `servers` WHERE `id`=:one");
	
			$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
		
			$Query->execute();
		
			$Fetch = $Query->fetch();
		
			$Core->AddGuestBuyLogs("[GUEST] Kupiono ".$DataBuy['name']." na nick <b>".$Nick."</b> na serwerze <b>".$Fetch['name']."</b>  kodem SMS <b>".$SMS."</b>");
			
			if($DataBuy['rcon_kick'] == 1)
			{
				
				$RCON = new HalfLifeRcon();
				
				$RCON->Connect($Fetch['ip'], $Fetch['port'], $Fetch['rcon_pass']);
				
				if($RCON->IsConnected())
				{
				
					$RCON->RconCommand('kick "'.$Nick.'" "Trwa dodawanie XP. Odczekaj 5 sekund i wejdz na serwer! :)"');
					
				}
				
			}
			
			sleep(2);
			
			$OtherMySQL = new PDO('mysql:host='.$DataBuy['host'].'; dbname='.$DataBuy['base'].'; charset=utf8;',  $DataBuy['user'],  $DataBuy['pass']);
			
			$Query = $OtherMySQL->prepare($DataBuy['command']);
			$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
			$Query->execute();
			
			$View->Load("info");
			$View->Add('title', 'Zakup dodany');
			$View->Add('header', 'Twój zakup został dodany!');
			$View->Add('info', 'Twój zakup został poprawnie dodany!');
			$View->Add('back', 'home.html');
			$View->Out();
		
		}
	
		else
		{
		
			$View->Load("info");
			$View->Add('title', 'Błąd :: Błędny kod SMS');
			$View->Add('header', 'Błędny kod SMS!');
			$View->Add('info', 'Podany przez Ciebie kod SMS jest błędny!');
		
			if($ServerCheck == 'SERVER')
			{
		
				$View->Add('back', 'buy_mysql-'.$ID.'-SERVER.html');
				
			}
	
			else
			{
			
				$View->Add('back', 'buy_mysql-'.$ID.'.html');
			
			}
		
			$View->Out();
	
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
		
			<form method="post" action="buy_mysql-'.$ID.'-SERVER.html">
		
				<input type="hidden" name="BUY" value="true">
			
				<br>Nick<br><input type="text" name="USER" required><br>
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
		
			<form method="post" action="buy_mysql-'.$ID.'.html">
		
				<input type="hidden" name="BUY" value="true">
			
				<br><input type="text" name="USER" placeholder="Nick" required><br>
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
		
			$View->Add('back', 'buy-'.$DataBuy['server'].'-SERVER.html');
			
		}
		
		else
		{
			
			$View->Add('back', 'buy-'.$DataBuy['server'].'.html');
			
		}
		
		$View->Out();
	
	}

?>