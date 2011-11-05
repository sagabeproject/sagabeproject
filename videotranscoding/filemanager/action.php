<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('class/FileManager.php');

header('X-Robots-Tag: noindex, nofollow');

$container = $_REQUEST['fmContainer'];

if($container != '' && isset($_SESSION[$container])) {
	$FileManager = unserialize($_SESSION[$container]);

	if($_REQUEST['fmMode'] == 'login' && $_REQUEST['fmName'] != '' && $_REQUEST['fmRememberPwd']) {
		$fmName = FM_Tools::utf8Decode($_REQUEST['fmName'], $FileManager->encoding);
		@setcookie($FileManager->container . 'LoginPwd', $fmName, time() + 90 * 24 * 3600);
	}

	if(!in_array($_REQUEST['fmMode'], $FileManager->binaryModes)) {
		if($FileManager->locale) @setlocale(LC_ALL, $FileManager->locale);
		header("Content-Type: text/html; charset=$FileManager->encoding");
		header('Cache-Control: private, no-cache, must-revalidate');
		header('Expires: 0');
	}

	if(function_exists('iconv_set_encoding') && $FileManager->encoding != '') {
		iconv_set_encoding('internal_encoding', $FileManager->encoding);
		iconv_set_encoding('output_encoding', $FileManager->encoding);
	}
	ob_start('ob_iconv_handler');
	$FileManager->action();
	ob_end_flush();
}
else {
	$msg = 'Cannot restore FileManager object from PHP session - ';
	if($container == '') $msg .= 'fmContainer not set!';
	else if(!session_id()) $msg .= 'could not create session!';
	else $msg .= "\$_SESSION['$container'] not found!";
	FileManager::error($msg);
}

?>