<?php

namespace Intermesh\Modules\Imap\Model;

use DateTime;
use DateTimeZone;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Core\Util\String;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Contacts\Model\Contact;
use Intermesh\Modules\Timeline\Model\Item;
use MimeMailParser\Parser;

/**
 * The Message model
 *
 * @property int $id
 * @property int $ownerUserId
 * @property User $owner
 * @property string $date
 * @property string $subject
 * @property array $from
 * @property array $to
 * @property array $cc
 * @property string $body
 * @property string $contentType
 * @property string $messageId
 *
 * 
 * @property Item[] $timelineItems
 * @property Attachment[] $attachments
 * @property Message[] $references
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Message extends AbstractRecord {
	
	protected static function defineRelations(RelationFactory $r) {
		return [
			$r->hasMany('timelineItems', Item::className(), 'imapMessageId', 'threadId'),
			$r->belongsTo('owner', User::className(), 'ownerUserId'),
			$r->hasMany('attachments', Attachment::className(), 'messageId'),
			$r->hasMany('references', Message::className(), 'threadId', 'threadId'),
		];
	}
	
	public function save() {
		if(parent::save()){
			
			if($this->threadId === null){
				$this->threadId = $this->id;
				
				$this->dbUpdate();
			}
			
			return true;
		}else
		{
			return false;
		}
	}
	
	public function getHtml(){
		
		if($this->contentType == 'text/html'){
			
			$html = $this->body;
			
			$inlineAttachments = $this->attachments(['AND','!=', ['contentId' => null]]);

			foreach($inlineAttachments as $attachment){
				$html = str_replace('cid:'.trim($attachment->contentId,'<>'), $attachment->getUrl(), $html);
			}
		}else
		{
			$html = String::textToHtml($this->body);
		}
		
		return $html;		
	
		
	}
	
	public function checkReadPermission(){
		$timelineItems = $this->timelineItems;
		
		foreach($timelineItems as $item){
		
			if($item->contact->checkPermission('readAccess')){
				return true;
			}
		}
		
		return false;
	}
	
	public function getAttribute($name) {
		
		$val = parent::getAttribute($name);
		
		switch($name){
			case 'from':
				$addresses = mailparse_rfc822_parse_addresses($val);
			
				$val = $addresses[0];
			
				break;
			
			case 'to':
			case 'cc':
				$val = mailparse_rfc822_parse_addresses($val);
				break;
		}
		
		return $val;
	}
		
	
	
	public function getExcerpt(){
		
		$text = str_replace('>','> ', $this->body);
		$text = strip_tags($text);
		
		return String::cutString($text, 400);
		
	}
	
	public function getCurrentUserIsAuthor(){
		
		
			//var_dump($addresses);
		
			$contact = User::current()->contact;

			$email = $contact->emailAddresses(['email' => $this->from['address']])->single();
			
			return $email !== false;		
		
		
		
	}
	
	private static function collectEmailAddresses(Parser $parser){
		
		$emailAddresses = [];
		
		$headers = ['to','cc','from'];
		
		foreach($headers as $header){
		
			$headerValue = $parser->getHeader($header);
			if(!empty($headerValue)){
				$a = mailparse_rfc822_parse_addresses($headerValue);

				foreach($a as $address){
					$emailAddresses[] = $address['address'];
				}
			}
		}
		
		return $emailAddresses;
		
	}
	
	/**
	 * 
	 * @param Parser $parser
	 * @return Contact[]
	 */
	private static function findContacts(Parser $parser, $ownerUserId){
		$emailAddresses = self::collectEmailAddresses($parser);	
		
		
		$query = Query::newInstance()
				->joinRelation('emailAddresses')
				->groupBy(['t.id'])
				->where(['IN', 'emailAddresses.email', $emailAddresses]);
				
				
		return Contact::findPermitted($query, 'readAccess', $ownerUserId);
		
	}
	
	public static function findThreadId(Parser $parser){
		
		$refs = [];
		
		
		$references = $parser->getHeader('references');
		if(!empty($references)){
			$refs = explode(',', $references);
		}
		
		$inReplyTo = $parser->getHeader('in-reply-to');
		
		if($inReplyTo){
			$refs[] = $inReplyTo;
		}
		
		$refs = array_unique($refs);
		
		if(count($refs)){
			
			$q = Query::newInstance()->where(['IN','messageId', $refs]);
			$message = Message::find($q)->single();			
			
			if($message){
				return $message->threadId;
			}
		}
		
		return null;
	}
	
	public static function createFromMime($mimeStr, $ownerUserId){
		
		
		$parser = new Parser();
		$parser->setText($mimeStr);
		
		$threadId = self::findThreadId($parser);
		
		if(!$threadId){
			$contacts = self::findContacts($parser, $ownerUserId);

			if(!$contacts->getRowCount()){

				echo 'No contacts';
				return false;
			}
		}
		
		
		
		$messageId = $parser->getHeader('message-id');
		
		$message = Message::find(['messageId' => $messageId])->single();
		if(!$message){
			$message = new Message();
		}
		
		$message->ownerUserId = $ownerUserId;
		
		$date = $parser->getHeader('date');
		
		$dateTime = new DateTime($date);
		$dateTime->setTimezone(new DateTimeZone("Etc/GMT"));
		
		$message->date = $dateTime->format('Y-m-d H:i');
		$message->subject = self::mimeHeaderDecode($parser->getHeader('subject'));
		
		$message->to = self::mimeHeaderDecode($parser->getHeader('to'));
		$message->cc = self::mimeHeaderDecode($parser->getHeader('cc'));
		$message->from = self::mimeHeaderDecode($parser->getHeader('from'));
		$message->messageId = $messageId;
		
		$message->threadId = $threadId;
		
		$html = $parser->getMessageBody('html');
		
		if($html){
			$message->body = String::sanitizeHtml($html);
		}else
		{
			$message->body = $parser->getMessageBody('text');
			$message->contentType = 'text/plain';
		}
		if(!$message->save()){
			var_dump($message->getValidationErrors());
			return false;
		}
		
		if(!$threadId) {
			foreach($contacts as $contact){
				if(!Item::find(['contactId' => $contact->id, 'imapMessageId' => $message->id])->single()){
					$timeLineItem = new Item();
					$timeLineItem->contactId = $contact->id;
					$timeLineItem->imapMessageId = $message->id;
					$timeLineItem->createdAt = $message->date;
					if(!$timeLineItem->save()){
						var_dump($timeLineItem->getValidationErrors());
					}			
				}
			}
		}
		
		
//		exit();
		$attachments = $parser->getAttachments();
//		
//		// Write attachments to disk
		foreach ($attachments as $attachment) {
			
			if(!Attachment::find(['messageId' => $message->id, 'filename' => $attachment->getFilename()])->single()){
				$a = new Attachment();
				$a->message = $message;
				$a->filename = $attachment->getFilename();
				$a->contentType = $attachment->content_type;
				$a->inline = $attachment->content_disposition == 'inline';
				
				$headers = $attachment->getHeaders();
				
				if(isset($headers['content-id'])){
					$a->contentId = $headers['content-id'];
				}


				$file = \Intermesh\Core\Fs\File::tempFile();
				$attachment->saveAttachment($file->getFolder()->getPath(), $file->getName());

				$a->setFile($file);

				$a->save();
			}

		}
		
		
		return $message;

	}
	
	
	
	public static function mimeHeaderDecode($string, $defaultCharset='UTF-8') {
		/*
		 * (=?ISO-8859-1?Q?a?= =?ISO-8859-1?Q?b?=)     (ab)
		 *  White space between adjacent 'encoded-word's is not displayed.
		 *
		 *  http://www.faqs.org/rfcs/rfc2047.html
		 */
		$string = preg_replace("/\?=[\s]*=\?/","?==?", $string);

		if (preg_match_all("/(=\?[^\?]+\?(q|b)\?[^\?]+\?=)/i", $string, $matches)) {
			foreach ($matches[1] as $v) {
				$fld = substr($v, 2, -2);
				$charset = strtolower(substr($fld, 0, strpos($fld, '?')));
				$fld = substr($fld, (strlen($charset) + 1));
				$encoding = $fld{0};
				$fld = substr($fld, (strpos($fld, '?') + 1));
				$fld = str_replace('_', '=20', $fld);
				if (strtoupper($encoding) == 'B') {
					$fld = base64_decode($fld);
				}
				elseif (strtoupper($encoding) == 'Q') {
					$fld = quoted_printable_decode($fld);
				}
				$fld = String::cleanUtf8($fld, $charset);

				$string = str_replace($v, $fld, $string);
			}
		}	elseif(($pos = strpos($string, "''")) && $pos < 64){ //check pos for not being to great
			//eg. iso-8859-1''%66%6F%73%73%2D%69%74%2D%73%6D%61%6C%6C%2E%67%69%66
			$charset = substr($string,0, $pos);
			
//			throw new \Exception($charset.' : '.substr($string, $pos+2));
			$string = rawurldecode(substr($string, $pos+2));

			$string = String::cleanUtf8($string, $charset);
		}else
		{			
			$string = String::cleanUtf8($string, $defaultCharset);
		}
		
		return str_replace(array('\\\\', '\\(', '\\)'), array('\\','(', ')'), $string);
	}
}
