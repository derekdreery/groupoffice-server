<?php

namespace Intermesh\Modules\System;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\System\Controller\CheckController;

class SystemModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'system' => [
				'children' => [
					'check' => [						
						'controller' => CheckController::className()
					]
				],
			],
		];
	}
}