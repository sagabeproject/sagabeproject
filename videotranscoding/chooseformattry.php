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

<script>
<?php

$i = 0;
$v = 0;
$len=0;
$fn_arr=array();
$fp_arr=array();
$file_format_arr=array();
$video_path = array();
$len_vid_array = 0;

foreach ($_POST['video'] as $vid) 
{
    $videopath[$len_vid_array++] = $vid;
	$v++;
	
	$path_parts = pathinfo($vid);
	$video_path[$len] = $vid;
	
	$len++;
	
}
echo $len;
$video_path = array_unique($video_path);

for($j = 0; $j < count($video_path); $j++)
{
	$path_parts = pathinfo($video_path[$j]);
	$fp_arr[$j] = $path_parts['dirname'];
	$fn_arr[$j] = $path_parts['filename'];
	$file_format_arr[$j] = $path_parts['extension'];
}

$len = count($video_path);

echo $len;

?>
</script>

<script type = "text/javascript">
/*function checkUncheck(quality)
{
	for (i = 0; i < quality.length; i++)
		quality[i].checked = true ;
}

function uncheckAll(quality)
{
	quality[0].checked = false;
}*/


var checked=1;

function allcheck(j)
{

if(checked==1)
{
var c=0;
for(c=1;c<=9;c++)
{
document.getElementById('l'+j+'c'+c).checked=true;
}
checked=checked*(-1);
}
else
{
var c=0;
for(c=1;c<=9;c++)
{
document.getElementById('l'+j+'c'+c).checked=false;
}
checked=checked*(-1);

}
}
function func()
{
<?php
for($i=0;$i<$len;$i++)
{
?>

var fname = document.getElementById("l"+<?php echo $i; ?> ).title;

alert(fname);

var fpath="<?php echo $fp_arr[$i]; ?>";
alert(fpath);
//var fpath="D:\\Movies";
var src="<?php echo $file_format_arr[$i]; ?>";	//to be changed w.r.t format selected in this form

alert(src);

var j;
var formats="";
for(j = 1; j <= 12;j++)
{

if(document.getElementById("l"+<?php echo $i; ?>+"c"+j).checked)
{
formats=formats+j+" ";
}
}
var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
alert(formats);
var query="?fname="+fname+"&fpath="+fpath+"&src="+src+"&opid="+formats;
xmlhttp1.open("GET","insertvideodata.php"+query,true);
xmlhttp1.send(null);

<?php
}
?>

 window.location = "Monitor.php"

}
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
			<div id="length" title="<?php echo $len;?>"/>
			<form name = "chooseFormatForm">
			<?php
				for($j=0; $j<$len; $j++)
				{
			?>
			<table>
				<tr>
					<label id="l<?php echo $j; ?>" title="<?php echo $fn_arr[$j]; ?>"><?php echo $fn_arr[$j]; ?></label>
				</tr>
				
					<td>
					<input type="checkbox" id="c" value="ab" name ="all" onClick = "allcheck(<?php echo $j?>)">All</input>						
					</td>
					<br/>
					<tr>
					<td>
						Android
					</td>
					
						<td><input type="checkbox" id="l<?php echo $j; ?>c10" value="a0" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android" onClick = "checkUncheck(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android)">All</input></td -->
						<td><input type="checkbox" id="l<?php echo $j; ?>c1" value="a1" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android)">Low Quality</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c2" value="a2" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android)">Medium Quality</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c3" value="a3" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_android)">High Quality</input></td>
					
				</tr>

				<tr>
					<td>
						Blackberry
					</td>
					
						<td><input type="checkbox" id="l<?php echo $j; ?>c11" value="b0" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb" onClick = "checkUncheck(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb)">All</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c4" value="b1" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb)">Low Quality</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c5" value="b2" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb)">Medium Quality</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c6" value="b3" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb"  onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_bb)">High Quality</input></td>
					
				</tr>

				<tr>
					<td>
						PC
					</td>
					
						<td><input type="checkbox" id="l<?php echo $j; ?>c12" value="p0" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc" onClick = "checkUncheck(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc)">All</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c7" value="p1" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc"onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc)">Low Quality</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c8" value="p2" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc)">Medium Quality</input></td>
						<td><input type="checkbox" id="l<?php echo $j; ?>c9" value="p3" name = "<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc" onClick = "uncheckAll(document.chooseFormatForm.<?php echo $fn_arr[$j]."_".$file_format_arr[$j]; ?>_pc)">High Quality</input></td>
					
				</tr>
			</table>


			<?php
				}
			?>
			<input type="button" name="Submit" value="Submit" onClick = "func()"></input>
			</form>
		</div>
	</div>
	
	
</div>
		<div class="footer">
		

		</div>



</body>

</html>