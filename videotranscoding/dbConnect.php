<?php	
	
	$host = "localhost";
	$username = "root";
	$password = "";
	$db = "vt_db";
	
	
		mysql_connect($host,$username,$password)
		or die ("Unable to connect to database.");
		
		mysql_select_db($db)
		or die ("Unable to select database.");
		
?>		