<?php
namespace Intermesh\Core\Model;

use Intermesh\Modules\Auth\Model\User;
use Intermesh\Core\App;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;

/**
 * Session model
 * 
 * User session data is stored in this model.
 *
 * @property int $id
 * @property int $userId
 * @property string $data
 * 
 * @property User $user
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Session extends AbstractRecord{

	public static function tableName() {
		return 'coreSession';
	}
	
	protected static function defineRelations(RelationFactory $r) {
		return array(
			$r->belongsTo('user', User::className(), 'userId')
		);
	}
	
	public function delete() {

		//clean up temp files
		$folder = App::config()->getTempFolder(false)->createFolder($this->id);
		$folder->delete();

		return parent::delete();
	}
}