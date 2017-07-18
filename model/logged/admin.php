<?php

	if($Core->CheckAdmin())
	{
		
		$SMS = new JustSend();
		
		$Query = $MySQL->query("SELECT `id` FROM `premium_cache` WHERE `premium_id`!=0");
		
		$QueryTwo = $MySQL->query("SELECT `id` FROM `premium_cache` WHERE `premium_id`=0");
		
		$QueryThree = $MySQL->query("SELECT `id` FROM `multi_admins`");
		
		$QueryFour = $MySQL->query("SELECT `id` FROM `guardians`");
		
		$Info .= 'Zakupione premki: '.$Query->rowCount().'<br>
		Ilość adminów: '.$QueryTwo->rowCount().'<br>
		Ilość multi adminów: '.$QueryThree->rowCount().'<br>
		Ilość opiekunów: '.$QueryFour->rowCount().'<br><br>
		Ilość pozostałych SMS: '.$SMS->GetCountSMS().'<br><br><br>
		
		Sklep korzysta z PHP w wersji: '.phpversion().'!';
	
		$View->Load("admin");
		$View->Add("info", $Info);
		$View->Out();
	
	}
	
	else
	{
		
		if($Core->CheckGuard() > 0)
		{
		
			header("Location: guardian.html");
			
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
	
	}

?>