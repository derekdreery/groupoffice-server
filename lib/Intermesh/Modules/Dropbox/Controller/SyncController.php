<?php
namespace Intermesh\Modules\Dropbox\Controller;

use Intermesh\Core\Controller\AbstractController;

class SyncController extends AbstractController{
	public function actionSync(){
		header('Content-Type: text/plain');
		$account = \Intermesh\Modules\Dropbox\Model\Account::find()->single();
		
		$account->sync();
		
	}
}