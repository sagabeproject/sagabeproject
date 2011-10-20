<html>
<head>
<script type="text/javascript">

function yo()
{
//if(document.getElementById('a1').checked)
//{
var i;
for(i=0;i<3;i++)
{
var fname=document.getElementById('l'+i).value;
alert(document.getElementById('l'+i).value);
var fpath="ajsdbjhasbd";
var src="kjansdkj";
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
xmlhttp.open("GET","insertvideodata.php?q="+query,true);
xmlhttp.send(null);
//}

}
}
</script>
</head>
<body>

<?php
$i=3;
$arr=array("ashish", "aadish", "gautam");
for($j=0; $j<$i; $j++)
{
?>
<div id="ash"></div>
<table>
<tr>
<label id="l<?php echo $j; ?>" value="<?php echo $arr[$j]; ?>"><?php echo $arr[$j]; ?></label>
</tr>
<tr>
<td>
Android
</td>
<td>
<input type="checkbox" id="c1" value="a1">Low Quality</input>
<input type="checkbox" id="c2" value="a2">Medium Quality</input>
<input type="checkbox" id="c3" value="a3">High Quality</input>
</td>
</tr>

<tr>
<td>
Blackberry
</td>
<td>
<input type="checkbox" id="c4" value="b1">Low Quality</input>
<input type="checkbox" id="c5" value="b2">Medium Quality</input>
<input type="checkbox" id="c6" value="b3">High Quality</input>
</td>
</tr>

<tr>
<td>
PC
</td>
<td>
<input type="checkbox" id="c7" value="p1">Low Quality</input>
<input type="checkbox" id="c8" value="p2">Medium Quality</input>
<input type="checkbox" id="c9" value="p3">High Quality</input>
</td>
</tr>
</table>


<?php
}
?>
<input type="button" name="asda" value="asdasd" onClick='yo()'></input>
</body>
</html>