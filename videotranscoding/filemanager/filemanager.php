<?php

/**
 * +-------------------------------------------------------------------+
 * |                    F I L E M A N A G E R   (v8.29)                |
 * |                                                                   |
 * | Copyright Gerd Tentler               www.gerd-tentler.de/tools    |
 * | Created: Dec. 7, 2006                Last modified: July 11, 2011 |
 * +-------------------------------------------------------------------+
 * | This program may be used and hosted free of charge by anyone for  |
 * | personal purpose as long as this copyright notice remains intact. |
 * |                                                                   |
 * | Obtain permission before selling the code for this program or     |
 * | hosting this software on a commercial website or redistributing   |
 * | this software over the Internet or in any other medium. In all    |
 * | cases copyright must remain intact.                               |
 * +-------------------------------------------------------------------+
 */

include_once('class/FileManager.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>File Manager</title>
</head>
<body style="background-color:#F0F0F0">
<table border="0" width="100%" height="90%"><tr>
<td align="center">
<?php

$FileManager = new FileManager();
print $FileManager->create();

?>
</td>
</tr></table>
</body>
</html>