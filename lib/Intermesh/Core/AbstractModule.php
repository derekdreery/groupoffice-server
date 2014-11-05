<?php

use Intermesh\Core\AbstractObject;

namespace Intermesh\Core;

abstract class AbstractModule extends AbstractObject {

	public static function getRoutes() {
		return [];
	}

}
