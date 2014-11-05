<?php

namespace Intermesh\Modules\CustomFields;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\CustomFields\Controller\FieldController;
use Intermesh\Modules\CustomFields\Controller\FieldSetController;

class CustomFieldsModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'CustomFields' => [
					'children' => [
						'models' => [
								'controller' => Controller\ModelController::className()
						],
						'fieldsets' => [
							'routeParams' => ['modelName'],
							'controller' => FieldSetController::className(),
							'children' => [
								'fields' => [
									'routeParams' => ['fieldId'],
									'controller' => FieldController::className(),
								]
							]
						]
					]
				]
		];
	}

}
