<?php

	$ID = $Core->ClearText($_GET['id']);
	
	$Query = $MySQL->prepare("SELECT * FROM `mysql_buy` WHERE `id`=:one");
	
	$Query->bindValue(':one', $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$DataBuy = $Fetch;
	
	if($Core->GetMoney() >= $DataBuy['cash'])
	{
		
		if($_POST['BUY'])
		{
		
			$Nick = $Core->ClearText($_POST['USER']);
			
			$Query = $MySQL->prepare("UPDATE `users` SET `money`=:one WHERE `id`=:two");
		
			$Query->bindValue(":one", $Core->GetMoney() - $DataBuy['cash'], PDO::PARAM_INT);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);

			$Query->execute();
			
			$Query = $MySQL->prepare("INSERT INTO `server_cash` VALUES('', :one, :two, CURRENT_TIMESTAMP)");
			
			$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
			$Query->bindValue(":two", $DataBuy['cash'], PDO::PARAM_INT);
			
			$Query->execute();
	
			$Query = $MySQL->prepare("SELECT * FROM `servers` WHERE `id`=:one");
	
			$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
		
			$Query->execute();
		
			$Fetch = $Query->fetch();
		
			$Core->AddBuyLogs("Kupiono ".$DataBuy['name']." na nick <b>".$Nick."</b> na serwerze <b>".$Fetch['name']."</b>");
			
			if($DataBuy['rcon_kick'] == 1)
			{
				
				$RCON = new HalfLifeRcon();
				
				$RCON->Connect($Fetch['ip'], $Fetch['port'], $Fetch['rcon_pass']);
				
				if($RCON->IsConnected())
				{
				
					$RCON->RconCommand('kick "'.$Nick.'" "Trwa dodawanie XP. Odczekaj 5 sekund i wejdz na serwer! :)"');
					
				}
				
			}
			
			$OtherMySQL = new PDO('mysql:host='.$DataBuy['host'].'; dbname='.$DataBuy['base'].'; charset=utf8;',  $DataBuy['user'],  $DataBuy['pass']);
			
			$Query = $OtherMySQL->prepare($DataBuy['command']);
			$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
			$Query->execute();
			
			$View->Load("info");
			$View->Add('title', 'Zakup dodany');
			$View->Add('header', 'Twój zakup został dodany!');
			$View->Add('info', 'Twój zakup został poprawnie dodany!');
			$View->Add('back', 'buy-'.$DataBuy['server'].'.html');
			$View->Out();
			
		}
		
		else
		{
		
			$Info = '<form method="post" action="buy_mysql-'.$ID.'.html">
		
				<input type="hidden" name="BUY" value="true">
			
				<br><input type="text" name="USER" placeholder="Nick"><br>
			
				<br><button type="submit" class="przycisk">Kupuję! <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("info");
			$View->Add("title", "Zakup :: ".$DataBuy['name']."");
			$View->Add("header", "".$DataBuy['name']." [".$DataBuy['cash']." wPLN]");
			$View->Add("info", $Info);
			$View->Add('back', 'buy-'.$DataBuy['server'].'.html');
			$View->Out();
		
		}
		
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak gotówki');
		$View->Add('header', 'Nie masz tyle gotówki!');
		$View->Add('info', 'Aby to kupić potrzebujesz '.$DataBuy['cash'].' wPLN!');
		$View->Add('back', 'buy-'.$DataBuy['server'].'.html');
		$View->Out();
	
	}

?>