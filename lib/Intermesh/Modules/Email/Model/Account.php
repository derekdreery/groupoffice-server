<?php
namespace Intermesh\Modules\Email\Model;

use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Email\Imap\Connection;
use Intermesh\Modules\Email\Imap\Mailbox;


/**
 * The Account model
 *
 * @property int $id
 * @property int $ownerUserId
 * @property User $owner
 * @property string $createdAt
 * @property string $modifiedAt
 * @property string $host
 * @property int $port
 * @property string $encrytion
 * @property string $username
 * @property string $password
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Account extends AbstractRecord {
	
	private $_connection;
	
	private $_rootMailbox;

	protected static function defineRelations(RelationFactory $r) {
		return [
			$r->belongsTo('owner', User::className(), 'ownerUserId'),
		];
	}	

	/**
	 * Get the IMAP connection
	 * 
	 * @return Connection
	 */
	public function getConnection() {
		
		if(!isset($this->_connection)) {
			$this->_connection = new Connection(
				$this->host,
				$this->port,
				$this->username, 
				$this->password, 
				$this->encrytion == 'ssl', 
				$this->encrytion=='tls', 
				'plain');
		}

		return $this->_connection;
	}
	
	/**
	 * Get the root mailbox
	 * 
	 * @return Mailbox
	 */
	public function getRootMailbox(){
		
		if(!isset($this->_rootMailbox)){
			$this->_rootMailbox = new Mailbox($this->getConnection());
		}
		return $this->_rootMailbox;
	}
	
	/**
	 * Finds a mailbox by name
	 * 
	 * 
	 * @param string $mailboxName
	 * @param string $reference
	 * @return Mailbox|boolean
	 */
	public function findMailbox($mailboxName, $reference = ""){
		return Mailbox::findByName($this->getConnection(), $mailboxName, $reference);		
	}


}
