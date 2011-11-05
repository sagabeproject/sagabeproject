<?php @ require_once ('dbConnect.php'); ?>
<?php

	/*$host = "localhost";
	$username = "root";
	$password = "";
	$db = "vt_db";
	$fname=$_GET["fname"];
	$fpath=$_GET["fpath"];
	$src=$_GET["src"];
	$opid=$_GET["opid"];
	
		mysql_connect($host,$username,$password)
		or die ("Unable to connect to database.");
		
		mysql_select_db($db)
		or die ("Unable to select database.");*/
	$fname=$_GET["fname"];
	$fpath=$_GET["fpath"];
	$src=$_GET["src"];
	$opid=$_GET["opid"];
	
		
		/*$fname = "file1";
		$fpath = "fpath1";
		$src = "src1";
		$opid="1 2 3 4";
		*/
		
		$arr = explode(" ", $opid);
		$len=count($arr);
		echo $len;
		
		
		$rs = mysql_query("call p_i_insertvideodata('".$fname."','".$fpath."','".$src."');")
		or die ("Unable to complete query1");
		
		$rs = mysql_query("select file_id from vt_inputdata_master where file_name='".$fname."' and file_path='".$fpath."';")
		or die ("Unable to complete query2");
		
		$info=mysql_fetch_array($rs);
		$fid=$info['file_id'];
		
		for($i=0;$i<$len;$i++)
		{
		$format=(int) $arr[$i];
		echo "\n".$format;
		$rs = mysql_query("call p_i_insertprocess($fid, $format)")
		or die ("Unable to complete query3");
		}

	
	
?>