<?php

namespace Intermesh\Modules\Auth;

use Intermesh\Core\AbstractModule;
use Intermesh\Modules\Auth\Controller\AuthController;

class AuthModule extends AbstractModule{
	public static function getRoutes(){
		return [
				'auth' => [
					'controller' => AuthController::className(),
					'children' => [
						'users' => [
							'routeParams' => ['userId'],
							'controller' => Controller\UserController::className()
						],
						'roles' => [
							'routeParams' => ['roleId'],
							'controller' => Controller\RoleController::className()
						]
					]
					]
						
		];
	}
	
}