<?php

namespace Intermesh\Modules\Announcements\Controller;

use Intermesh\Modules\Announcements\Model\Announcement;
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
		$announcement = Announcement::findByPk($this->router->routeParams['announcementId']);		

		if ($announcement) {		
			return $announcement->getImageFile();
		}
		
		return false;
	}


	protected function thumbUseCache() {
		return true;
	}

}
