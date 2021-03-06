<?php
namespace Intermesh\Modules\Auth\Model;

use Intermesh\Core\Db\AbstractRecord;

/**
 * Roles are used for permissions
 * 
 * @property int $userId
 * @property int $roleId
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class UserRole extends AbstractRecord{
	public static function primaryKeyColumn() {
		return array('userId', 'roleId');
	}
}