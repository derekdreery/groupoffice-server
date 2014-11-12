<?php

namespace Intermesh\Modules\Email;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\Email\Controller\TestController;


class EmailModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'email' => [
				'controller' => TestController::className()
				
			],
		];
	}
}