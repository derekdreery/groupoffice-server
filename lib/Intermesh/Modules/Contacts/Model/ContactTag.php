<?php
namespace Intermesh\Modules\Contacts\Model;

use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;
/**
 * The contact model
 *
 * @property int $id
 * @property string $name
 * @property Contact $contact
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ContactTag extends AbstractRecord{	
	public static function primaryKeyColumn() {
		return array('contactId', 'tagId');
	}
	
	public static function defineRelations(RelationFactory $r){
		return [$r->belongsTo('contact', Contact::className(), 'contactId')];
	}
}