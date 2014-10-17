<?php
namespace Intermesh\Modules\Notes\Model;

use Intermesh\Modules\Auth\Model\AbstractRole;

class NoteRole extends AbstractRole{	
	public static function resourceKey() {
		return 'noteId';
	}
	
}