<?php

namespace Intermesh\Modules\CustomFields\Controller;

use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Modules\Modules\ModuleUtils;

class ModelController extends AbstractRESTController{
	
	
	protected function httpGet() {
		
		$modelClasses = ModuleUtils::getModelNames();
		
		$customFieldModels = [];
		foreach($modelClasses as $modelClass){
			
			if(is_subclass_of($modelClass, "\Intermesh\Core\Db\AbstractRecord") && ($relation = $modelClass::getRelation('customfields'))){
				$customFieldModels[] = ['modelName' => $modelClass, 'customFieldsModelName' => $relation->getRelatedModelName()];
			}
		}
		
		return $this->renderJson(['results' => $customFieldModels, 'success' => true]);
	}
}
