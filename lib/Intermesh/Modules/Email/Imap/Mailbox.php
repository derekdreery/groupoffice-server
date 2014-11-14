<?php

namespace Intermesh\Modules\Email\Imap;

use Exception;
use Intermesh\Core\AbstractObject;

/**
 * Mailbox object
 * 
 * Handles all mailbox related IMAP functions
 *
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Mailbox extends AbstractObject {
	
	/**
	 *
	 * @var Connection 
	 */
	public $connection;
	
	/**
	 * Name of the mailbox
	 * 
	 * @var string 
	 */
	public $name;
	
	/**
	 * Reference. In most cases this is a namespace.
	 * 
	 * @var string 
	 */
	public $reference = "";	
	
	/**
	 * Mailbox delimiter
	 * 
	 * Usually "/" or "."
	 * 
	 * @var string 
	 */
	public $delimiter;	
	
	/**
	 * Array of mailbox flags
	 * 
	 * eg. NoInferiors, HasChildren
	 * 
	 * @var string 
	 */
	public $flags = [];
	
	
	private $_status;

	
	/**
	 * Constructor
	 * 
	 * @param \Intermesh\Modules\Email\Imap\Connection $connection
	 */
	public function __construct(Connection $connection) {
		parent::__construct();

		$this->connection = $connection;
	}
	
	public function __toString() {
		return $this->name;
	}
	
	/**
	 * Finds a mailbox by name
	 * 
	 * eg. 
	 * 
	 * $mailbox = Mailbox::findByName($conn, "INBOX");
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
	 * Not used directly.
	 * 
	 * When you use a find command this function is used to create mailboxes from
	 * the IMAP response.
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
	
	
	
	/**
	 * Get the mailbox status
	 * 
	 * Only messages and unseen are feteched
	 * 
	 * @return array eg. ['messages'=> 1, 'unseen' => 1]
	 * @throws Exception
	 */
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
	
	/**
	 * Get the number of unseen messages
	 * 
	 * @return int
	 */
	public function getUnseenCount(){
		$status = $this->getStatus();
		
		return $status['unseen'];
	}
	
	/**
	 * Get the number of messages
	 * 
	 * @return int
	 */
	public function getMessagesCount(){
		$status = $this->getStatus();
		
		return $status['messages'];
	}
	
	/**
	 * Get children mailboxes	  
	 * 
	 * @param bool $listSubscribed	
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
	 * Get messages from this mailbox
	 * 
	 * @return Message[]
	 */
	public function getMessages($sort = 'DATE', $reverse = true, $limit = 10, $offset = 0, $filter='ALL'){
		$uids = $this->serverSideSort($sort, $reverse, $filter);
		
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
	
	/**
	 * Select this mailbox on the IMAP server
	 * 
	 * @return boolean
	 */
	private function select(){
		$command = 'SELECT "'.Utils::escape(Utils::utf7Encode($this->name)).'"';
		
		$this->connection->sendCommand($command);
		
		$this->connection->getResponse();
		
		return $this->connection->lastCommandSuccessful;
	}
	
	
	/**
	 * Get an array of UIDS sorted by the server.
	 * 
	 * @param string $sort 'DATE", 'ARRIVAL', 'SUBJECT', 'FROM'
	 * @param boolean $reverse
	 * @param string $filter
	 * 
	 * @return array|boolean UID list ['1','2']
	 */
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
	
	private function getMessageHeaders($uids) {

		if(empty($uids)){
			return [];
		}

		$sorted_string = implode(',', $uids);
		
		$command = 'UID FETCH '.$sorted_string.' (FLAGS INTERNALDATE RFC822.SIZE BODY.PEEK[HEADER.FIELDS (SUBJECT FROM DATE CONTENT-TYPE X-PRIORITY TO CC BCC REPLY-TO DISPOSITION-NOTIFICATION-TO CONTENT-TRANSFER-ENCODING MESSAGE-ID)])';

		$this->connection->sendCommand($command);
		$res = $this->connection->getResponse();
		
		return $res;
		
	}
}