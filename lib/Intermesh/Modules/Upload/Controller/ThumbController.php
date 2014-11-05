<?php
namespace Intermesh\Modules\Upload\Controller;

use Intermesh\Core\App;
use Intermesh\Modules\Upload\Controller\AbstractThumbController;

/**
 * The controller for address books
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ThumbController extends AbstractThumbController {

protected function thumbGetFile() {
		return App::session()->getTempFolder()->createFile($this->router->routeParams['tempFile']);
	}
	
	protected function thumbUseCache() {
		return false;
	}

}

