<?php

	if($Core->CheckAdmin())
	{
	
		if(!$_POST['ADD'])
		{
			
			$Query = $MySQL->query("SELECT `id`, `login` FROM `users` ORDER BY `login` ASC");
		
			while($Fetch = $Query->fetch())
			{
		
				$User .= '<option value="'.$Fetch['id'].'">'.$Fetch['login'].'</option>';
			
			}
			
			$Info = '<form method="post" action="admin_add_cash.html">
		
				<input type="hidden" name="ADD" value="true">
			
				<br><input type="text" name="CASH" placeholder="Kwota"><br>
				<br><select name="USER">'.$User.'</select><br>
			
				<br><button type="submit" class="przycisk">Dodaj <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("admin_members");
			$View->Add('title', 'Dodaj kasę');
			$View->Add('header', 'Dodaj kasę');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			$User = $Core->ClearText($_POST['USER']);
			$Cash = $Core->ClearText($_POST['CASH']);
			
			$Query = $MySQL->prepare("UPDATE `users` SET `money`=`money`+:one WHERE `id`=:two");
			
			$Query->bindValue(":one", $Cash, PDO::PARAM_INT);
			$Query->bindValue(":two", $User, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");

			$Query->bindValue(":one", $User, PDO::PARAM_INT);
	
			$Query->execute();
	
			$Fetch = $Query->fetch();
	
			$Name = $Fetch['login'];
			
			$Core->AddAdminLogs("Dodano <b>".$Cash."</b> wPLN użytkownikowi <b>".$Name."</b>");
			
			$View->Load("info");
			$View->Add('title', 'Kasa dodana');
			$View->Add('header', 'Kasa dodana!');
			$View->Add('info', 'Kasa została dodana poprawnie!');
			$View->Add('back', 'admin_add_cash.html');
			$View->Out();
			
			
		}
		
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