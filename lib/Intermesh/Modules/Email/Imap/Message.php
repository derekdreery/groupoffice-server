<?php

namespace Intermesh\Modules\Email\Imap;

use DateTime;
use Intermesh\Core\Model;
use Intermesh\Core\Util\String;


/**
 * Message object
 * 
 * Represents an IMAP message
 *
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Message extends Model {

	/**
	 *
	 * @var Mailbox 
	 */
	public $mailbox;
	
	/**
	 * UID on IMAP server
	 * 
	 * @var String 
	 */
	public $uid;
	
	/**
	 * Flags
	 * 
	 * eg. \Seen \Recent $Forwarded
	 * 
	 * @var array 
	 */
	public $flags;
	
	/**
	 * The date it arrived on the server
	 * 
	 * @var DateTime 
	 */
	public $internaldate;
	
	/**
	 * Size in bytes
	 * 
	 * @var int 
	 */
	public $size;
	
	/**
	 * The time from the Date header field.
	 * 
	 * @var DateTime 
	 */
	public $date;
	
	/**
	 * The from address
	 * 
	 * @var \Intermesh\Modules\Email\Util\Recipient 
	 */
	public $from;
	
	/**
	 * The Subject
	 * 
	 * @var string 
	 */
	public $subject;
	
	
	/**
	 * The to recipients
	 * 
	 * @var Recipient[] 
	 */
	public $to;
	
	/**
	 * The cc recipients
	 * 
	 * @var Recipient[] 
	 */
	public $cc;
	
	/**
	 * The bcc recipients
	 * 
	 * @var Recipient[] 
	 */
	public $bcc;
	
	/**
	 * The to recipients
	 * 
	 * @var Recipient[] 
	 */
	public $replyTo;
	
	/**
	 * Content type header
	 * 
	 * eg. text/plain; charset=utf-8
	 * 
	 * @var string 
	 */
	public $contentType;
	
	/**
	 * Message-ID header
	 * 
	 * eg. <sadasdsad@domain.com>
	 * 
	 * @var string 
	 */
	public $messageId;
	
	/**
	 * Priority header
	 * 
	 * @var string 
	 */
	public $xPriority;
	
	/**
	 * Do we need this???
	 * 
	 * @var string 
	 */
	public $contentTransferEncoding;
	
	/**
	 * Send a notification to this address
	 * 
	 * @var \Intermesh\Modules\Email\Util\Recipient 
	 */
	public $dispositionNotificationTo;
	
	private static $mimeDecodeAttributes = ['to', 'replyTo', 'cc', 'bcc', 'dispositionNotificationTo', 'subject'];
	
	private $_structure;


	public function __construct(Mailbox $mailbox) {
		parent::__construct();

		$this->mailbox = $mailbox;
	}
	
	
	/**
	 * 
	 * @param Mailbox $mailbox
	 * @param array $response
	 * @return Message
	 */
	public static function createFromImapResponse(Mailbox $mailbox, array $response) {
		
		$message = new Message($mailbox);
		
		$start = strpos($response[0], 'UID');
		$end = strpos($response[0], 'BODY');

		$line = substr($response[0], $start, $end - $start - 1);

		$arr = str_getcsv($line, ' ');

		for ($i = 0, $c = count($arr); $i < $c; $i++) {
			$name = String::lowerCamelCasify($arr[$i]);
			
			if($name == 'rfc822.size'){
				$name = 'size';
			}

			$value = $arr[$i + 1];


			if (substr($value, 0, 1) == '(') {
				$values = [];

				do {

					$i++;

					$values[] = trim($arr[$i], '()');
				} while (substr($arr[$i], -1, 1) != ')' && $i < $c);


				$attr[$name] = $values;
			} else {

				$attr[$name] = $value;

				$i++;
			}
		}


		//headers

		$response[1] = str_replace("\r", "", trim($response[1]));
		$response[1] = preg_replace("/\n[\s]+/", "", $response[1]);


		$lines = explode("\n", $response[1]);

		foreach ($lines as $line) {
			$parts = explode(':', $line);

			$name = String::lowerCamelCasify(array_shift($parts));

			$attr[$name] = trim(implode(':', $parts));
		}
		
		if(isset($attr['date'])){
			
			//sometimes headers contain some extra stuff between ()
			//
			//Still needed?
			//
			//$message['date']=preg_replace('/\([^\)]*\)/','', $message['date']);
			
			$attr['date'] = new DateTime($attr['date']);
		}
		
		if(isset($attr['internaldate'])){
			$attr['internaldate'] = new DateTime($attr['internaldate']);
		}
		
		foreach($attr as $prop => $value){
			
			if(property_exists($message, $prop)){
				
				if(in_array($prop, self::$mimeDecodeAttributes)){
					$value = Utils::mimeHeaderDecode($value);
				}
				
				if($prop == 'to' || $prop == 'cc' || $prop == 'bcc'){
					$list = new \Intermesh\Modules\Email\Util\RecipientList($value);
					$value = $list->toArray();
				}
				
				
				if($prop == 'from' || $prop == 'replyTo' || $prop == 'displayNotificationTo'){
					$list = new \Intermesh\Modules\Email\Util\RecipientList($value);
					$value = $list[0];
				}
				
				$message->$prop = $value;
			}
		}

		return $message;
	}
	
	
	
	/**
	 * Get's the message structure object
	 * 
	 * @return Structure
	 */
	public function getStructure(){
		
		if(!isset($this->_structure)) {
			$this->_structure  = new Structure($this);
		}
		
		return $this->_structure;
	}
	
	
	/**
	 * Returns body in HTML
	 * 
	 * @return string
	 */
	public function getBody($asHtml = true){
		
		$parts  = $this->getStructure()->findParts('text',$asHtml ? 'html' : 'plain');
			
		
		if(empty($parts) && $asHtml){
			$parts  = $this->getStructure()->findParts('text','plain');
		}
		
		if(empty($parts)){
			return false;
		}
		
		$part = array_shift($parts);		
		
		$data = $part->getDataDecoded();
		
		if($part->subtype == 'plain' && $asHtml){
			$data = nl2br($data);
		}
		
		return $data;	
	}
	
	
	/**
	 * Get attachment parts
	 * 
	 * @return SinglePart
	 */
	public function getAttachments(){
		
		$attachments = [];
		
//		var_dump($this->getStructure()->parts);
		
		if(count($this->getStructure()->parts) == 1 && $this->getStructure()->parts[0]->type == 'multipart'){
			$parts = $this->getStructure()->parts[0]->parts;
		}else
		{
			$parts = $this->getStructure()->parts;
		}
		
		foreach($parts as $part){			
			
//			echo $part->partNumber.' -> '.$part->type."\n";
			
			if($part->partNumber != "1" && $part->type != "multipart"){
				$attachments[] = $part;
			}			
		}
		
		return $attachments;
	}	
}
