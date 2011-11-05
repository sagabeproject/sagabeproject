<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

@error_reporting(E_WARNING);
@set_time_limit(6000);
@ini_set('session.gc_maxlifetime', 6000);
@ini_set('include_path', @ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__));
if(!session_id()) @session_start();

/* global constants */
define('FM_EXT_DELETED', 'deleted');
define('FM_CACHE_DAYS', 7);

include_once('FM_Listing.php');
include_once('FM_Event.php');
include_once('FM_Log.php');
include_once('FM_Tools.php');

/* check memory limit */
$memoryLimit = @ini_get('memory_limit');
if($memoryLimit && FM_Tools::toBytes($memoryLimit) < 134217728) {
	@ini_set('memory_limit', '128M');
}

/**
 * This is the main class.
 */
class FileManager {

/* PUBLIC PROPERTIES *************************************************************************** */

	/* configuration variables; will be filled with content from config file */

	var $ftpHost;
	var $ftpUser;
	var $ftpPassword;
	var $ftpPort;
	var $ftpPassiveMode;
	var $ftpSSL;
	var $authUser;
	var $authPassword;
	var $language;
	var $encoding;
	var $locale;
	var $startDir;
	var $startSubDirs;
	var $startSearch;
	var $fmWebPath;
	var $fmWidth;
	var $fmHeight;
	var $fmMargin;
	var $fmView;
	var $fmPrefix;
	var $debugInfo;
	var $logHeight;
	var $logSave;
	var $explorerWidth;
	var $explorerExpandAll;
	var $enableImagePreview;
	var $enableImageRotation;
	var $thumbMaxWidth;
	var $thumbMaxHeight;
	var $thumbSharpen;
	var $enableMediaPlayer;
	var $mediaPlayerWidth;
	var $mediaPlayerHeight;
	var $enableDocViewer;
	var $docViewerWidth;
	var $docViewerHeight;
	var $publicUrl;
	var $enableId3Tags;
	var $defaultFilePermissions;
	var $defaultDirPermissions;
	var $allowFileTypes;
	var $hideFileTypes;
	var $hideDirNames;
	var $hideSystemFiles;
	var $hideSystemType;
	var $hideFilePath;
	var $hideLinkTarget;
	var $hideDisabledIcons;
	var $hideColumns;
	var $markNew;
	var $uploadEngine;
	var $enableUpload;
	var $enableDownload;
	var $enableEdit;
	var $enableDelete;
	var $enableRestore;
	var $enableRename;
	var $enablePermissions;
	var $enableMove;
	var $enableCopy;
	var $enableNewDir;
	var $enableSearch;
	var $createBackups;
	var $replSpacesUpload;
	var $replSpacesDownload;
	var $lowerCaseUpload;
	var $lowerCaseDownload;
	var $maxImageWidth;
	var $maxImageHeight;
	var $loginPassword;
	var $mailOnUpload;
	var $mailOnDownload;
	var $uploadHook;
	var $downloadHook;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $container;

	/**
	 * holds Log object
	 *
	 * @var FM_Log
	 */
	var $Log;

	/**
	 * binary modes
	 *
	 * @var array
	 */
	var $binaryModes = array('getFile', 'loadFile', 'getThumbnail', 'getCachedImage');

/* PRIVATE PROPERTIES ************************************************************************** */

	/**
	 * file manager directory path (for includes)
	 *
	 * @var string
	 */
	var $_incPath;

	/**
	 * path to temporary directory
	 *
	 * @var string
	 */
	var $_tmpDir;

	/**
	 * holds listing object
	 *
	 * @var FM_Listing
	 */
	var $_Listing;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $_listCont;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $_logCont;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $_infoCont;

	/**
	 * user access
	 *
	 * @var boolean
	 */
	var $_access;

	/**
	 * config variables that should be converted to arrays
	 *
	 * @var array
	 */
	var $_arrays = array('startSubDirs', 'allowFileTypes', 'hideFileTypes', 'hideDirNames', 'hideColumns', 'loginPassword');

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param string $startDir		optional: directory path
	 * @return FileManager
	 */
	function FileManager($startDir = '') {
		$this->_incPath = str_replace('\\', '/', realpath(dirname(__FILE__) . '/..'));
		$this->_tmpDir = $this->_incPath . '/tmp';

		$this->initFromConfig();

		if($startDir != '') $this->startDir = $startDir;
		if($this->fmWebPath == '') $this->fmWebPath = FM_Tools::getWebPath();
		if($this->locale) @setlocale(LC_ALL, $this->locale);

		$this->_checkStartDir();
	}

