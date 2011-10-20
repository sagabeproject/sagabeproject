<?php

 $host = "localhost";
	 $username = "root";
	 $password = "";
	 $db = "vt_db";
	$fname=$_GET["fname"];
	$fpath=$_GET["fpath"];
	$src=$_GET["src"];
		mysql_connect($host,$username,$password)
		or die ("Unable to connect to database.");
		
		mysql_select_db($db)
		or die ("Unable to select database.");
	
	
		$rs = mysql_query("call p_i_insertvideodata('".$fname."','".$fpath."','".$src."')")
		or die ("Unable to complete query");


	
	
?>