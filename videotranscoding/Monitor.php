<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once ('dbConnect.php');?>
<meta http-equiv="page-exit" content="blendTrans (Duration=2)">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/> 
<meta name="author" content="author"/> 

<link rel="stylesheet" type="text/css" href="default.css" media="screen"/>
<title>Mime-360 : Video Transcoding Monitor</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type = "text/javascript">



</script>

</head>

<body>
<script>
function clearFinished(video)
{
var table = document.getElementById("monitor_table");

<?php
for($i=0; $i<video.length;$i++)
alert($video[$i]);

$query = "SELECT status_id FROM 'vt_process_master' WHERE file_name ='".$video[$i]."';";
$rs = mysql_query($query);
					
//or die ("Unable to complete query1");

		$status_id = mysql_result($rs,$i,"status_id");
		

		if($video_status == 3 ||$video_status == 4)
		{?>
			table.deleteRow(<?php echo $i?>);
		<?php
		}

?>

}

function refresh15seconds()
{
	if((document.monitor_form.refresh15sec).checked == true)
	{
		alert("true!")
		<?php echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"5\">"; ?>
	}
	else
	{
		<?php echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"5\">"; ?>
	}
}
</script>

<div class="container">

<div class="navigation">

<div class="title">
<h1>Mime360</h1>
<h2>- Video Transcoding Dashboard -</h2>
</div>

		<a href="index.html" class = "transition">Home</a>
		<a href="Monitor.php" class = "transition">Monitor</a>
		<a href="summaryview.php" class = "transition">Summary View</a>
        <a href="transcode.php" class = "transition">Transcode</a>
<div class="clearer"><span></span></div>

</div>

<div class="holder_top"></div>

<div class="holder">

<div class = "holder_content">
<h1 align="center"><strong>Transcoding Monitor</strong></h1>
<p>&nbsp;</p>

<?php

/*$rs = mysql_query("	SELECT file_name, status_desc, platform_name, quality_name
					FROM `vt_process_master` AS a, vt_status_lookup AS b, vt_inputdata_master AS c, vt_format_lookup AS d, vt_quality_lookup AS e, vt_platform_lookup as f
					WHERE a.status_id = b.status_id
					AND a.queued_file_id = c.file_id
					AND a.op_format_id = d.format_id
					AND d.quality_id = e.quality_id
					AND d.platform_id = f.platform_id
				")*/
				
$rs = mysql_query("SELECT file_name, status_desc, platform_name, quality_name
				   FROM `vt_process_master` AS a
				   INNER JOIN vt_status_lookup AS b
				   ON a.status_id = b.status_id
				   INNER JOIN vt_inputdata_master AS c
				   ON a.queued_file_id = c.file_id
				   INNER JOIN vt_format_lookup AS d
				   ON a.op_format_id = d.format_id
				   INNER JOIN vt_quality_lookup AS e
				   ON d.quality_id = e.quality_id
				   INNER JOIN vt_platform_lookup as f
				   ON d.platform_id = f.platform_id"
				  )				   
		or die ("Unable to complete query1");
		
		$video_info = mysql_fetch_array($rs);
		$num_of_rows = mysql_numrows($rs);
				
		mysql_close();

?>

<form name="monitor_form" action="dosomething.php" method="post">
  <table summary="folder contents for fly types" width="100%" id = "monitor_table">
    <thead>
      <tr>
        <th class="video">Format</th>
        <th class="cpu">CPU</th>
        <th class="memory">Memory</th>
        <th class="status">Status</th>
		<th class="progress">Progress</th>
        <th class="estimatedtime">Estimated time to finish</th>
        </tr>
      </thead>
    <tbody>
	<?php
      while($i < $num_of_rows)
		{
			$video_name = mysql_result($rs,$i,"file_name");
			$video_status = mysql_result($rs,$i,"status_desc");
			$platform_name = mysql_result($rs,$i,"platform_name");
			$quality_name = mysql_result($rs,$i,"quality_name");
			
			echo "<tr>";
				echo "<th colspan=\"3\" id = \"video1\"><img src=\"img/minus.jpg\" alt=\"\" width=\"10\" height=\"10\" /><strong>".$video_name."</strong></th></tr>";
			echo "<tr class = \"srvideo1\">";
			echo "<th class=\"start\"><label>";
			echo "<div align=\"left\">";
			echo "<input type=\"checkbox\" name=\"video[]\" id=\"checkbox\" />";
			echo $platform_name." ".$quality_name."</div>";
			echo "</label></th><td>90%</td><td>5%</td>";
			echo "<td class=\"start\">".$video_status."</td>";
$str = "<div class=\"meter-wrap\">
			<div class=\"meter-value\" style=\"background-color: #0a0; width: 40%;\">
				<div class=\"meter-text\">
					In progress
				</div>
			</div>
		</div>";
			echo "<td class=\"progress\">".$str."</td>";
			echo "<td>3 hrs 14 min</td></tr>";		
			
			$i++;
		}
      
      ?>

      </tbody>
  </table>
  
  <p align="right">
    <input type="checkbox" name="refresh15sec" id="refresh15sec" value="" onClick = "refresh15seconds()"/>
    Refresh automatically every 15 seconds
 </p>
  <p>
    <input name="button" type="submit" class="alignright" id="button" value="Refresh" />
  </p>
  <p>
  <table width="100%">
  <tr>
    <td/><div align="left"></div>
    <input type="submit" name="pauseselected" id="pauseselected" value="Pause Selected" /> 
    <td/><div align="center"></div>
    <input type="submit" name="resumeselected" id="resumeselected" value="Resume Selected" />
    <td/><div align="right"></div>
    <input type="button" name="clearfinished" id="clearfinished" value="Clear Finished" onClick = "clearFinished('document.monitor_form.video');"/>
   </tr>
   </table>
    </p>
</form>


</div>

</div>
<div class="footer">


</div>

</div>

</body>

</html>

