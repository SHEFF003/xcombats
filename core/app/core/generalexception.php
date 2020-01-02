<?php

namespace Core;
class GeneralException extends \Exception {

	public function __construct($message, $code = 0) {
		$s = '';
		$s .= 'Server date & time: ' . date('d.m.Y H:i:s') . "\n";
		$s .= 'Error code: ' . $code . "\n";
		$s .= 'Error message: ' . $message . "\n";
		$s .= 'In file: ' . $this->getFile() . "\n";
		$s .= 'In line: ' . $this->getLine() . "\n";
		$s .= 'Client IP: ' . $_SERVER['REMOTE_ADDR'] . "\n";
		$s .= 'GET Data: ' . serialize($_GET) . "\n";
		$s .= 'POST Data: ' . serialize($_POST) . "\n";
		$s .= 'Call Stack Trace: ' . "\n";
		foreach ( debug_backtrace() as $stack ) {
			$s .= "\tFILE: '" . $stack['file'] . "', LINE: '" . $stack['line'] . "';\n";
		}
		file_put_contents(PROJECT_PATH . DS . 'log/error.log', $s . "\n", FILE_APPEND);
	}
}

?>