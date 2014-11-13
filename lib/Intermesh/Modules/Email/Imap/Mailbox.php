<?php

namespace Intermesh\Modules\Email\Imap;

use Exception;
use Intermesh\Core\AbstractObject;

class Mailbox extends AbstractObject {
	
	/**
	 *
	 * @var Connection 
	 */
	public $connection;
	
	public $name;
	
	public $reference = "";	
	
	public $delimiter;
	
	public $flags = [];

	
	public function __construct(Connection $connection) {
		parent::__construct();

		$this->connection = $connection;
	}
	
	public function __toString() {
		return $this->name;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $reference
	 * @return self|boolean
	 */
	public static function findByName(Connection $connection, $name, $reference = ""){
		
		if(!$connection->isAuthenticated()) {
			$connection->authenticate();
		}
		
		$cmd = 'LIST "'.Utils::escape(Utils::utf7Encode($reference)).'" "'.Utils::escape(Utils::utf7Encode($name)).'"';
		
		$connection->sendCommand($cmd);
		
		$response = $connection->getResponse();
		

		
		if($connection->lastCommandSuccessful){
			return self::createFromImapListResponse($connection, explode(' ',$response[0][0]));
		}else
		{
			return false;
		}
	}
	
	/**
	 * 
	 * @param Connection $connection
	 * @param array $lineParts
	 * @return Mailbox
	 */
	public static function createFromImapListResponse(Connection $connection, array $lineParts){
		
		//eg. "* LIST (\HasNoChildren) "/" Trash"
		
		$mailbox = new Mailbox($connection);

		$mailbox->name = array_pop($lineParts);
		$mailbox->delimiter = trim(array_pop($lineParts),'"\'');
		
		while($part = array_pop($lineParts)) {			
			
			$flag = strtolower(trim($part, '\()'));
			if($flag == 'list' || $flag == 'lsub') {
				break;
			}					
			if(!empty($flag)){
				$mailbox->flags[] = $flag;
			}
		}
		
		return $mailbox;		
	}
	
	private $_status;
	
	
	private function getStatus(){
		
		if(!isset($this->_status)){
			$cmd = 'STATUS "'.Utils::escape(Utils::utf7Encode($this->name)).'" (MESSAGES UNSEEN)';

			$this->connection->sendCommand($cmd);

			$response = $this->connection->getResponse();

			if(!$this->connection->lastCommandSuccessful){
				throw new Exception("Could not fetch status from server");
			}
			
		
			$parts = explode(' ', $response[0][0]);
		
			$status = ['mailbox' => $parts[1]];

			for($i = 2, $c = count($parts); $i < $c; $i++){

				$name = trim($parts[$i], ' ()');

				$i++;

				$value = trim($parts[$i], ' ()');

				$status[strtolower($name)] = intval($value);
			}	

			$this->_status = $status;
		}
		
		return $this->_status;
	}
	
	
	public function getUnseenCount(){
		$status = $this->getStatus();
		
		return $status['unseen'];
	}
	
	public function getMessagesCount(){
		$status = $this->getStatus();
		
		return $status['messages'];
	}
	
	/**
	 * 
	 * @param bool $listSubscribed
	 * 
	 * @return Mailbox[]
	 */
	public function getChildren($listSubscribed = true){
		
		
		$listCmd = $listSubscribed ? 'LSUB' : 'LIST';

//		if($listSubscribed && $this->has_capability("LIST-EXTENDED"))
//		$listCmd = "LIST (SUBSCRIBED)";
//			$listCmd = "LIST";
		
		$pattern = isset($this->name) ? '%' : Utils::escape(Utils::utf7Encode($this->name.$this->delimiter)).'%';

		
		if(!$this->connection->isAuthenticated()) {
			$this->connection->authenticate();
		}	
		

		$cmd = $listCmd.' "'.Utils::escape(Utils::utf7Encode($this->reference)).'" "'.$pattern.'"';
		
		$this->connection->sendCommand($cmd);
		
		$response = $this->connection->getResponse();	
		
		$mailboxes = [];
		while($responseLine = array_shift($response)){
			$mailboxes[] = self::createFromImapListResponse($this->connection, explode(' ',$responseLine[0]));
		}
		
		return $mailboxes;
	}
	
	/**
	 * 
	 * @return Message
	 */
	public function getMessages($sort = 'DATE', $reverse = true, $limit = 10, $offset = 0){
		$uids = $this->serverSideSort($sort, $reverse);
		
		if($limit>0){
			$uids = array_slice($uids, $offset, $limit);
		}
		
		$headers = $this->getMessageHeaders($uids);
		
		$messages = [];
		
		foreach($headers as $response){			
			$messages[] = Message::createFromImapResponse($this, $response);
		}
		
		return $messages;
	}
	
	private function select(){
		$command = 'SELECT "'.Utils::escape(Utils::utf7Encode($this->name)).'"';
		
		$this->connection->sendCommand($command);
		
		$this->connection->getResponse();
		
		return $this->connection->lastCommandSuccessful;
	}
	
	
	
	private function serverSideSort($sort = 'DATE', $reverse = true, $filter="ALL") {
		
		$this->select();
		
		$command = 'UID SORT ('.$sort.') UTF-8 '.$filter;
		
		if(!emptY($filter)){			
			$command .= ' '.$filter;
		}
		
		$this->connection->sendCommand($command);
		
		$response = $this->connection->getResponse();
		
		if(!$this->connection->lastCommandSuccessful){
			return false;
		}

		
		$uids = [];
		
		//remove OK line.
		array_pop($response[0]);
		
		while($line = array_shift($response[0])) {
			
			$vals = explode(" ", trim(str_replace('SORT', '', $line)));		
			$uids = array_merge($uids, $vals);
			
		}
		
		if($reverse){
			$uids = array_reverse($uids);
		}
	
		return $uids;
	}
	
	
	
	/**
	 * Get's message headers from an UID range:
	 *
	 * $message=array(
					'to'=>'',
					'cc'=>'',
					'bcc'=>'',
					'from'=>'',
					'subject'=>'',
					'uid'=>'',
					'size'=>'',
					'internal_date'=>'',
					'date'=>'',
					'udate'=>'',
					'internal_udate'=>'',
					'x-priority'=>3,
					'reply-to'=>'',
					'content-type'=>'',
					'disposition-notification-to'=>'',
					'content-transfer-encoding'=>'',
				 'charset'=>'',
					'seen'=>0,
					'flagged'=>0,
					'answered'=>0,
					'forwarded'=>0
				);
	 *
	 * @param <type> $uids
	 * @return <type>
	 */
	private function getMessageHeaders($uids, $full_data=false) {

		if(empty($uids)){
			return [];
		}

		$sorted_string = implode(',', $uids);
		
		$command = 'UID FETCH '.$sorted_string.' (FLAGS INTERNALDATE RFC822.SIZE BODY.PEEK[HEADER.FIELDS (SUBJECT FROM DATE CONTENT-TYPE X-PRIORITY TO CC BCC REPLY-TO DISPOSITION-NOTIFICATION-TO CONTENT-TRANSFER-ENCODING MESSAGE-ID)])';

		$this->connection->sendCommand($command);
		$res = $this->connection->getResponse();

//		var_dump($res);
			
		
		
		
		
		return $res;
		
//		exit();
		
	
		$tags = array('UID' => 'uid', 'FLAGS' => 'flags', 'X-GM-LABELS' => 'flags', 'RFC822.SIZE' => 'size', 'INTERNALDATE' => 'internal_date');
		$junk = array('SUBJECT', 'FROM', 'CONTENT-TYPE', 'TO', 'CC','BCC', '(', ')', ']', 'X-PRIORITY', 'DATE','REPLY-TO','DISPOSITION-NOTIFICATION-TO','CONTENT-TRANSFER-ENCODING', 'MESSAGE-ID');
		//$flds = array('uid','flags','size','internal_date','answered','seen','','reply-to', 'content-type','x-priority','disposition-notification-to');
		$headers = array();
		foreach ($res as $n => $vals) {
			if (isset($vals[0]) && $vals[0] == '*') {
				$message=array(
					'to'=>'',
					'cc'=>'',
					'bcc'=>'',
					'from'=>'',
					'subject'=>'',
					'uid'=>'',
					'size'=>'',
					'internal_date'=>'',
					'date'=>'',
					'udate'=>'',
					'internal_udate'=>'',
					'x_priority'=>3,
					'reply_to'=>'',
					'message_id'=>'',
					'content_type'=>'',
					'content_type_attributes'=>array(),
					'disposition_notification_to'=>'',
					'content_transfer_encoding'=>'',
					'charset'=>'',
					'seen'=>0,
					'flagged'=>0,
					'answered'=>0,
					'forwarded'=>0,
					'has_attachments'=>0,
					'labels'=>array(),
					'deleted'=>0,
				);

				$count = count($vals);
				for ($i=0;$i<$count;$i++) {
					if ($vals[$i] == 'BODY[HEADER.FIELDS') {
						$i++;
						while(isset($vals[$i]) && in_array($vals[$i], $junk)) {
							$i++;
						}

						$header = str_replace("\r\n", "\n", $vals[$i]);
						$header = preg_replace("/\n\s/", " ", $header);

						$lines = explode("\n", $header);

						foreach ($lines as $line) {
							if(!empty($line)) {
								$header = trim(strtolower(substr($line, 0, strpos($line, ':'))));
								$header = str_replace('-','_',$header);

								if (!$header && !empty($last_header)) {
									$message[$last_header] .= "\n".trim($line);
								}else {
									if(isset($message[$header])){
										$message[$header] = trim(substr($line, (strpos($line, ':') + 1)));
										$last_header = $header;
									}
								}
							}
						}
					}
					elseif (isset($tags[strtoupper($vals[$i])])) {
						if (isset($vals[($i + 1)])) {
							if ($tags[strtoupper($vals[$i])] == 'flags' && $vals[$i + 1] == '(') {
								$n = 2;
								while (isset($vals[$i + $n]) && $vals[$i + $n] != ')') {
									$prop = str_replace('-','_',strtolower(substr($vals[$i + $n],1)));
									//\GO::debug($prop);
									if(isset($message[$prop])) {
										$message[$prop]=true;
									} else {
										$message['labels'][] = strtolower($vals[$i + $n]);
									}

									$n++;
								}
								$i += $n;
							}
							else {
								$prop = $tags[strtoupper($vals[$i])];

								if(isset($message[$prop]))
										$message[$prop] = trim($vals[($i + 1)]);
								$i++;
							}
						}
					}
				}
				if ($message['uid']) {
					if(isset($message['content_type'])) {
						$message['content_type']=strtolower($message['content_type']);
						if (strpos($message['content_type'], 'charset=')!==false) {
							if (preg_match("/charset\=([^\s]+)/", $message['content_type'], $matches)) {
								$message['charset'] = trim(str_replace(array('"', "'", ';'), '', $matches[1]));
							}
						}
						if(preg_match("/([^\/]*\/[^;]*)(.*)/", $message['content_type'], $matches)){
							$message['content_type']=$matches[1];
							$atts = trim($matches[2], ' ;');
							$atts=explode(';', $atts);

							for($i=0;$i<count($atts);$i++){
								$keyvalue=explode('=', $atts[$i]);
								if(isset($keyvalue[1]) && $keyvalue[0]!='boundary')
									$message['content_type_attributes'][trim($keyvalue[0])]=trim($keyvalue[1],' "');
							}

							//$message['content-type-attributes']=$atts;
						}
					}

					//sometimes headers contain some extra stuff between ()
					$message['date']=preg_replace('/\([^\)]*\)/','', $message['date']);

					$message['udate']=strtotime($message['date']);
					$message['internal_udate']=strtotime($message['internal_date']);
					if(empty($message['udate']))
						$message['udate']=$message['internal_udate'];

					$message['subject']=$this->mime_header_decode($message['subject']);
					$message['from']=$this->mime_header_decode($message['from']);
					$message['to']=$this->mime_header_decode($message['to']);
					$message['reply_to']=$this->mime_header_decode($message['reply_to']);
					$message['disposition_notification_to']=$this->mime_header_decode($message['disposition_notification_to']);

					if(isset($message['cc']))
						$message['cc']=$this->mime_header_decode($message['cc']);

					if(isset($message['bcc']))
						$message['bcc']=$this->mime_header_decode($message['bcc']);

					preg_match("'([^/]*)/([^ ;\n\t]*)'i", $message['content_type'], $ct);

					if (isset($ct[2]) && $ct[1] != 'text' && $ct[2] != 'alternative' && $ct[2] != 'related')
					{
						$message["has_attachments"] = 1;
					}

					$headers[$message['uid']] = $message;

					//$message['priority']=intval($message['x-priority']);


				}
			}
		}
		$final_headers = array();
		foreach ($uids as $v) {
			if (isset($headers[$v])) {
				$final_headers[$v] = $headers[$v];
			}
		}

		//\GO::debug($final_headers);
		return $final_headers;
	}
}