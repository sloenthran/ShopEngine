<?php

	$ID = $Core->ClearText($_GET['id']);
	
	if(!$ID || $ID == '')
	{

		$Query = $MySQL->query("SELECT `id`,`name` FROM `servers`");
		
		while($Fetch = $Query->fetch())
		{
		
			$Menu .= '<a href="buy-'.$Fetch['id'].'.html"><li><i class="fa fa-asterisk"></i> '.$Fetch['name'].'</li></a>';
		
		}
		
		$View->Load("buy");
		$View->Add("header", "Wybierz serwer");
		$View->Add("info", "Wybierz serwer z menu");
		$View->Add("menu", $Menu);
		$View->Add("back", "home.html");
		$View->Out();
		
	}
	
	else
	{
	
		$Query = $MySQL->prepare("SELECT `id`,`name` FROM `buy` WHERE `server`=:one");
		
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		
		$Query->execute();
		
		while($Fetch = $Query->fetch())
		{
		
			$Menu .= '<a href="buy_two-'.$Fetch['id'].'.html"><li><i class="fa fa-asterisk"></i> '.$Fetch['name'].'</li></a>';
		
		}
		
		$Query = $MySQL->prepare("SELECT `id`,`name`,`cash` FROM `mysql_buy` WHERE `server`=:one");
		
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		
		$Query->execute();
		
		while($Fetch = $Query->fetch())
		{
		
			$Menu .= '<a href="buy_mysql-'.$Fetch['id'].'.html"><li><i class="fa fa-asterisk"></i> '.$Fetch['name'].' ['.$Fetch['cash'].' wPLN]</li></a>';
		
		}
		
		$Query = $MySQL->prepare("SELECT `reservation` FROM `servers` WHERE `id`=:one");
		
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		
		$Query->execute();
		
		$Fetch = $Query->fetch();
		
		if($Fetch['reservation'] == 1)
		{
		
			$Menu .= '<a href="free_reservation-'.$ID.'.html"><li><i class="fa fa-asterisk"></i> Rezerwacja nicku</li></a>';
		
		}
		
		$Query = $MySQL->prepare("SELECT * FROM `buy_menu` WHERE `server`=:one");
		
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		
		$Query->execute();
		
		while($Fetch = $Query->fetch())
		{
			
			$Menu .= '<a href="'.$Fetch['value'].'" target="_blank"><li><i class="fa fa-asterisk"></i> '.$Fetch['name'].'</li></a>';
			
		}
	
		$View->Load("buy");
		$View->Add("header", "Wybierz zakup");
		$View->Add("info", "Wybierz zakup z menu");
		$View->Add("back", "buy.html");
		$View->Add("menu", $Menu);
		$View->Out();
	
	}

?>