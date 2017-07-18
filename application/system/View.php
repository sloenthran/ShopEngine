<?php

	class View
	{
		
		var $TagList = array();
		var $BodyHTML;

		function Load($FileName)
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='styles'");
			$Fetch = $Query->fetch();
	
			$File = './view/'.$Fetch['value'].'/'.$FileName.'.html';
		
			$FileHandle = fopen($File, "r");
		
			$this->BodyHTML = fread($FileHandle, filesize($File));
		
			fclose($FileHandle);
		
		}

		function Parse()
		{
			
			foreach($this->TagList as $Tag => $Value)
			{
				
				$this->BodyHTML = str_replace($Tag, $Value, $this->BodyHTML);
			
			}
			
			return $this->BodyHTML;
		
		}

		function Add($Tag, $Value)
		{
			
			$Tag = "{" . $Tag . "}";
			
			$this->TagList[$Tag] = $Value;
		
		}

		function Out()
		{
			
			global $MySQL;
			
			$Options = array(
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => false,
				),
			);  
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='css_url'");
			
			$Fetch = $Query->fetch();
			
			$CSS = $this->Minify(file_get_contents($Fetch['value'], false, stream_context_create($Options)));
			
			$this->Add('css', '<style type="text/css">'.$CSS.'</style>');
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='logo_url'");
			
			$Fetch = $Query->fetch();
			
			$this->Add('logo', $Fetch['value']);
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='favicon_url'");
			
			$Fetch = $Query->fetch();
			
			$this->Add('favicon', '<link rel="icon" type="image/png" href="'.$Fetch['value'].'">');
			
			$this->Parse();
			
			echo $this->BodyHTML;
		
		}
		
		function Minify($Text)
		{
	
			$Text = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $Text);
			$Text = str_replace(': ', ':', $Text);
			$Text = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $Text);
		
			return $Text;
	
		}

	}
	
?>