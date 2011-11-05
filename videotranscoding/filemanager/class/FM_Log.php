<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('FM_Tools.php');

/**
 * This class handles log messages.
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class FM_Log {

/* PUBLIC PROPERTIES *************************************************************************** */

	/**
	 * remote IP address
	 *
	 * @var string
	 */
	var $remoteIp;

/* PRIVATE PROPERTIES ************************************************************************** */

	/**
	 * log messages
	 *
	 * @var array
	 */
	var $_messages;

	/**
	 * path to log file directory
	 *
	 * @var string
	 */
	var $_logDir;

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param string $logDir	optional: path to log file directory
	 * @return FM_Log
	 */
	function FM_Log($logDir = '') {
		$this->_messages = array();
		$this->_logDir = $logDir;
		$this->remoteIp = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : 'n/a';
	}

	/**
	 * get log messages
	 *
	 * @return string
	 */
	function get() {
		$log = join(',', $this->_messages);
		$this->_messages = array();
		return $log;
	}

	/**
	 * add log message
	 *
	 * @param string $text		message text
	 * @param string $type		optional: message type
	 */
	function add($text, $type = '') {
		switch(strtolower($type)) {
			case 'info': break;
			case 'error': break;
			default: $type = 'default';
		}
		$time = @date('Y-m-d H:i:s');
		$ip = $this->remoteIp;
		$text = addslashes($text);
		$this->_messages[] = "{type:'$type',time:'$time',text:'$text'}";

		if($this->_logDir != '' && $type != 'info') {
			$file = $this->_logDir . '/' . @date('Y-m-d') . '.log';
			$line = sprintf("%s  %s  %s\n", $time, $ip, $text);
			$ok = FM_Tools::saveLocalFile($file, $line, '', true);
			if(!$ok) $this->_messages[] = "{type:'error',time:'$time',ip:'$ip',text:'Could not write to logfile'}";
		}
	}
}

?>