<?php

namespace Intermesh\Modules\Announcements;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\Announcements\Controller\ThumbController;

class AnnouncementsModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'announcements' => [
				'controller' => Controller\AnnouncementController::className(),
				'routeParams' => ['announcementId'],
				'children' => [
					'thumb' => [
						'controller' => ThumbController::className(),
					],
				]
			]
		];
	}

}
