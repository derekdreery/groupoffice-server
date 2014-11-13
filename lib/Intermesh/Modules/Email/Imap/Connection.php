<?php

namespace Intermesh\Modules\Email\Imap;

use Exception;
use Intermesh\Core\App;

/**
 * https://tools.ietf.org/html/rfc3501
 */
class Connection {

	private $handle;
	private $authenticated = false;
	private $ssl = false;
	private $server = '';
	private $port = 143;
	private $username = '';
	private $password = '';
	private $starttls = false;
	private $auth = 'plain';

	public function __construct($server, $port, $username, $password, $ssl = false, $starttls = false, $auth = 'plain') {

		$this->ssl = $ssl;
		$this->starttls = $starttls;
		$this->auth = strtolower($auth);

		$this->server = $server;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
	}

	private function getHandle() {

		if (!isset($this->handle)) {
			$server = $this->ssl ? 'ssl://' . $this->server : $this->server;
			$this->handle = fsockopen($server, $this->port, $errorno, $errorstr, 10);
		}

		if (!is_resource($this->handle)) {
			throw new Exception('Failed to open socket #' . $errorno . '. ' . $errorstr);
		}


		return $this->handle;
	}

	public function connect() {

		$this->getHandle();

		return true;
	}
	
	public function isAuthenticated(){
		return $this->authenticated;
	}

	/**
	 * Handles authentication. You can optionally set
	 * $this->starttls or $this->auth to CRAM-MD5
	 *
	 * @param <type> $username
	 * @param <type> $pass
	 * @return <type>
	 */
	public function authenticate() {

//		if ($this->starttls) {
//			$this->sendCommand("STARTTLS");
//			$response = $this->get_response();
//			if (!empty($response)) {
//				$end = array_pop($response);
//				if (substr($end, 0, strlen('A'.$this->command_count.' OK')) == 'A'.$this->command_count.' OK') {
//					stream_socket_enable_crypto($this->handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
//				}
//			}
//		}
		switch (strtolower($this->auth)) {
//			case 'cram-md5':
//				$this->banner = fgets($this->handle, 1024);
//				$cram1 = 'A'.$this->command_number().' AUTHENTICATE CRAM-MD5'."\r\n";
//				fputs ($this->handle, $cram1);
//				$this->commands[trim($cram1)] = \GO\Base\Util\Date::getmicrotime();
//				$response = fgets($this->handle, 1024);
//				$this->responses[] = $response;
//				$challenge = base64_decode(substr(trim($response), 1));
//				$pass .= str_repeat(chr(0x00), (64-strlen($pass)));
//				$ipad = str_repeat(chr(0x36), 64);
//				$opad = str_repeat(chr(0x5c), 64);
//				$digest = bin2hex(pack("H*", md5(($pass ^ $opad).pack("H*", md5(($pass ^ $ipad).$challenge)))));
//				$challenge_response = base64_encode($username.' '.$digest);
//				$this->commands[trim($challenge_response)] = \GO\Base\Util\Date::getmicrotime();
//				fputs($this->handle, $challenge_response."\r\n");
//				break;
			default:

				$this->sendCommand('LOGIN "' . Utils::escape($this->username) . '" "' . Utils::escape($this->password) . '"');

				break;
		}

		$response = $this->getResponse();
//		var_dump($response);
		//returns A1 OK lastly on success
		$this->authenticated = $this->lastCommandSuccessful;
		
		
		if($this->authenticated){
			
			$lastLine = array_pop($response[0]);
		
			if(($startpos = strpos($lastLine, 'CAPABILITY'))!==false){
				App::debug("Use capability from login", "imap");					
				$endpos=  strpos($lastLine, ']', $startpos);
				if($endpos){
					$this->_capability = substr($lastLine, $startpos, $endpos-$startpos);
					
				}

			}
		}

		

		return $this->authenticated;
	}
	
	private $_capability;
	
	
	/**
	 * Get's the capabilities of the IMAP server. Useful to determine if the
	 * IMAP server supports server side sorting.
	 *
	 * @return <type>
	 */

	public function getCapability() {
		//Cache capability in the session so this command is not used repeatedly
	
		if(!isset($this->_capability)){			
			$this->sendCommand("CAPABILITY");
			$response = $this->getResponse();
			
			$this->_capability = implode(' ', $response);
		}		
		
		return $this->capability;
	}

	/**
	 * Check if the IMAP server has a particular capability.
	 * eg. QUOTA, ACL, LIST-EXTENDED etc.
	 *
	 * @param string $str
	 * @return boolean
	 */
	public function hasCapability($str){
		return stripos($this->getCapability(), $str)!==false;
	}
	
	

	public function sendCommand($command) {
		$handle = $this->getHandle();

		$command = 'A' . $this->commandNumber() . ' ' . $command . "\r\n";

		App::debug('> ' . $command, 'imap');

		if (!fputs($handle, $command)) {
			throw new \Exception("Lost connection to " . $this->server);
		}
	}
	
	public $lastCommandSuccessful = false;
	
	
	
	public function readLine($length = 8192){
		$line = fgets($this->getHandle(), $length);

		App::debug('< ' . $line, 'imap');	
		
		return $line;
	}

	/**
	 * Returns text response in array
	 * 
	 * @return array
	 */
	public function getResponse(Streamer $streamer = null) {

		$response = [];

		

		do {
			$chunk = trim($this->readLine());

			
//			echo $chunk."\n";
			
			if(substr($chunk, 0, 1) === '*'){
				
				if(isset($data)){
					$response[] = $data;
				}
				
				$data = [substr($chunk, 2)];
			}else
			{
				$data[] = $chunk;
			}			
			
			//check for literal {<SIZE>}
			if(substr($chunk,-1,1) == '}'){
				$startpos = strrpos($chunk, '{');

				if($startpos){
					$size = substr($chunk, $startpos+1, -1);						
					$data[] = $this->getLiteralDataResponse($size, $streamer);
				}
			}
			
		} while (substr($chunk, 0, strlen('A' . $this->commandCount)) !== 'A' . $this->commandCount);
		
		if(isset($data)){
			$response[] = $data;
		}
		
		if(stripos($chunk, 'A' . $this->commandCount . ' OK') !== false){
			$this->lastCommandSuccessful = true;
			
			//remove response line
//			array_pop($response);
		}else
		{
			$this->lastCommandSuccessful = false;
		}

		return $response;
	}
	

	public function getLiteralDataResponse($size, Streamer $streamer = null) {
		$max = 8192 > $size ? $size : 8192;
		
		$readLength = 0;
		$data = "";
		do{
			
			$line = $this->readLine($max);
			
			$readLength += strlen($line);
			
			if(isset($streamer)){
				$streamer->put($line);
			}else
			{			
				$data .= $line;
			}
			
		}while ($readLength < $size);			
	
		if(isset($streamer)){
			$streamer->finish();
			return null;
		}else
		{
			return $data;
		}
	}

	private $commandCount = 0;

	private function commandNumber() {
		$this->commandCount++;
		return $this->commandCount;
	}

}
