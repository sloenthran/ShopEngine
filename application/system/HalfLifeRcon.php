<?php

	class HalfLifeRcon
	{

		var $challenge_number;
		var $connected;
		var $server_ip;
		var $server_password;
		var $server_port;
		var $socket;

		function __construct()
		{
    
			$this->challenge_number = 0;
			$this->connected = true;
			$this->server_ip = "";
			$this->server_port = 27015;
			$this->server_password = "";
		
		}

		function Connect($server_ip, $server_port, $server_password = "")
		{
    
			$this->server_ip = gethostbyname($server_ip);
			$this->server_port = $server_port;
			$this->server_password = $server_password;

			$fp = fsockopen("udp://" . $this->server_ip, $this->server_port, $errno, $errstr, 5);
			if($fp)
			{
				
				$this->connected = true;
				
			}
    
			else
			{
				
				$this->connected = false;
				return false;
			
			}

			$this->socket = $fp;

			return true;

		}

		function Disconnect()
		{
    
			fclose($this->socket);
			$connected = false;

		}

		function IsConnected()
		{

			return $this->connected;
		
		}

		function RconCommand($command, $pagenumber = 0, $single = true)
		{
    
			if(!$this->connected)
			{
				
				return $this->connected;
				
			}

			if($this->challenge_number == "")
			{
				
				$challenge = "\xff\xff\xff\xffchallenge rcon\n";
				$buffer = $this->Communicate($challenge);

				if(trim($buffer) == "")
				{
					
					$this->connected = false;
					return false;
				
				}

				$buffer = explode(" ", $buffer);
				$this->challenge_number = trim($buffer[2]);
			
			}

			$command = "\xff\xff\xff\xffrcon $this->challenge_number \"$this->server_password\" $command\n";

			$result = "";
			$buffer = "";
			
			while($pagenumber >= 0)
			{
    
				$buffer .= $this->Communicate($command);

				if($single == true)
				{
					
					$result = $buffer;
					
				}

				else
				{
					
					$result .= $buffer;
					
				}

				$command = "";

				$pagenumber--;

			}

			return trim($result);

		}

		function Communicate($command)
		{
    
			if(!$this->connected)
			{
     
				return $this->connected;
				
			}

			if($command != "")
			{
    
				fputs($this->socket, $command, strlen($command));
				
			}

			$buffer = fread ($this->socket, 1);
			$status = socket_get_status($this->socket);
			$buffer .= fread($this->socket, $status["unread_bytes"]);

			if(substr($buffer, 0, 4) == "\xfe\xff\xff\xff")
			{
				
				$buffer2 = fread ($this->socket, 1);
				$status = socket_get_status($this->socket);
				$buffer2 .= fread($this->socket, $status["unread_bytes"]);

				if(strlen($buffer) > strlen($buffer2))
				{
       
					$buffer = substr($buffer, 14) . substr($buffer2, 9);
					
				}
				
				else
				{
					
					$buffer = substr($buffer2, 14) . substr($buffer, 9);
					
				}

			}

			else
			{
		
				$buffer = substr($buffer, 5);
				
			}

			return $buffer;

		}

	}

?>