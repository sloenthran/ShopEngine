<?php

	class FTP
	{
		
		function Upload($Host, $User, $Pass, $ID, $Path)
		{

			$File = curl_init('ftp://'.$User.':'.$Pass.'@'.$Host.'/'.$Path.'/users.ini');
			$Open = fopen('cache/'.$ID.'.ini', 'r');
			curl_setopt($File, CURLOPT_INFILE, $Open);
			curl_setopt($File, CURLOPT_UPLOAD, true);
			curl_setopt($File, CURLOPT_TIMEOUT, 5);
			curl_exec($File);
			fclose($Open);
			curl_close($File);
	
			unlink('./cache/'.$ID.'.ini');
		
		}
		
		function UpdatePassBans($Host, $User, $Pass, $ID, $Path)
		{

			$File = curl_init('ftp://'.$User.':'.$Pass.'@'.$Host.'/'.$Path.'/sql.cfg');
			$Open = fopen('cache/'.$ID.'.cfg', 'r');
			curl_setopt($File, CURLOPT_INFILE, $Open);
			curl_setopt($File, CURLOPT_UPLOAD, true);
			curl_setopt($File, CURLOPT_TIMEOUT, 5);
			curl_exec($File);
			fclose($Open);
			curl_close($File);
	
			unlink('./cache/'.$ID.'.cfg');
		
		}
	
	}

?>