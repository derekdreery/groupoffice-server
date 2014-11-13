<?php

namespace Intermesh\Modules\Email\Imap;

use DateTime;
use Intermesh\Core\Model;
use Intermesh\Core\Util\String;


	/*
		 *  ["uid"]=>
  string(1) "4"
  ["flags"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
  ["internaldate"]=>
  string(26) "19-Sep-2014 11:33:12 +0200"
  ["rfc822.size"]=>
  string(4) "2540"
  ["date"]=>
  string(38) "Fri, 19 Sep 2014 11:33:12 +0200 (CEST)"
  ["from"]=>
  string(55) "MAILER-DAEMON@mail.intermesh.dev (Mail Delivery System)"
  ["subject"]=>
  string(35) "Undelivered Mail Returned to Sender"
  ["to"]=>
  string(19) "admin@intermesh.dev"
  ["content_type"]=>
  string(46) "multipart/report; report-type=delivery-status;"
  ["	boundary="a7c9eb20b05.1411119192/mschering_ux31a""]=>
  string(0) ""
  ["message_id"]=>
  string(44) "<20140919093312.E67F0B20BF0@mschering-UX31A>"
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
	 *
	 * @var type 
	 */
	public $from;
	
	public $subject;
	
	public $to;
	
	public $cc;
	
	public $bcc;
	
	public $replyTo;
	
	public $contentType;
	
	public $messageId;
	
	public $xPriority;
	
	public $contentTransferEncoding;
	
	public $dispositionNotificationTo;
	
	public static $mimeDecodeAttributes = ['to', 'replyTo', 'cc', 'bcc', 'dispositionNotificationTo', 'subject'];


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
					$value = new \Intermesh\Modules\Email\Util\RecipientList($value);
				}
				
				
				if($prop == 'from'){
					$list = new \Intermesh\Modules\Email\Util\RecipientList($value);
					$value = $list[0];
				}
				
				$message->$prop = $value;
			}
		}

		return $message;
	}
	
	private $_structure;
	
	/**
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
