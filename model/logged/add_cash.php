<?php

	$ID = $Core->ClearText($_GET['id']);
	
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

	if(!$ID || $ID == '')
	{
		
		foreach($Price as $Key => $Value)
		{
	
			$Info .= '<a href="add_cash-'.$Value['amount'].'.html"><button class="przycisk">'.$Value['amount'].' wPLN</button></a><br>';
		
		}
		
		$View->Load("logged_home");
		$View->Add('title', 'Doładuj portfel');
		$View->Add('header', 'Wybierz kwotę');
		$View->Add('info', $Info);
		$View->Out();
	
	}
	
	else
	{
	
		foreach($Price as $Key => $Value)
		{
			
			if($ID == $Value['amount'])
			{
				
				$Data = $Value;
			
				break;
			
			}
			
		}
	
		if($_POST['ADD'])
		{
		
			$SMS = $Core->ClearText($_POST['SMS']);
			
			if($Pay->CheckSMS($SMS, $Data['number']))
			{
			
				$Query = $MySQL->prepare("UPDATE `users` SET `money`=`money`+:one WHERE `id`=:two");
				
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
				
				$Query->execute();
				
				$Core->AddCashLogs("Dodano ".$ID." wPLN (".$SMS.")");
			
				$View->Load("info");
				$View->Add('title', 'Doładowano');
				$View->Add('header', 'Doładowano!');
				$View->Add('info', 'Konto zostało doładowane kwotą '.$ID.' wPLN');
				$View->Add('back', 'add_cash-'.$ID.'.html');
				$View->Out();
			
			}
			
			else
			{
			
				$View->Load("info");
				$View->Add('title', 'Błąd :: Błędny kod SMS');
				$View->Add('header', 'Błędny kod SMS!');
				$View->Add('info', 'Podany przez Ciebie kod SMS jest błędny!');
				$View->Add('back', 'add_cash-'.$ID.'.html');
				$View->Out();
			
			}
		
		}
		
		else
		{
			
			$Info = 'Aby doładować konto o <b>'.$Data['amount'].' wPLN</b> wyślij SMS-a o treści <b>'.$Data['text'].'</b> na numer <b>'.$Data['number'].'</b>.<br>
			Otrzymany kod SMS wpisz w pole poniżej.<br>
			Całkowity koszt SMS-a wynosi <b>'.$Data['cost'].' PLN</b>!
		
			<form method="post" action="add_cash-'.$Data['amount'].'.html">
		
				<input type="hidden" name="ADD" value="true">
			
				<br><input type="text" name="SMS" placeholder="Kod SMS"><br>
			
				<br><button type="submit" class="przycisk">Wyślij! <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>
		
			<br><br>
		
			<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&choe=UTF-8&chl=smsto:'.$Data['number'].':'.$Data['text'].'">';
			
			if($ProfitSMS)
			{
				
				$Info .= '<br><br><br>Regulamin serwisu ProfitSMS.pl - <a href="http://profitsms.pl/page/index/5">link</a>.<br>
				Wszelkie reklamacje możesz zgłosić - <a href="http://profitsms.pl/page/kontakt/reklamacje">tutaj</a>.

				<br><br>

				<img src="./view/'.$Styles.'/media/images/profit.png">';
				
			}

			$View->Load("logged_home");
			$View->Add('title', 'Doładuj portfel');
			$View->Add('header', ''.$Data['amount'].' wPLN');
			$View->Add('info', $Info);
			$View->Out();
		
		}
	
	}

?>