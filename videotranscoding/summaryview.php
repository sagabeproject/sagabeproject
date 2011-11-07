<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="page-exit" content="blendTrans (Duration=2)">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/> 
<meta name="author" content="author"/> 
<link rel="stylesheet" type="text/css" href="default.css" media="screen"/>
<title>Mime-360 : Video Transcoding Monitor</title>
<script type="text/javascript"
src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<?php require ('dbConnect.php'); ?>
<script>
	$(document).ready(function() {		   
							   
	//Set default open/close settings
		$('.acc_container').hide();
		$('.acc_trigger:first').addClass('active').next().show(); 

	//On Click
		$('.acc_trigger').click(function(){
			if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
		$('.acc_trigger').removeClass('active').next().slideUp(); //Remove all "active" state and slide up the immediate next container
		$(this).toggleClass('active').next().slideDown(); //Add "active" state to clicked trigger and slide down the immediate next container
	}
	return false; //Prevent the browser jump to the link anchor
});
});
</script>

</head>

<body>

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
    <h1 align="center"><strong>Transcoding Summary</strong></h1>
		<h2 class="acc_trigger"><a href="#">Processed Videos</a></h2>
	<div class="acc_container">
	  <div class="block">

		<p>&nbsp;</p>
		<table width="100%" height="237" border="1">
				  <thead>
			  <tr>
				<th class="video">Video</th>
				<th class="videoSource">Video Source Path</th>
				<th class="destinationFormat">Destination Format</th>
				<th class="finishTime">Finish Time</th>
				</tr>
			  </thead>
			  <?php
				$rs = mysql_query("select * from vt_process_master where status_id = 4;")//For processed videos
				or die ("Unable to complete in body of processed videos ".mysql_error() );
				
				$num_of_rows = mysql_numrows($rs);
				
				for($i = 0; $i < $num_of_rows; $i++)
				{
					$rs2 = mysql_query("select file_name,file_path from vt_inputdata_master where file_id = ".mysql_result($rs,$i,"queued_file_id").";")//For processed videos
					or die ("Unable to complete in body of processed videos 1".mysql_error() );
					//$rs3 = mysql_query("select platform_name from vt_platform_lookup where platform_id = (select platform_id from vt_format_lookup where format_id = (select op_format_id from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."));")
					//or die ("Unable to complete in body of processed videos 2".mysql_error() );
					$rs4 = mysql_query("select dte_update from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."")//For processed videos
					or die ("Unable to complete in body of processed videos 1".mysql_error() );
					echo "<tr>";
					echo "<td>".mysql_result($rs2,$i,"file_name")."</td>";
					echo "<td>".mysql_result($rs2,$i,"file_path")."</td>";
					echo "<td></td>";
					//echo "<td>".mysql_result($rs3,$i,"platform_name")."</td>";
					echo "<td>".mysql_result($rs4,0,"dte_update")."</td>";
					echo "</tr>";
				}
				/*
				//$dest_plat[];
				//$dest_quality[];
				$rs5 = mysql_query("select op_format_id from vt_process_master where status_id = 4;")//For inprocess videos
				or die ("Unable to complete in body of processed videos 1".mysql_error() );
				$num_of_rows5 = mysql_numrows($rs5);
				
				for($j = 0;$j < $num_of_rows5;$j++)
				{
					$rs6 = mysql_query("select platform_id,quality_id from vt_format_lookup where format_id = ".mysql_result($rs5,$j,"format_id").";")//For inprocess videos
					or die ("Unable to complete in body of processed videos 1".mysql_error() );
					$num_of_rows6 = mysql_numrows($rs6);
					
					for($k = 0;$k < $num_of_rows6;$k++)
					{
						$plat_name = mysql_query("select platform_name from vt_platform_lookup where platform_id = ".mysql_result($rs6,$k,"platform_id").";")//For inprocess videos
						or die ("Unable to complete in body of processed videos 1".mysql_error() );
						
						$dest_plat[$k] = mysql_result($plat_name,0,"platform_name");
						
						$dest_qua = mysql_query("select quality_name from vt_quality_lookup where quality_id = ".mysql_result($rs6,$k,"quality_id").";")//For inprocess videos
						or die ("Unable to complete in body of processed videos 1".mysql_error() );
						
						$dest_qual[$k] = mysql_result($plat_name,0,"quality_name");
					}
					
					echo "<td>";
					for($k = 0;$k < $num_of_rows6;$k++)
					{
						echo $dest_plat[$k]."".$dest_qual[$k].", ";
					}
					echo "</td>";*/
				
			  ?>
	    </table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
</div>
	</div>
	
	<h2 class="acc_trigger"><a href="#">Inprocess Videos</a></h2>
	<div class="acc_container">
		<div class="block">
			<p>&nbsp;</p>
			<table width="452" height="237" border="1">
		  <thead>
      <tr>
        <th class="video">Video</th>
		<th class="videoSource">Video Source Path</th>
        <th class="sourceFormat">Source Format</th>
		<th class="destinationFormat">Destination Format</th>
		<th class="timneToFinishTime">Time to Finish</th>
        </tr>
      </thead>
		  <?php
				$rs = mysql_query("select * from vt_process_master where status_id = 1 or status_id = 2;")//For processed videos
				or die ("Unable to complete in body of processed videos ".mysql_error() );
				
				$num_of_rows = mysql_numrows($rs);
				
				for($i = 0; $i < $num_of_rows; $i++)
				{
					$rs2 = mysql_query("select file_name,file_path from vt_inputdata_master where file_id = ".mysql_result($rs,$i,"queued_file_id").";")//For inprocess videos
					or die ("Unable to complete in body of processed videos 1".mysql_error() );
					$num_of_rows2 = mysql_numrows($rs2);
					//$rs3 = mysql_query("select platform_name from vt_platform_lookup where platform_id = (select platform_id from vt_format_lookup where format_id = (select op_format_id from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."));")
					//or die ("Unable to complete in body of processed videos 2".mysql_error() );
					//$rs4 = mysql_query("select dte_update from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."")//For inprocess videos
					//or die ("Unable to complete in body of processed videos 3".mysql_error() );
					for($j = 0; $j < $num_of_rows2; $j++)
					{
					echo "<tr>";
					echo "<td>".mysql_result($rs2,$j,"file_name")."</td>";
					echo "<td>".mysql_result($rs2,$j,"file_path")."</td>";
					echo "<td></td>";
					//echo "<td>".mysql_result($rs3,$i,"platform_name")."</td>";
					//echo "<td>".mysql_result($rs4,0,"dte_update")."</td>";
					echo "</tr>";
					}
				}
			  ?>
	    </table>
		</div>
	</div>
	
	<h2 class="acc_trigger"><a href="#">Queued Videos</a></h2>

	<div class="acc_container">
	  <div class="block">
		<h3>Unprocessed Videos</h3>
		  <p>&nbsp;</p>
		  <table width="100%" height="237" border="1">
		  <thead>
      <tr>
        <th class="video">Video</th>
		<th class="videoSource">Video Source Path</th>
        <th class="sourceFormat">Source Format</th>
		<th class="destinationFormat">Destination Format</th>
		<th class="timneToFinishTime">Time to Finish</th>
        </tr>
      </thead>
		  <?php
				$rs = mysql_query("select * from vt_process_master where status_id = 6 or status_id = 2;")//For processed videos
				or die ("Unable to complete in body of processed videos ".mysql_error() );
				
				$num_of_rows = mysql_numrows($rs);
				
				for($i = 0; $i < $num_of_rows; $i++)
				{
					$rs2 = mysql_query("select file_name,file_path from vt_inputdata_master where file_id = ".mysql_result($rs,$i,"queued_file_id").";")//For inprocess videos
					or die ("Unable to complete in body of processed videos 1".mysql_error() );
					$num_of_rows2 = mysql_numrows($rs2);
					//$rs3 = mysql_query("select platform_name from vt_platform_lookup where platform_id = (select platform_id from vt_format_lookup where format_id = (select op_format_id from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."));")
					//or die ("Unable to complete in body of processed videos 2".mysql_error() );
					//$rs4 = mysql_query("select dte_update from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."")//For inprocess videos
					//or die ("Unable to complete in body of processed videos 3".mysql_error() );
					for($j = 0; $j < $num_of_rows2; $j++)
					{
					echo "<tr>";
					echo "<td>".mysql_result($rs2,$j,"file_name")."</td>";
					echo "<td>".mysql_result($rs2,$j,"file_path")."</td>";
					echo "<td></td>";
					//echo "<td>".mysql_result($rs3,$i,"platform_name")."</td>";
					//echo "<td>".mysql_result($rs4,0,"dte_update")."</td>";
					echo "</tr>";
					}
				}
			  ?>
	    </table>
		</div>

	</div>
	
	<h2 class="acc_trigger" width="100%"><a href="#">Failed</a></h2>
	<div class="acc_container" width="100%">
	  <div class="block" width="100%">
		<h3>Failed</h3>
		  <p>&nbsp;</p>
		  
		  <table width="100%" height="237" border="1">
		  <thead>
      <tr>
        <th class="video">Video</th>
		<th class="videoSource">Video Source Path</th>
        <th class="sourceFormat">Source Format</th>
		<th class="destinationFormat">Destination Format</th>
		<th class="timneToFinishTime">Reason for failing</th>
        </tr>
      </thead>
		  <?php
				$rs = mysql_query("select * from vt_process_master where status_id = 6 or status_id = 2;")//For processed videos
				or die ("Unable to complete in body of processed videos ".mysql_error() );
				
				$num_of_rows = mysql_numrows($rs);
				
				for($i = 0; $i < $num_of_rows; $i++)
				{
					$rs2 = mysql_query("select file_name,file_path from vt_inputdata_master where file_id = ".mysql_result($rs,$i,"queued_file_id").";")//For inprocess videos
					or die ("Unable to complete in body of processed videos 1".mysql_error() );
					$num_of_rows2 = mysql_numrows($rs2);
					//$rs3 = mysql_query("select platform_name from vt_platform_lookup where platform_id = (select platform_id from vt_format_lookup where format_id = (select op_format_id from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."));")
					//or die ("Unable to complete in body of processed videos 2".mysql_error() );
					//$rs4 = mysql_query("select dte_update from vt_process_master where queued_file_id = ".mysql_result($rs,$i,"queued_file_id")."")//For inprocess videos
					//or die ("Unable to complete in body of processed videos 3".mysql_error() );
					for($j = 0; $j < $num_of_rows2; $j++)
					{
					echo "<tr>";
					echo "<td>".mysql_result($rs2,$j,"file_name")."</td>";
					echo "<td>".mysql_result($rs2,$j,"file_path")."</td>";
					echo "<td></td>";
					//echo "<td>".mysql_result($rs3,$i,"platform_name")."</td>";
					//echo "<td>".mysql_result($rs4,0,"dte_update")."</td>";
					echo "</tr>";
					}
					

				}
			  ?>
	    </table>
		  
		</div>
	</div>
  </div>
  </div>

		<div class="footer">
		

		</div>

</div>

</body>

</html>