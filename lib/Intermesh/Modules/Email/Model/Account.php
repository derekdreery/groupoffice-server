<?php

namespace Intermesh\Modules\Imap\Model;

use DateInterval;
use DateTime;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Modules\Auth\Model\User;


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

	

	private function getConnection($mailbox = "INBOX") {



//		echo $mailboxStr.', '.$this->username.', '.$this->password;

//		return imap_open($this->getMailboxSpec($mailbox), $this->username, $this->password);
	}


}