	/**
	 * initialization from config file
	 */
	function initFromConfig() {
		$config = parse_ini_file($this->_incPath . '/config.inc.php');

		foreach($config as $key => $val) {
			if($key == 'defaultFilePermissions' || $key == 'defaultDirPermissions') {
				$val = octdec(preg_replace('/^0/', '', $val));
			}
			else if(in_array($key, $this->_arrays)) {
				$val = preg_split('/\s*,\s*/', $val);
				if($val[0] == '') $val = array();
			}
			$this->$key = $val;
		}
		if(!$this->ftpPort) $this->ftpPort = 21;
	}

	/**
	 * create file manager
	 *
	 * @return string	HTML code
	 */
	function create() {
		global $fmCnt;

		ob_start();

		foreach($this->_arrays as $prop) {
			if(!is_array($this->$prop)) {
				$val = preg_split('/\s*,\s*/', $this->$prop);
				if($val[0] == '') $val = array();
				$this->$prop = $val;
			}
		}
		$logDir = $this->logSave ? $this->_incPath . '/log' : '';
		$this->Log = new FM_Log($logDir);

		if(!$fmCnt) $fmCnt = 1;
		if($fmCnt == 1) {
			$fmWebPath = $this->fmWebPath;
			include_once($this->_incPath . '/template.inc.php');
			$this->_cleanTmpDirs();
		}
		$this->language = str_replace('-', '_', $this->language);
		$this->_getLanguageFile();

		$this->uploadEngine = strtolower($this->uploadEngine);

		if($this->uploadEngine == 'perl') {
			$this->_checkPerl();
		}
		else if(!in_array($this->uploadEngine, array('java', 'perl', 'php'))) {
			$this->uploadEngine = 'php';
		}
		$this->container = $this->fmPrefix . 'Cont' . $fmCnt;
		$this->_listCont = $this->container . 'List';
		$this->_logCont = $this->container . 'Log';
		$this->_infoCont = $this->container . 'Info';
		$this->_save();

		$this->_checkAccess($_COOKIE[$this->container . 'LoginPwd']);

		$this->_viewHeader();
		$this->_viewFooter();

		$fmCnt++;

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * return listing object
	 *
	 * @return FM_Listing
	 */
	function &getListing() {
		if(!$this->_Listing) {
			$this->_Listing =& new FM_Listing($this);
		}
		return $this->_Listing;
	}

	/**
	 * get user's temporary directory
	 *
	 * @param boolean $create		create directory if it doesn't exist
	 * @return string
	 */
	function getTmpDir($create = true) {
		$dir = $this->_tmpDir . '/tmp/' . $this->container . session_id();
		if($create && !is_dir($dir)) FM_Tools::makeDir($dir, $this);
		return $dir;
	}

	/**
	 * get cache directory
	 *
	 * @param boolean $create		create directory if it doesn't exist
	 * @return string
	 */
	function getCacheDir($create = true) {
		$cacheDir = $this->_tmpDir . '/cache/' . $this->container;
		if($create && !is_dir($cacheDir)) FM_Tools::makeDir($cacheDir);
		return $cacheDir;
	}

	/**
	 * get user's upload directory
	 *
	 * @param boolean $create		create directory if it doesn't exist
	 * @return string
	 */
	function getUploadDir($create = true) {
		$dir = $this->_tmpDir . '/upload/' . $this->container . session_id();
		if($create && !is_dir($dir)) FM_Tools::makeDir($dir);

		if($this->uploadEngine == 'perl') {
			$dir .= '/files';
			if($create && !is_dir($dir)) FM_Tools::makeDir($dir);
		}
		return $dir;
	}

	/**
	 * remove user's cache directory
	 */
	function removeCacheDir() {
		FM_Tools::removeDir($this->getCacheDir(false));
	}

	/**
	 * remove user's upload directory
	 */
	function removeUploadDir() {
		FM_Tools::removeDir($this->_tmpDir . '/upload/' . $this->container . session_id());
	}

	/**
	 * clean user's cache directory
	 */
	function cleanCacheDir() {
		FM_Tools::cleanDir($this->getCacheDir(false));
	}

	/**
	 * clean user's upload directory
	 */
	function cleanUploadDir() {
		FM_Tools::cleanDir($this->_tmpDir . '/upload/' . $this->container . session_id());
	}

	/**
	 * perform requested action
	 */
	function action() {
		global $msg;

		$this->_getLanguageFile();

		if(!$this->ftpHost && $this->startDir == '') {
			$t = addslashes($msg['error']);
			FileManager::error('SECURITY ALERT:<br/>Please set a start directory or an FTP server!');
		}
		$fmMode = $_REQUEST['fmMode'];
		$fmObject = $_REQUEST['fmObject'];
		$fmName = FM_Tools::utf8Decode($_REQUEST['fmName'], $this->encoding);

		/* handle special events */
		switch($fmMode) {
			case 'getUserPerms':
			case 'getThumbnail':
			case 'getCachedImage':
			case 'getFile':
			case 'getExplorer':
			case 'loadFile':
			case 'jupload':
				$this->getListing();
				$Event = new FM_Event($this);
				$Event->handle($fmMode, $fmObject, $fmName);
				exit;

			case 'login':
				$this->_checkAccess($fmName);
				if($this->startSearch != '') {
					$fmMode = 'search';
					$fmName = $this->startSearch;
				}
				else $fmMode = 'refresh';
				break;
		}
		print '{';

		$this->_checkAccess($_COOKIE[$this->container . 'LoginPwd']);

		if($this->_access) {
			$this->getListing();
			$Event = new FM_Event($this);
			$error = $Event->handle($fmMode, $fmObject, $fmName);
			if($error != '') print ",error:'" . addslashes($error) . "'";
			if($this->ftpHost) $this->_Listing->FileSystem->ftpClose();
		}
		else $this->_viewLogin();

		if($this->debugInfo) print ',' . $this->_getDebugInfo();
		if($log = $this->_getLogMessages()) print ",messages:[$log]";
		print ',end:1}';
		$this->_save();
	}

	/**
	 * send upload info e-mail
	 *
	 * @param array $uploaded
	 */
	function sendUploadInfo($uploaded) {
		$date = @date('Y-m-d H:i:s');
		$ip = $this->Log->remoteIp;
		$body = "The following files have been uploaded on $date by IP address $ip:\n\n";
		foreach($uploaded as $file) $body .= $file['path'] . ' ' . $file['size'] . " B\n";
		@mail($this->mailOnUpload, 'FileManager Upload Info', $body);
	}

	/**
	 * send download info e-mail
	 *
	 * @param string $path
	 * @param integer $size
	 */
	function sendDownloadInfo($path, $size) {
		$date = @date('Y-m-d H:i:s');
		$ip = $this->Log->remoteIp;
		$body = "The following file has been downloaded on $date by IP address $ip:\n\n";
		$body .= "$path $size B\n";
		@mail($this->mailOnDownload, 'FileManager Download Info', $body);
	}

	/**
	 * call upload hook
	 *
	 * @param string $path
	 * @param integer $size
	 * @return string
	 */
	function callUploadHook($path, $size) {
		$ip = $this->Log->remoteIp;
		$path = urlencode($path);
		$url = $this->uploadHook;
		$url .= (strstr($url, '?') ? '&' : '?') . "size=$size&ip=$ip&file=$path";
		return FM_Tools::callUrl($url, $errstr);
	}

	/**
	 * call download hook
	 *
	 * @param string $path
	 * @param integer $size
	 * @return string
	 */
	function callDownloadHook($path, $size) {
		$ip = $this->Log->remoteIp;
		$path = urlencode($path);
		$url = $this->downloadHook;
		$url .= (strstr($url, '?') ? '&' : '?') . "size=$size&ip=$ip&file=$path";
		return FM_Tools::callUrl($url, $errstr);
	}

/* PUBLIC STATIC METHODS *********************************************************************** */

	/**
	 * exit FileManager with error message
	 *
	 * @param string $msg
	 */
	function error($msg) {
		$cont = $_REQUEST['fmContainer'];
		$d = @date('Y-m-d H:i:s');
		$m = addslashes($msg);
		die("{cont:'$cont',lang:'en',title:'ERROR',error:'$m',messages:[{time:'$d',text:'$m',type:'error'}],end:1}");
	}

/* PRIVATE METHODS ***************************************************************************** */

	/**
	 * view header
	 */
	function _viewHeader() {
		global $msg;

		$listHeight = $this->fmHeight;
		if($this->logHeight > 0) $listHeight -= $this->logHeight + 9;
		if($this->debugInfo) $listHeight -= 151;

		print "<script type=\"text/javascript\">\n";
		print "fmContSettings.$this->container = {";
		print "encoding:'" . addslashes($this->encoding) . "',";
		print "sid:'" . $this->container . session_id() . "',";
		print "sort:{field:'isDir',order:'asc'},";
		print "listType:'" . addslashes($this->fmView) . "',";
		print 'markNew:' . (int) $this->markNew . ',';
		print 'thumbMaxWidth:' . (int) $this->thumbMaxWidth . ',';
		print 'thumbMaxHeight:' . (int) $this->thumbMaxHeight . ',';
		print 'mediaPlayerWidth:' . (int) $this->mediaPlayerWidth . ',';
		print 'mediaPlayerHeight:' . (int) $this->mediaPlayerHeight . ',';
		print 'docViewerWidth:' . (int) $this->docViewerWidth . ',';
		print 'docViewerHeight:' . (int) $this->docViewerHeight . ',';
		print "publicUrl:'" . addslashes($this->publicUrl) . "',";
		print 'perlEnabled:' . (int) $this->perlEnabled . "};\n";

		$m = array();
		foreach($msg as $key => $val) $m[] = "$key:'" . addslashes($val) . "'";
		print "fmMsg.$this->language = {" . join(',', $m) . "};\n";

		$url = $this->fmWebPath . '/action.php?fmContainer=' . $this->container;
		$mode = ($this->startSearch != '') ? 'search&fmName=' . addslashes($this->startSearch) : 'refresh';
		print "fmLib.getUserPerms('$url', '$mode');\n";
		print "</script>\n";

		print "<div id=\"$this->container\" class=\"fmTH1\" ";
		print "style=\"position:relative; padding:1px; width:{$this->fmWidth}px; margin:{$this->fmMargin}px\">\n";
		print "<div id=\"$this->_listCont\" class=\"fmTH2\" style=\"height:" . ($listHeight + 2) . "px\"></div>\n";
	}

	/**
	 * view footer
	 */
	function _viewFooter() {
		if($this->logHeight > 5) {
			print "<div class=\"fmLogWindow\" style=\"height:{$this->logHeight}px; margin-top:1px\">";
			print "<div id=\"$this->_logCont\" class=\"fmLogWindow\" ";
			print "style=\"height:{$this->logHeight}px; text-align:left; ";
			print 'padding-top:4px; padding-left:4px; overflow:auto">';
			print "</div></div>\n";
		}

		if($this->debugInfo) {
			print '<div class="fmTD2" style="height:150px; margin-top:1px">';
			print "<div id=\"$this->_infoCont\" class=\"fmTD2\" ";
			print "style=\"height:150px; text-align:left; overflow:auto\">\n";
			print "</div></div>\n";
		}
		print "</div>\n";
	}

	/**
	 * view login form
	 */
	function _viewLogin() {
		global $msg;

		print "title:'" . addslashes($msg['cmdLogin']) . "',cont:'$this->container',";
		print "lang:'$this->language',width:'$this->fmWidth',";
		print "login:{submit:'{$this->container}Login'}";
	}

	/**
	 * get language file
	 */
	function _getLanguageFile() {
		global $msg;

		if($this->language == '') $this->language = 'en';
		$file = $this->_incPath . '/languages/lang_' . str_replace('_', '-', $this->language) . '.inc';
		$data = FM_Tools::readLocalFile($file, $this->encoding);

		if(preg_match_all('/(\w+)\s*=\s*(.+)/', $data, $m)) {
			for($i = 0; $i < count($m[0]); $i++) {
				$key = trim($m[1][$i]);
				$val = trim($m[2][$i]);
				$msg[$key] = $val;
			}
		}
	}

	/**
	 * get debug info
	 */
	function _getDebugInfo() {
		$cookie = array();

		foreach(session_get_cookie_params() as $key => $val) {
			$cookie[] = "$key => $val";
		}
		$json =  "debug:{cookie:'" . addslashes(join('<br/>', $cookie)) . "',";
		$json .= "phpVersion:'" . phpversion() . "',";
		$json .= "memoryLimit:'" . FM_Tools::bytes2string(FM_Tools::toBytes(@ini_get('memory_limit'))) . "',";
		if(function_exists('memory_get_peak_usage')) $json .= "memoryUsage:'" . FM_Tools::bytes2string(memory_get_peak_usage(true)) . "',";
		else if(function_exists('memory_get_usage')) $json .= "memoryUsage:'" . FM_Tools::bytes2string(memory_get_usage()) . "',";
		$json .= "lang:'$this->language',encoding:'$this->encoding',";
		$json .= "locale:'$this->locale (system: " . addslashes(@setlocale(LC_ALL, '0')) . ")',";
		$json .= "uploadEngine:'" . addslashes(($this->uploadEngine == 'php') ? 'PHP' : ucfirst($this->uploadEngine)) . "',";
		$json .= "perlEnabled:'" . ($this->perlEnabled ? 'yes' : 'no') . "',";
		$json .= "webPath:'" . addslashes($this->fmWebPath) . "',";
		$json .= "startDir:'" . addslashes($this->startDir) . "',";
		$json .= 'maxImageWidth:' . (int) $this->maxImageWidth . ',';
		$json .= 'maxImageHeight:' . (int) $this->maxImageHeight . ',';
		$json .= "curDir:'" . ($this->_Listing ? addslashes($this->_Listing->curDir) : '') . "',";
		$json .= "search:'" . ($this->_Listing ? addslashes($this->_Listing->searchString) : '') . "',";
		$json .= 'cache:' . FM_Tools::getFileCount($this->getCacheDir(false)) . '}';
		return $json;
	}

	/**
	 * get log messages
	 *
	 * @return string
	 */
	function _getLogMessages() {
		if($this->logHeight > 0 && $this->_Listing) {
			return $this->Log->get();
		}
		return '';
	}

	/**
	 * save FileManager object
	 */
	function _save() {
		$_SESSION[$this->container] = serialize($this);
	}

	/**
	 * check if Perl uploader can be used
	 */
	function _checkPerl() {
		$url = $this->fmWebPath . '/cgi/check.pl';

		if($this->authUser != '') {
			$url = preg_replace('%^(https?://)(.+)$%i', '$1' . $this->authUser . ':' . $this->authPassword . '@$2', $url);
		}
		$response = FM_Tools::callUrl($url, $errstr);

		if($errstr != '') {
			$this->Log->add(trim($errstr), 'error');
		}
		else {
			list($header, $content) = explode("\r\n\r\n", $response);

			if(strlen($content) < 50 && preg_match('/^(Perl [\d\.]+)/', trim($content), $m)) {
				if($this->uploadEngine == 'perl') $this->perlEnabled = true;
				$this->Log->add("$m[1] detected", 'info');
			}
		}
	}

	/**
	 * clean temporary directories
	 */
	function _cleanTmpDirs() {
		$time1 = time() - FM_CACHE_DAYS * 86400;
		$time2 = time() - 86400;
		FM_Tools::cleanDir($this->_tmpDir . '/cache', $time1, true);
		FM_Tools::cleanDir($this->_tmpDir . '/upload', $time2, true);
		FM_Tools::cleanDir($this->_tmpDir . '/tmp', $time2, true);
	}

	/**
	 * check start directory
	 */
	function _checkStartDir() {
		if($this->startDir != '') {
			if(!$this->ftpHost) $this->startDir = realpath($this->startDir);
			$this->startDir = str_replace('\\', '/', $this->startDir);

			if($this->ftpHost) {
				$this->startDir = preg_replace('%/*\.\.%', '', $this->startDir);
				$this->startDir = preg_replace('%^/+%', '', $this->startDir);
			}
		}
		if($this->ftpHost && $this->startDir == '') $this->startDir = '/';
	}

	/**
	 * check access
	 *
	 * @param string $loginPassword
	 */
	function _checkAccess($loginPassword) {
		if(is_array($this->loginPassword)) {
			if(count($this->loginPassword) == 0) {
				$this->_access = true;
			}
			else foreach($this->loginPassword as $val) {
				list($pwd, $dir) = preg_split('/\s*::\s*/', $val);

				if($pwd == $loginPassword) {
					if($dir != '') $this->startDir = $dir;
					$this->_access = true;
					break;
				}
			}
		}
		else $this->_access = true;
	}
}

?>