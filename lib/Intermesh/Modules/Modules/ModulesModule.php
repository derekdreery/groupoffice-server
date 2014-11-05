<?php

namespace Intermesh\Modules\Modules;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\Modules\Controller\ModuleController;

class ModulesModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'modules' => [
				'controller' => ModuleController::className()
			]
		];
	}

}
