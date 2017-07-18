<?php

	error_reporting(0);

	ob_start();
	
		session_start();
		
		require_once('./config.php');
		
		$MySQL = new PDO('mysql:host='.$DB[0].'; dbname='.$DB[3].'; charset=utf8;',  $DB[1],  $DB[2]);
		
		$MySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$MySQL->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		
		$Code = $_REQUEST['code'];
		
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='facebook_publickey'");
		$Fetch = $Query->fetch();
		
		$PublicKey = $Fetch['value'];
		
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='facebook_privatekey'");
		$Fetch = $Query->fetch();
		
		$PrivateKey = $Fetch['value'];
		
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='facebook_redirecturl'");
		$Fetch = $Query->fetch();
		
		$URL = $Fetch['value'];
		
		$cURL = curl_init('https://graph.facebook.com/oauth/access_token');
		
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($cURL, CURLOPT_POST, 1);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, 'client_id='.$PublicKey.'&redirect_uri='.$URL.'&client_secret='.$PrivateKey.'&code='.$Code.'');

		$Query = curl_exec($cURL);
		
		curl_close($cURL);
   
		$Data = explode('&', $Query);
    
		foreach($Data as $Key)
		{
			
			$Item = explode('=', $Key);
			$GiveKey[$Item[0]] = $Item[1];
		
		}
		
		$GiveKey = $GiveKey['access_token'];
   
		header("Location: facebook_login-".$GiveKey.".html");
		
	ob_end_flush();
	
?>