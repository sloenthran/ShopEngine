<?php

	$Check = $Core->CheckGuard();

	if($Check > 0)
	{
		
		$Query = $MySQL->prepare("SELECT `id` FROM `premium_cache` WHERE `premium_id`!=0 AND `server`=:one");
		$Query->bindValue(":one", $Check, PDO::PARAM_INT);
		$Query->execute();
		
		$QueryTwo = $MySQL->prepare("SELECT `id` FROM `premium_cache` WHERE `premium_id`=0 AND `server`=:one");
		$QueryTwo->bindValue(":one", $Check, PDO::PARAM_INT);
		$QueryTwo->execute();
		
		$Info .= 'Zakupione premki: '.$Query->rowCount().'<br>
		Ilość adminów: '.$QueryTwo->rowCount().'<br>';
	
		$View->Load("guard");
		$View->Add("info", $Info);
		$View->Out();
	
	}
	
	else
	{
		
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak uprawnień');
		$View->Add('header', 'Błąd! Brak uprawnień!');
		$View->Add('info', 'Nie posiadasz uprawnień administracyjnych!');
		$View->Add('back', 'home.html');
		$View->Out();
	
	}

?>