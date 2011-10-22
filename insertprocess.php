<?php

 $host = "localhost";
	 $username = "root";
	 $password = "";
	 $db = "vt_db";
	$fid=$_GET["fid"];
	$opid=$_GET["opid"];
		mysql_connect($host,$username,$password)
		or die ("Unable to connect to database.");
		
		mysql_select_db($db)
		or die ("Unable to select database.");
	
	
		$rs = mysql_query("call p_i_insertprocess($fid, $opid)")
		or die ("Unable to complete query");


	
	
?>