<?php

namespace Intermesh\Modules\Upload;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\Upload\Controller\FlowController;
use Intermesh\Modules\Upload\Controller\ThumbController;

class UploadModule extends AbstractModule {

	public static function getRoutes() {
		return [
			'upload' => [
				'controller' => FlowController::className(),
				'children' => [
					'thumb' => [
						'routeParams' => ['tempFile'],
						'controller' => ThumbController::className()
					]
				],
			],
		];
	}
}