<?php

namespace Intermesh\Modules\Imap\Model;

use DateInterval;
use DateTime;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Modules\Auth\Model\User;
use MimeMailParser\Parser;

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

	protected static function defineRelations(RelationFactory $r) {
		return [
			$r->belongsTo('owner', User::className(), 'ownerUserId'),
		];
	}

	private function getMailboxSpec($mailbox = "INBOX") {
		$mailboxStr = "{" . $this->host . ":" . $this->port;

		if ($this->encrytion == 'ssl') {
			$mailboxStr .= "/ssl/novalidate-cert";
		} else if ($this->encrytion == 'tls') {
			$mailboxStr .= "/tls";
		}

		$mailboxStr .= "}" . $mailbox;

		return $mailboxStr;
	}

	private function getConnection($mailbox = "INBOX") {



//		echo $mailboxStr.', '.$this->username.', '.$this->password;

		return imap_open($this->getMailboxSpec($mailbox), $this->username, $this->password);
	}

	public function sync() {

		$conn = $this->getConnection();

		if (!$conn) {
			echo "Failed to connect";
			exit();
		}


		$mailboxes = imap_lsub($conn, $this->getMailboxSpec(""), '*');
		
	
		foreach($mailboxes as $mailbox){
			
			imap_reopen($conn, $mailbox);

			$date = new DateTime();
			$interval = new DateInterval('P7D');
			$interval->invert = true;
			$date->add($interval);
	
			$this->syncedAt = $date->format('Y-m-d H:i:s');

			$since = date("d-M-Y", strtotime($this->syncedAt));

	//		echo imap_num_msg($conn);

			$uids = imap_search($conn, "SINCE " . $since, SE_UID, "UTF-8");
			
			if(!empty($uids)){
				foreach ($uids as $uid) {
					$this->_importMessage($conn, $uid);
				}
			}
		
		}


		imap_close($conn);
	}

	private function _importMessage($conn, $uid) {
		$msg = imap_fetchheader($conn, $uid, FT_PREFETCHTEXT | FT_UID) . "\n";
		$msg .= imap_body($conn, $uid, FT_UID | FT_PEEK);

		$message = Message::createFromMime($msg, $this->ownerUserId);
		if($message){
			$this->syncedAt = $message->date;
			$this->save();
		}

	}

}
