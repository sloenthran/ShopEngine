<?php

	$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='facebook_publickey'");
	$Fetch = $Query->fetch();
	
	$Key = $Fetch['value'];
	
	$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='facebook_redirecturl'");
	$Fetch = $Query->fetch();
	
	$URL = $Fetch['value'];

	header("Location: https://www.facebook.com/dialog/oauth?client_id=".$Key."&redirect_uri=".$URL."&scope=email");

?>