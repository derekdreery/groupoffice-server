<?php
namespace Intermesh\Modules\Notes\Model;

use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;

/**
 * @property int $id
 * @property int $noteId
 * @property string $text
 * @property boolean $checked
 * @property int $sortOrder
 */

class NoteListItem extends AbstractRecord{
	
	protected static function defineValidationRules() {
		return array(				

		);
	}
	
	public static function defineRelations(RelationFactory $r){
		return array(
			$r->belongsTo('note', Note::className(), 'noteId')	
		);
	}
	
}