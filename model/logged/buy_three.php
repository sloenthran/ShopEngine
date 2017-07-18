<?php

	$ID = $Core->ClearText($_GET['id']);
	
	$Query = $MySQL->prepare("SELECT * FROM `buy` WHERE `id`=:one");
	
	$Query->bindValue(':one', $ID, PDO::PARAM_INT);
	
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$DataBuy = $Fetch;
	
	if($Core->GetMoney() >= $DataBuy['cash'])
	{
	
		if($_POST['BUY'])
		{
		
			$Nick = $Core->ClearText($_POST['USER']);
			$Pass = $Core->ClearText($_POST['PASS']);
			
			if(!$Nick || !$Pass)
			{
			
				$View->Load("info");
				$View->Add("title", "Błąd :: Puste pola");
				$View->Add("header", "Błąd! Puste pola!");
				$View->Add("info", "Pola formularza nie mogą być puste!");
				$View->Add("back", "buy_three-".$ID.".html");
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
						$View->Add('back', 'buy_three-'.$ID.'.html');
						$View->Out();
					
					}
					
					else if($Fetch['premium_id'] == $ID)
					{
						
						$BAD++;
			
						$View->Load("info");
						$View->Add('title', 'Błąd :: Nick istnieje');
						$View->Add('header', 'Taki nick już istnieje!');
						$View->Add('info', 'Taki nick już istnieje w bazie danych.<br>Jeżeli jesteś właścicielem możesz go przedłużyć <a href="my_buy.html">klikając tutaj</a>!');
						$View->Add('back', 'buy_three-'.$ID.'.html');
						$View->Out();
						
					}
			
				}
			
				if($BAD == 0)
				{
				
					$Buy = new Buy();
					
					$Query = $MySQL->prepare("SELECT `days` FROM `buy` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$Fetch = $Query->fetch();
					
					$Days = $Fetch['days'];
				
					$Buy->AddBuy($Nick, $Pass, $ID, $_SESSION['ID'], $Days);
					
					$Query = $MySQL->prepare("UPDATE `users` SET `money`=:one WHERE `id`=:two");
					
					$Query->bindValue(":one", $Core->GetMoney() - $DataBuy['cash'], PDO::PARAM_INT);
					$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
					
					$Query->execute();
					
					$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					
					$Query->bindValue(":one", $DataBuy['server'], PDO::PARAM_INT);
					
					$Query->execute();
					
					$Fetch = $Query->fetch();
					
					$Core->AddBuyLogs("Kupiono premium na nick <b>".$Nick."</b> na serwerze <b>".$Fetch['name']."</b>");
				
					$View->Load("info");
					$View->Add('title', 'Zakup dodany');
					$View->Add('header', 'Twój zakup został dodany!');
					$View->Add('info', 'Twój zakup został poprawnie dodany. Na serwerze będzie aktywny po około minucie!<br>Nie zapomnij o wpisaniu w konsoli<br><b>setinfo _pw "'.$Pass.'"</b>');
					$View->Add('back', 'my_buy.html');
					$View->Out();
					
				}
				
			}
		
		}
		
		else
		{
		
			$Info = '<form method="post" action="buy_three-'.$ID.'.html">
		
				<input type="hidden" name="BUY" value="true">
			
				<br><input type="text" name="USER" placeholder="Nick lub SID"><br>
				<br><input type="text" name="PASS" placeholder="Hasło"><br>
			
				<br><button type="submit" class="przycisk">Kupuję! <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("info");
			$View->Add("title", "Zakup :: ".$DataBuy['name']."");
			$View->Add("header", "".$DataBuy['name']." [".$DataBuy['cash']." wPLN]");
			$View->Add("info", $Info);
			$View->Add('back', 'buy_two-'.$ID.'.html');
			$View->Out();
		
		}
	
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak gotówki');
		$View->Add('header', 'Nie masz tyle gotówki!');
		$View->Add('info', 'Aby to kupić potrzebujesz '.$DataBuy['cash'].' wPLN!');
		$View->Add('back', 'buy_two-'.$ID.'.html');
		$View->Out();
	
	}

?>