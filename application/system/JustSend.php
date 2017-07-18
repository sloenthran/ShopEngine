<?php

	class JustSend
	{
		
		var $Name;
		var $Key;
		
		function __construct()
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='justsend_api'");
			
			$Fetch = $Query->fetch();
			
			$this->Key = $Fetch['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='justsend_name'");
			
			$Fetch = $Query->fetch();
			
			$this->Name = $Fetch['value'];
			
		}
		
		function SendMessage($Message, $NumberSMS)
		{
			
			$Key = $this->Key;
			$Name = $this->Name;
			
			file_get_contents('https://justsend.pl:443/api/rest/message/send/'.$Key.'/'.$NumberSMS.'/'.$Name.'/'.rawurlencode($Message).'/PRO');
			
		}
		
		function SendAlarm()
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='justsend_alarmnumber'");
			
			$Fetch = $Query->fetch();
			
			$this->SendMessage("ALARM! Koniec srodkow na JustSend!", $Fetch['value']);
			
		}
		
		function GetCountSMS()
		{
			
			$Key = $this->Key;
			
			$Query = file_get_contents("https://justsend.pl/api/rest/payment/points/".$Key."");
			
			$Data = json_decode($Query);
			
			return floor($Data->data / 7);
			
		}
		
	}

?>