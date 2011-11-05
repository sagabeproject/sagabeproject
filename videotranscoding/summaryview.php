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

		<h3>Inprocess Videos</h3>
		<p>&nbsp;</p>
		<table width="452" height="237" border="1">
		  <thead>
      <tr>
        <th class="video">Video</th>
        <th class="cpu">Start Time</th>
        <th class="estimatedtime">Estimated time to finish</th>
        <th class="progress">Progress</th>
        </tr>
      </thead>
		  <tr>
		    <td>Video 1</td>
		    <td>13 / 01 / 2011 20:31:21</td>
		    <td>2hrs 4min</td>
		    <td>21%</td>
	      </tr>
		  <tr>
		    <td>Video 2</td>
		    <td>14 / 02 / 2011 14:21:54</td>
		    <td>3hrs 12min</td>
		    <td>2%</td>
	      </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
	    </table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
</div>
	</div>
	
	<h2 class="acc_trigger"><a href="#">Queued Videos</a></h2>
	<div class="acc_container">
	  <div class="block">

		<h3>Inprocess Videos</h3>
		<p>&nbsp;</p>
</div>
	</div>
	
	<h2 class="acc_trigger"><a href="#">Inprocess Videos</a></h2>

	<div class="acc_container">
	  <div class="block">
		<h3>Unprocessed Videos</h3>
		  <p>&nbsp;</p>
		</div>

	</div>
	
	<h2 class="acc_trigger"><a href="#">Failed</a></h2>
	<div class="acc_container">
	  <div class="block">
		<h3>Failed</h3>
		  <p>&nbsp;</p>
		</div>
	</div>
  </div>
  </div>

		<div class="footer">
		

		</div>

</div>

</body>

</html>