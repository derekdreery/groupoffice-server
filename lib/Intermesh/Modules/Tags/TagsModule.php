<?php

namespace Intermesh\Modules\Tags;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\Tags\Controller\TagController;

class TagsModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'tags' => [
				'routeParams' => ['tagId'],
				'controller' => TagController::className()					
			],
		];
	}
}