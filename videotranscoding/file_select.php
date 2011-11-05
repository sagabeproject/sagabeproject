<html>

<head></head>


<body>


<br>
<br>
<br>

<?php

	echo "<form action='format_select.php' method='post'>" ;

	dir_contents_recursive("C:\\xampp\\htdocs\\xampp\\videotranscoding" );

	echo ' <input type="submit" name="formSubmit" value="Submit" /> ';

	echo '</form>';
	



//	array_push($filename , "file1");

function dir_contents_recursive($dir ) {

	static $i = 0;

    // open handler for the directory

	//create two arrays to store name and path of files
	$filename= array();
	$filepath= array();
	
    $iter = new DirectoryIterator($dir);

    foreach( $iter as $item ) {
        // make sure you don't try to access the current dir or the parent
        if ($item != '.' && $item != '..') {
                if( $item->isDir() ) {
                        // call the function on the folder
                        dir_contents_recursive("$dir/$item" );
                } else {
                        //insert checkbox using php
						//vid1 
    					echo '<input type="checkbox" name="chkbox'.$i.'" value="Yes" />';
						echo 'number '.$i;

						
    					echo '<input type="text" readonly="readonly" size="100" name="filepath'.$i.'" value="'. $dir . "/" .$item->getFilename().'" />';
    					echo '<input type="text" readonly="readonly" size="30" name="filename'.$i.'" value="'.$item->getFilename().'" />';

						
						// print files
//						echo '<p id="id_'.$i.'"> ';
//						echo ' </p> ';
							echo "<br>";
						$i= $i  + 1;
						array_push($filename  , $item->getFilename() );
						array_push($filepath  , $dir . "/" .$item->getFilename() );
						
                }
        }
    }
	//echo "<br>";
	//echo "<br>";
	//echo "<br>";

	echo "<br>";
	echo "<br>";
	
}




//	print_r($filename);

//	print_r($filepath);	
?>



</body>

</html>