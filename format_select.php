<html>
<head>
<script type="text/javascript">


<?php

$i=0;
$len=0;
$fn_arr=array();
$fp_arr=array();

while($i<200) 
{
	if(isset($_POST['chkbox'.$i]) && $_POST['chkbox'.$i] == 'Yes')
	{
	    $fn_arr[$len] = $_POST['filename'.$i];
		$fp_arr[$len] = $_POST['filepath'.$i];
		$len++;
		
//		echo $fp_arr[$i];
	}
	
	$i=$i+1;
	
}
?>


function func()
{
alert("inside function func");

<?php
for($i=0;$i<$len;$i++)
{
?>

alert("sending");


var fname=document.getElementById("l"+<?php echo $i; ?> ).title;
//alert(document.getElementById("l"+i).title);

var fpath="<?php echo $fp_arr[$i]; ?>";
//var fpath="D:\Movies";
var src=".mp4";	//to be changed w.r.t format selected in this form



var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
var query="?fname="+fname+"&fpath="+fpath+"&src="+src;
xmlhttp.open("GET","insertvideodata.php"+query,true);
xmlhttp.send(null);

//}


var j;
for(j=1; j<=9;j++)
{

if(document.getElementById("l"+<?php echo $i; ?>+"c"+j).checked)
{
var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }

//alert("l"+i+"c"+j);
var fid=1;
var query="?fid="+fid+"&opid="+j;
xmlhttp1.open("GET","insertprocess.php"+query,true);
xmlhttp1.send(null);
}
}
<?php
}
?>
}
</script>
</head>
<body>

<div id="length" title="<?php echo $len;?>"/>

<?php
for($j=0; $j<$len; $j++)
{
?>
<table>
<tr>
<label id="l<?php echo $j; ?>" title="<?php echo $fn_arr[$j]; ?>"><?php echo $fn_arr[$j]; ?></label>
</tr>
<tr>
<td>
Android
</td>
<td>
<input type="checkbox" id="l<?php echo $j; ?>c1" value="a1">Low Quality</input>
<input type="checkbox" id="l<?php echo $j; ?>c2" value="a2">Medium Quality</input>
<input type="checkbox" id="l<?php echo $j; ?>c3" value="a3">High Quality</input>
</td>
</tr>

<tr>
<td>
Blackberry
</td>
<td>
<input type="checkbox" id="l<?php echo $j; ?>c4" value="b1">Low Quality</input>
<input type="checkbox" id="l<?php echo $j; ?>c5" value="b2">Medium Quality</input>
<input type="checkbox" id="l<?php echo $j; ?>c6" value="b3">High Quality</input>
</td>
</tr>

<tr>
<td>
PC
</td>
<td>
<input type="checkbox" id="l<?php echo $j; ?>c7" value="p1">Low Quality</input>
<input type="checkbox" id="l<?php echo $j; ?>c8" value="p2">Medium Quality</input>
<input type="checkbox" id="l<?php echo $j; ?>c9" value="p3">High Quality</input>
</td>
</tr>
</table>


<?php
}
?>
<input type="button" name="Submit" value="Submit" onClick='func()'></input>
</body>
</html>