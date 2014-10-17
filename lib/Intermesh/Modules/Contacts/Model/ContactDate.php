<?php
namespace Intermesh\Modules\Contacts\Model;

use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Modules\Contacts\Model\Contact;


/**
 * Contact address 
 * 
 * @property int $id
 * @property int $contactId
 * @property string $type
 * @property string $date
 * @property Contact $contact
 *
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ContactDate extends AbstractRecord {
	public static function defineRelations(RelationFactory $r){
		return [$r->belongsTo('contact', Contact::className(), 'contactId')];
	}
}