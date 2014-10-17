<?php

namespace Intermesh\Modules\CustomFields\Controller;

use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Modules\ModuleUtils;

class ModelController extends AbstractAuthenticationController{
	
	
	public function actionGetModelNames() {
		
		$modelClasses = ModuleUtils::getModelNames();
		
		$customFieldModels = [];
		foreach($modelClasses as $modelClass){
			
			if(is_subclass_of($modelClass, "\Intermesh\Core\Db\AbstractRecord") && ($relation = $modelClass::getRelation('customfields'))){
				$customFieldModels[] = ['modelName' => $modelClass, 'customFieldsModelName' => $relation->getRelatedModelName()];
			}
		}
		
		echo $this->view->render('json', ['results' => $customFieldModels, 'success' => true]);
	}
	
	
	
}
