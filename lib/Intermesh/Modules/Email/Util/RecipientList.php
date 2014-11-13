<?php

namespace Intermesh\Modules\Email\Util;

use ArrayAccess;
use Exception;

class RecipientList implements ArrayAccess{

	/**
	 * Pass a e-mail string like:
	 * 
	 * "Merijn Schering" <mschering@intermesh.nl>,someone@somedomain.com,Pete <pete@pete.com
	 * 
	 * @param string $emailRecipientList 
	 */
	public function __construct($emailRecipientList = '', $strict = false) {
		$this->strict = $strict;
		$this->addString($emailRecipientList);
	}

	/**
	 * Set to true if you want to throw an exception when an e-mail is invalid.
	 * @var boolean 
	 */
	public $strict = false;

	/**
	 * Check if an e-mail address is in this list
	 * 
	 * @param string $email
	 * @return int|false index of the array
	 */
	public function hasRecipient($email) {
//		return isset($this->_addresses[$email]);
		
		for($i=0,$c = count($this->_recipients); $i < $c; $i++){
			if($this->_recipients[$i]->email == $email){
				return $i;
			}
		}
		
		return false;
	}

	/**
	 * Remove a recipient from the list.
	 * 
	 * @param string $email 
	 */
	public function removeRecipient($email) {
		
		$index = $this->hasRecipient($email);
		
		if($index !== false){
			unset($this->_recipients[$index]);
		}
		
	}

	public function __toString() {
		$str = '';
		foreach ($this->_recipients as $recipient) {
			$str .= $recipient. ', ';
		}
		return rtrim($str, ', ');
	}

	/**
	 * Get an array of formatted address:
	 * 
	 * $a[] = '"John Doe" <john@domain.com>';
	 * 
	 * @return array
	 */
	public function toArray() {
		return $this->_recipients;
	}


	/**
	 * The array of parsed addresses
	 *
	 * @var     array
	 * @access  private
	 */
	private $_recipients = array();

	/**
	 * Temporary storage of personal info of an e-mail address
	 *
	 * @var     string
	 * @access  private
	 */
	private $_personal = false;

	/**
	 * Temporary storage
	 *
	 * @var     string
	 * @access  private
	 */
	private $_buffer = '';

	/**
	 * Bool to check if a string is quoted or not
	 *
	 * @var     bool
	 * @access  private
	 */
	private $_quote = false;

	/**
	 * Bool to check if we found an e-mail address
	 *
	 * @var     bool
	 * @access  private
	 */
	private $_emailFound = false;

	/**
	 * Pass a e-mail string like:
	 * 
	 * "Merijn Schering" <mschering@intermesh.nl>,someone@somedomain.com,Pete <pete@pete.com
	 * 
	 * @param string $emailRecipientList 
	 */
	public function addString($recipientListString) {
		//initiate addresses array
		//$this->_addresses = array();

		$recipientListString = trim($recipientListString, ',; ');



		for ($i = 0; $i < strlen($recipientListString); $i++) {
			$char = $recipientListString[$i];

			switch ($char) {
				case '"':
					$this->_handleQuote($char);
					break;

				case "'":
					$this->_handleQuote($char);
					break;

				case '<':
					$this->_personal = trim($this->_buffer);
					$this->_buffer = '';
					$this->_emailFound = true;
					break;

				case '>':
					//do nothing		
					break;

				case ',':
				case ';':
					if ($this->_quote || (!$this->strict && !$this->_emailFound /*&& !\GO\Base\Util\String::validate_email(trim($this->_buffer))*/)) {
						$this->_buffer .= $char;
					} else {
						$this->_addBuffer();
					}
					break;


				default:
					$this->_buffer .= $char;
					break;
			}
		}
		$this->_addBuffer();

		return $this->_recipients;
	}

	/**
	 * Adds the current buffers to the addresses array
	 *
	 * @access private
	 * @return void
	 */
	private function _addBuffer() {
		$this->_buffer = trim($this->_buffer);
		if (!empty($this->_personal) && empty($this->_buffer)) {
			$this->_buffer = 'noaddress';
		}

		if (!empty($this->_buffer)) {
			if ($this->strict && !\GO\Base\Util\String::validate_email($this->_buffer)) {
				throw new Exception("Address " . $this->_buffer . " is not valid");
			} else {				
				$this->_recipients[] = new Recipient($this->_buffer, $this->_personal);
			}
		}
		$this->_buffer = '';
		$this->_personal = false;
		$this->_emailFound = false;
		$this->_quote = false;
	}

	/**
	 * Hanldes a quote character (' or ")
	 *
	 * @access private
	 * @return void
	 */
	private function _handleQuote($char) {
		if (!$this->_quote && trim($this->_buffer) == "") {
			$this->_quote = $char;
		} elseif ($char == $this->_quote) {
			$this->_quote = false;
		} else {
			$this->_buffer .= $char;
		}
	}

//	/**
//	 * Merge two address strings
//	 * 
//	 * @param RecipientList $recipients
//	 * @return RecipientList 
//	 */
//	public function mergeWith(RecipientList $recipients) {
//		$this->_addresses = array_merge($this->_addresses, $recipients->getAddresses());
//
//		return $this;
//	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->_recipients);
	}

	public function offsetGet($offset) {
		return $this->_recipients[$offset];
	}

	public function offsetSet($offset, $value) {
		
		if(!is_string($value)){
			return false;
		}
			
		$recipients = new RecipientList($value);
		
		$this->_recipients[$offset] = $recipients[0];
	}

	public function offsetUnset($offset) {
		unset($this->_recipients[$offset]);
	}

}
