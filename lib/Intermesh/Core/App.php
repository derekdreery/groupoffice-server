<?php

namespace Intermesh\Core;

use Intermesh\Core\Cache\Disk;
use Intermesh\Core\Db\Connection;
use Intermesh\Core\Http\Request;
use Intermesh\Core\Http\Router;

/**
 * App singleton class with services
 * 
 * The App class is a collection of static functions to access common services
 * like the configuration, request, debugger etc.
 * 
 * <p>Example:</p>
 * <code>
 * App::config()->getTempFolder();
 * </code>
 */
class App {

	private static $cache = array();

	/**
	 * Initializes the framework.
	 * 
	 * Set's custom error handling and configures the framework.
	 * 
	 * @param array $config Config object with properties per class name.
	 * 
	 * <p>Example:</p>
	 * <code>
	 * array(
	 * 			
	 * 			'Intermesh\Core\Config'=>array(
	 * 				'productName'=>'Intermesh Application'	
	 * 			),
	 * 			
	 * 			'Intermesh\Core\Debugger'=>array(
	 * 				'enabled'=>true	
	 * 			),
	 * 			
	 * 			'Intermesh\Core\Db\Connection'=>array(
	 * 					'user'=>'root',
	 * 					'port'=>3306,
	 * 					'pass'=>'',
	 * 					'database'=>'intermesh',
	 * 					'host'=>'localhost',
	 * 			),
	 * 			
	 * 			'Intermesh\Core\Fs\File'=>array(
	 * 					'createMode'=>0644
	 * 			),
	 * 			
	 * 			'Intermesh\Core\Fs\Folder'=>array(
	 * 					'createMode'=>0755
	 * 			)
	 * 			
	 * 	))
	 * </code>
	 * 
	 */
	public static function init(array $config) {

		//register our custom error handler here
		error_reporting(E_ALL | E_STRICT);
//		ini_set('display_errors', 'on');
		set_error_handler(array('\Intermesh\Core\App', 'errorHandler'));
		register_shutdown_function(array('\Intermesh\Core\App', 'shutdown'));

		

		App::config()->setConfig($config);
	}

	private static $_lastReportedError;

	/**
	 * Called when PHP exits.
	 */
	public static function shutdown() {

		$error = error_get_last();
		if ($error) {
			//Log only fatal errors because other errors should have been logged by the normal error handler
			if ($error['type'] == E_ERROR || $error['type'] == E_CORE_ERROR || $error['type'] == E_COMPILE_ERROR || $error['type'] == E_RECOVERABLE_ERROR)
				self::errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
		}
	}

	/**
	 * Custom error handler that logs to our own error log
	 * 
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @return boolean
	 */
	public static function errorHandler($errno, $errstr, $errfile, $errline) {

		//prevent that the shutdown function will log this error again.
		if (self::$_lastReportedError == $errno . $errfile . $errline)
			return;

		self::$_lastReportedError = $errno . $errfile . $errline;

		//log only errors that are in error_reporting
		$error_reporting = ini_get('error_reporting');
		if (!($error_reporting & $errno))
			return;

		$type = "Unknown error";

		switch ($errno) {
			case E_ERROR:
			case E_USER_ERROR:
				$type = 'Fatal error';
				break;

			case E_WARNING:
			case E_USER_WARNING:
				$type = 'Warning';
				break;

			case E_NOTICE:
			case E_USER_NOTICE:
				$type = 'Notice';
				break;
		}

		$errorMsg = "[" . @date("Ymd H:i:s") . "] PHP $type: $errstr in $errfile on line $errline";

//		$user = App::session()->user() ? App::session()->user()->username : 'notloggedin';
		$user = 'TODO';
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';

		$errorMsg .= "\nUser: " . $user . " Agent: " . $agent . " IP: " . $ip . "\n";

		if (isset($_SERVER['QUERY_STRING']))
			$errorMsg .= "Query: " . $_SERVER['QUERY_STRING'] . "\n";


		$backtrace = debug_backtrace();
		array_shift($backtrace); //first item is this function which we don't have to see

		$errorMsg .= "Backtrace:\n";
		foreach ($backtrace as $o) {

			if (!isset($o['class']))
				$o['class'] = 'global';

			if (!isset($o['function']))
				$o['function'] = 'global';

			if (!isset($o['file']))
				$o['file'] = 'unknown';

			if (!isset($o['line']))
				$o['line'] = 'unknown';

			$errorMsg .= $o['class'] . '::' . $o['function'] . ' in file ' . $o['file'] . ' on line ' . $o['line'] . "\n";
		}
		$errorMsg .= "----------------";

		self::debug($errorMsg, 'errors');
//		\GO::logError($errorMsg);	
//		foreach(self::$_errorLogCallbacks as $callback){
//			call_user_func($callback, $errorMsg);
//		}

		if (self::debugger()->enabled) {
			echo $errorMsg;
		}
		
//		throw new \Exception($errorMessage);

		/* Execute PHP internal error handler too */
		return false;
	}

	/**
	 * Routes requests
	 * 
	 * @return Router
	 */
	public static function router() {

		if (!isset(self::$cache['Router'])) {
			self::$cache['Router'] = new Router();
		}

		return self::$cache['Router'];
	}

	/**
	 * Get the session
	 * 
	 * @return Session
	 */
	public static function session() {
		if (!isset(self::$cache['session'])) {
			self::$cache['session'] = new Session();
		}

		return self::$cache['session'];
	}

	/**
	 * Get the JSON Request object
	 * 
	 * @return Request
	 */
	public static function request() {
		if (!isset(self::$cache['Request'])) {
			self::$cache['Request'] = new Request();
		}

		return self::$cache['Request'];
	}

	/**
	 * Get the Group-Office configuration
	 * 
	 * @return Config
	 */
	public static function config() {

		if (!isset(self::$cache['Config'])) {
			self::$cache['Config'] = new Config();
		}

		return self::$cache['Config'];
	}

	/**
	 * Get the server information class
	 * 
	 * @return Server
	 */
	public static function server() {
		if (!isset(self::$cache['Server'])) {
			self::$cache['Server'] = new Server();
		}

		return self::$cache['Server'];
	}

	/**
	 * Get a simple key value caching object
	 * 
	 * @return Disk
	 */
	public static function cache() {
		if (!isset(self::$cache['Cache'])) {
			self::$cache['Cache'] = new Disk();
		}

		return self::$cache['Cache'];
	}

	/**
	 * Get the database connection
	 * 
	 * @return Connection
	 */
	public static function dbConnection() {
		if (!isset(self::$cache['dbConnection'])) {
			self::$cache['dbConnection'] = new Connection();
		}

		return self::$cache['dbConnection'];
	}

	/**
	 * Get a simple key value caching object
	 * 
	 * @return Debugger
	 */
	public static function debugger() {
		if (!isset(self::$cache['debugger'])) {
			self::$cache['debugger'] = new Debugger();
		}

		return self::$cache['debugger'];
	}

	/**
	 * Add debug output
	 * 
	 * @param string $str
	 */
	public static function debug($str, $section = 'general') {

		if (App::debugger()->enabled) {
			self::debugger()->debug($str, $section);
		}
	}

}
