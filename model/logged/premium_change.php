<?php

	if($_POST['CHANGE'])
	{
		
		
		
	}
	
	else
	{
		
		$View->Load('premium_settings');
		$View->Add('title', 'Przypisz zakup');
		$View->Add('header', 'Przypisz zakup');
		$View->Add('info', 'W budowie...');
		$View->Out();
		
	}

?>