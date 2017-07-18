<?php

	if($Core->CheckAdmin())
	{
	
		$Pages = $Core->ClearText($_GET['id']);
	
		$Query = $MySQL->query("SELECT `id` FROM `admin_logs`");
		$Count = $Query->rowCount();
	
		$PagesNumber = floor($Count / 10);
	
		if(!$Pages || $Pages < 0 || $Pages > $PagesNumber)
		{
	
			$Pages = 0;
	
		}
	
		$NewPages = 10 * $Pages;

		$Query = $MySQL->prepare("SELECT * FROM `admin_logs` ORDER BY `id` DESC LIMIT :one, 10");
	
		$Query->bindValue(':one', $NewPages, PDO::PARAM_INT);
	
		$Query->execute();
		
		$Member = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
		
		while($Fetch = $Query->fetch())
		{
		
			$Member->bindValue(':one', $Fetch['member'], PDO::PARAM_INT);
			
			$Member->execute();
			
			$FetchMember = $Member->fetch();
			
			$Time = date("d.m.Y H:i", $Fetch['time']);
			
			$Info .= '<tr>
		
				<td>'.$FetchMember['login'].'</td>
				<td>'.$Fetch['message'].'</td>
				<td>'.$Time.'</td>
				<td>'.$Fetch['ip'].'</td>
			
			</tr>';
			
		}
		
		$Button .= '<a href="admin_logs.html"><button class="przycisk">0</button></a>&nbsp;&nbsp;&nbsp;';
	
		if($Pages != 0)
		{
	
			$Number = 0;
			$Number = $Pages - 1;
		
			if($Number == 0)
			{
	
				$Button .= '<a href="admin_logs.html"><button class="przycisk"><i class="fa fa-arrow-left"></i></button></a>&nbsp;&nbsp;&nbsp;';
			
			}
		
			else
			{
		
				$Button .= '<a href="admin_logs-'.$Number.'.html"><button class="przycisk"><i class="fa fa-arrow-left"></i></button></a>&nbsp;&nbsp;&nbsp;';
		
			}
	
		}
	
		if($Pages != $PagesNumber)
		{
	
			$Number = 0;
			$Number = $Pages + 1;
	
			$Button .= '<a href="admin_logs-'.$Number.'.html"><button class="przycisk"><i class="fa fa-arrow-right"></i></button></a>&nbsp;&nbsp;&nbsp;';
	
		}
	
		$Button .= '<a href="admin_logs-'.$PagesNumber.'.html"><button class="przycisk">'.$PagesNumber.'</button></a>';
		
		$View->Load("admin_logs");
		$View->Add('header', 'Logi adminów');
		$View->Add('info', $Info);
		$View->Add('button', $Button);
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