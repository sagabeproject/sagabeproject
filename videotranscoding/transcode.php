<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/> 
<meta name="author" content="author"/> 
<link rel="stylesheet" type="text/css" href="default.css" media="screen"/>
<title>Mime-360 : Video Transcoding Monitor</title>

<style type="text/css">
			BODY,
			HTML {
				padding: 0px;
				margin: 0px;
			}
			BODY {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				background: #EEE;
				padding: 15px;
			}
			
			H1 {
				font-family: Georgia, serif;
				font-size: 20px;
				font-weight: normal;
			}
			
			H2 {
				font-family: Georgia, serif;
				font-size: 16px;
				font-weight: normal;
				margin: 0px 0px 10px 0px;
			}
			
			.example {
				float: left;
				margin: 15px;
			}
			
			.demo {
				width: 200px;
				height: 400px;
				border-top: solid 1px #BBB;
				border-left: solid 1px #BBB;
				border-bottom: solid 1px #FFF;
				border-right: solid 1px #FFF;
				background: #FFF;
				overflow: scroll;
				padding: 5px;
			}
			
			P.note {
				color: #999;
				clear: both;
			}
		</style>
		
		<link rel="stylesheet" type="text/css" href="default.css" media="screen"/>
		<script src="jquery.js" type="text/javascript"></script>

		<script src="jquery.easing.js" type="text/javascript"></script>
		<script src="jqueryFileTree.js" type="text/javascript"></script>
		<link href="jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
		
		<script type="text/javascript">
			var vid = 0;
			var labelvid = 0;
			$(document).ready( function() {
				
				$('#fileTreeDemo_1').fileTree({ root: 'C:\\', script: 'jqueryFileTree.php' }, function(file) { 
					//addCheckbox($('#txtName').val());
					addCheckbox(file);
				});
				
				$('#fileTreeDemo_2').fileTree({ root: 'C:\\', script: 'jqueryFileTree.php', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, multiFolder: false }, function(file) { 
					alert(file);
				});
				
				$('#fileTreeDemo_3').fileTree({ root: 'C:\\', script: 'jqueryFileTree.php', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Un momento...' }, function(file) { 
					alert(file);
				});
				
				$('#fileTreeDemo_4').fileTree({ root: 'C:\\', script: 'jqueryFileTree.php', folderEvent: 'dblclick', expandSpeed: 1, collapseSpeed: 1 }, function(file) { 
					alert(file);
				});
				
			});
			
			function addCheckbox(name) {
				var container = $('#videoToBeTranscoded');
				var inputs = container.find('input');
				var id = inputs.length+1;
				

				var html = '<tr><td><input type="checkbox" name = "video[]" value="'+name+'" id = vid'+(vid++)+' checked = true/> <label id="labelvid'+(labelvid++)+'" for="cb'+id+'">'+name+'</label></td></tr>';
				container.append($(html));
}

			function clearVideos(){
			
			
			//var videoArray = document.selectedVideos.elements["video"];
			var videoArray = document.getElementsByName("video[]");
			
			for(var i=0; i<videoArray.length; i++){
				if(document.getElementById("vid"+i).checked == false)
				{
					document.getElementById("vid"+i).style.display = "none";
					document.getElementById("labelvid"+i).style.display = "none";
					
					
				}
			}
				/*for (i=0;i<document.selectedVideos.elements.length;i++)
				{
					if (document.selectedVideos.elements[i].name == 'video[]' && document.selectedVideos.elements[i].checked == false)
					{
						document.selectedVideos.elements[i].style.display = "none";
					}
				}*/
			}
			
			function validate() {
			var videoArray = document.getElementsByName("video[]");
			if(videoArray.length == 0) {
				alert("No videos selected. Select video(s) from the list.");
				return false;
			}
			}

		</script>
		<Style>
		a.button {
			display: block;
			width: 100px;
			height: 20px;
			padding: 15px 20px 10px 45px;
			color:#666666;
			text-decoration: none;
		}
		
		a.button:hover {
			color:#333333;
			background:url no-repeat 0px -45px;
		}
		

		</style>

</head>

<body>

<div class="container">

	<div class="navigation">

		<div class="title">
			<h1>Mime360</h1>
			<h2>- Video Transcoding Dashboard -</h2>
		</div>
		
		<a href="index.html">Home</a>
		<a href="Monitor.php">Monitor</a>
		<a href="summaryview.php">Summary View</a>
        <a href="transcode.php">Transcode</a>
		<div class="clearer"><span></span></div>

	</div>

	<div class="holder_top"></div>

	<div class="holder">

		<h1>Select Video to be Transcoded</h1>
        <form name = "selectedVideos" action="chooseformattry.php" method="post" onSubmit = "return validate()">

        <table width="100%">
        	<tr>
        		<td>
					<div id = "fileTreeDemo_1" class = "demo"></div>
       			</td>
        		<td>
        	        <h1>The videos selected for transcoding are :</h1>
        			<div id = "videoToBeTranscoded" class = "videoToBeTranscoded"></div>
          
        		</td>
        	</tr>
        </table>
        
        <table width="100%">
		<tr>
        <td><button type="button" name="Clear Unselected" id="Clear Unselected" value="Clear Unselected" onClick="clearVideos()" class="button" no-repeat 0px 0px;>Clear Unselected</button></td>
		<td><button type = "submit" value = "OK" align = "right">OK</td>
		</tr>
		</table>
        </form>

	</div>

	<div class="footer">
	

	</div>

</div>

</body>

</html>