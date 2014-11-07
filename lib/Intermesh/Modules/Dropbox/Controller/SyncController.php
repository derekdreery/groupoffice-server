<?php
namespace Intermesh\Modules\Dropbox\Controller;

use Intermesh\Core\Controller\AbstractCrudController;
use Intermesh\Modules\Dropbox\Model\Account;

class SyncController extends AbstractCrudController{
	public function actionSync(){
		header('Content-Type: text/plain');
		$account = Account::find()->single();
		
		$account->sync();
		
	}
}