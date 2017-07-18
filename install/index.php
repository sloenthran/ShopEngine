<?php

	echo '<!DOCTYPE HTML>
		
	<html>
	
		<head>
		
			<title>Shop Engine :: Instalacja</title>

			<meta name="viewport" content="width=device-width, initial-scale=1">
		
			<meta http-equiv="content-type" content="text/html" charset="utf-8">
			<meta http-equiv="content-language" content="pl">
		
			<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> 
			<style type="text/css">'.file_get_contents('https://cdn.sloenthran.pl/css/style.css').'</style>

			<link rel="icon" type="image/png" href="https://cdn.sloenthran.pl/images/favicon.png">
		
			<script src="https://cdn.sloenthran.pl/js/jQuery.js"></script>
	
		</head>

		<body>
			
			<div id="container">
		
				<div id="menuclick"><i class="fa fa-th-large"></i></div>
			
				<script>
	
					$( "#menuclick" ).click(function() {
						$( "#header_menu" ).fadeToggle();
					});

				</script>
			
				<a id="logomobilne" href="home.html"><img src="https://cdn.sloenthran.pl/images/logo.png" alt="logo" style="width: 200px;"></a>
        
				<div id="header_menu">
            
				<a href="home.html"><img src="https://cdn.sloenthran.pl/images/logo.png" alt="logo" style="width: 200px;"></a>

				<ul>
   
					<a href="../"><li><i class="fa fa-home"></i> Powrót</li></a>
    
				</ul>
			
			</div>
			
			<br><br><br><br><br>';

	if($_POST['INSTALL'])
	{
		
		require_once("../application/system/Core.php");
		
		$Core = new Core();
		
		$HostSQL = $Core->ClearText($_POST['HOSTSQL']);
		$UserSQL = $Core->ClearText($_POST['USERSQL']);
		$PassSQL = $Core->ClearText($_POST['PASSSQL']);
		$BaseSQL = $Core->ClearText($_POST['BASESQL']);
		
		$User = $Core->ClearText($_POST['USER']);
		$Pass = $Core->ClearText($_POST['PASS']);
		$Mail = $Core->ClearText($_POST['MAIL']);
		
		$Pass = sha1(md5($Pass));
		
		$File = fopen('../config.php', 'w');
      
		flock($File, LOCK_EX);
		
		fwrite($File, '<?php' . "\r\n");
		fwrite($File, '$DB[0] = "'.$HostSQL.'";' . "\r\n");
		fwrite($File, '$DB[1] = "'.$UserSQL.'";' . "\r\n");
		fwrite($File, '$DB[2] = "'.$PassSQL.'";' . "\r\n");
		fwrite($File, '$DB[3] = "'.$BaseSQL.'";' . "\r\n");
		fwrite($File, '?>' . "\r\n");
      
		flock($File, LOCK_UN);
      
		fclose($File);

		$Query[] = "CREATE TABLE IF NOT EXISTS `users`(
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`login` varchar(50) NOT NULL,
			`pass` varchar(150) NOT NULL,
			`ranks` int(1) NOT NULL,
			`money` int(10) NOT NULL,
			`mail` varchar(75) NOT NULL,
			`telephone` int(10) NOT NULL,
			`sms_notification` int(1) NOT NULL,
			PRIMARY KEY (`id`)
		)";
		
		$Query[] = "INSERT INTO `users` VALUES('', '".$User."', '".$Pass."', '1', '0', '".$Mail."', '000000000', '0')";

		$Query[] = "CREATE TABLE IF NOT EXISTS `admin_logs` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`message` varchar(512) NOT NULL,
			`member` int(255) NOT NULL,
			`ip` varchar(50) NOT NULL,
			`time` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `login_logs` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`message` varchar(512) NOT NULL,
			`member` int(255) NOT NULL,
			`ip` varchar(50) NOT NULL,
			`time` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `buy_logs` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`message` varchar(512) NOT NULL,
			`member` int(255) NOT NULL,
			`ip` varchar(50) NOT NULL,
			`time` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `cash_logs` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`message` varchar(512) NOT NULL,
			`member` int(255) NOT NULL,
			`ip` varchar(50) NOT NULL,
			`time` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `other_logs` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`message` varchar(512) NOT NULL,
			`member` int(255) NOT NULL,
			`ip` varchar(50) NOT NULL,
			`time` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `settings` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`name` varchar(512) NOT NULL,
			`value` varchar(512) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `server_cash` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`server_id` int(255) NOT NULL,
			`value` varchar(512) NOT NULL,
			`time` time,
			PRIMARY KEY (`id`)
		)";
	
		$Query[] = "INSERT INTO `settings` VALUES('', 'styles', 'default')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'pay', 'Pukawka')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'user_gest_buy', '1')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'logo_url', 'https://cdn.sloenthran.pl/images/logo.png')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'css_url', 'https://cdn.sloenthran.pl/css/style.css')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'favicon_url', 'https://cdn.sloenthran.pl/images/favicon.png')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'justsend_api', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'justsend_name', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'justsend_alarmnumber', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'facebook_publickey', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'facebook_privatekey', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'facebook_redirecturl', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'api_pukawka', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'api_oneshotonekill', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'api_cssetti', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'api_zabijaka', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'api_profitsms', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'text_profitsms', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'id_liveserver', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'pin_liveserver', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'text_simpay', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'key_simpay', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'pass_simpay', '-')";
		$Query[] = "INSERT INTO `settings` VALUES('', 'service_simpay', '-')";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `servers` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(512) NOT NULL,
			`host` varchar(512) NOT NULL,
			`user` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`type` int(10) NOT NULL,
			`path` varchar(512) NOT NULL,
			`reservation` int(1) NOT NULL,
			`rcon_pass` varchar(512) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `buy` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(512) NOT NULL,
			`cash` varchar(512) NOT NULL,
			`flags` varchar(512) NOT NULL,
			`description` text,
			`server` int(255) NOT NULL,
			`days` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		$Query[] = "CREATE TABLE IF NOT EXISTS `rcon_buy` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(512) NOT NULL,
			`command` varchar(512) NOT NULL,
			`cash` varchar(512) NOT NULL,
			`server` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		$Query[] = "CREATE TABLE IF NOT EXISTS `mysql_buy` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(512) NOT NULL,
			`host` varchar(512) NOT NULL,
			`user` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`base` varchar(512) NOT NULL,
			`command` varchar(512) NOT NULL,
			`cash` varchar(512) NOT NULL,
			`server` int(255) NOT NULL,
			`rcon_kick` int(1) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `premium` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`nick` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`flags` varchar(512) NOT NULL,
			`server` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `premium_cache` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`nick` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`flags` varchar(512) NOT NULL,
			`server` varchar(512) NOT NULL,
			`time` varchar(512) NOT NULL,
			`user_id` int(255) NOT NULL,
			`premium_id` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `free_reservation` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`nick` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`server` varchar(512) NOT NULL,
			`user_id` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `multi_admins` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`sid` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`user_id` int(255) NOT NULL,
			`time` int(255) NOT NULL,
			`type` int(1) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `guardians` (
			`id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			`sid` varchar(512) NOT NULL,
			`pass` varchar(512) NOT NULL,
			`user_id` int(255) NOT NULL,
			`server` int(255) NOT NULL,
			`flags` varchar(512) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
		$Query[] = "CREATE TABLE IF NOT EXISTS `send_sms` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`time` int(255) NOT NULL,
			`premium_id` int(255) NOT NULL,
			PRIMARY KEY (`id`)
		)";
		
		$Query[] = "CREATE TABLE IF NOT EXISTS `buy_menu` (
			`id` int(255) NOT NULL AUTO_INCREMENT,
			`server` int(255) NOT NULL,
			`name` varchar(512) NOT NULL,
			`value` varchar(512) NOT NULL,
			PRIMARY KEY (`id`)
		)";
	
		require_once('../config.php');
	
		$MySQL = new PDO('mysql:host='.$DB[0].'; dbname='.$DB[3].'; charset=utf8;',  $DB[1],  $DB[2]);
	
		$MySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$MySQL->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		foreach($Query as $Key => $Value)
		{
	
			$MySQL->query($Value);
	
		}
		
		$File = fopen('./.htaccess', 'w');
      
		flock($File, LOCK_EX);
		
		fwrite($File, 'Order allow,deny' . "\r\n");
		fwrite($File, 'Deny from all";' . "\r\n");
      
		flock($File, LOCK_UN);
      
		fclose($File);
		
		echo '<div id="srodek">
        
			<h1>Instalacja zakończona pomyślnie</h1>
			
			<p>Instalacja przebiegła pomyślnie!<br>
			Sklep jest już gotowy do użycia...</p>
			
		</div>';
		
	}
	
	else
	{
		
		echo '<div id="srodek">
        
			<h1>Instalator ShopEngine</h1>
			
			<p><form method="post" action="index.php">
		
				<input type="hidden" name="INSTALL" value="true">
				
				<br><input type="text" name="HOSTSQL" placeholder="Host MySQL" required><br>
				<br><input type="text" name="USERSQL" placeholder="Użytkownik MySQL" required><br>
				<br><input type="password" name="PASSSQL" placeholder="Hasło MySQL" required><br>
				<br><input type="text" name="BASESQL" placeholder="Baza MySQL" required><br>
				
				<br><br>
			
				<br><input type="text" name="USER" placeholder="Login" required><br>
				<br><input type="password" name="PASS" placeholder="Hasło" required><br>
				<br><input type="text" name="MAIL" placeholder="E-mail" required><br>
			
				<br><button type="submit" class="przycisk">Instaluj <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form></p>
				
		</div>';
		
	}
	
	echo '	</div>
    
			<div class="clearfix"></div>
    
			<div id="footerbg">
        
				<div id="footer">
					
					<span class="pull-right">
				
						Grafika: <a href="http://upek.eu">upek</a> | 
					
						Shop Engine: <span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
	
							<span itemprop="name"><a href="https://sloenthran.pl">Sloenthran</a></span>
			
						</span>
				
					</span>
			
				</div>
    
			</div>
    
		</body>
	
	</html>';

?>