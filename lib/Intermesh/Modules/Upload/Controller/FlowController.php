<?php

namespace Intermesh\Modules\Upload\Controller;

use Flow\Basic;
use Flow\Request;
use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Core\App;

/**
 * The controller that handles file uploads and can thumbnail the temporary files.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class FlowController extends AbstractAuthenticationController {
	
	use ThumbControllerTrait;
	
	protected function thumbGetFile() {
		return App::session()->getTempFolder()->createFile($_GET['src']);
	}
	
	protected function thumbUseCache() {
		return false;
	}

	/**
	 * Use Flow.js to upload files. This controller returns the filenames relative
	 *
	 * to the App::session()->getTempFolder();
	 */
	public function actionUpload() {

		$chunksTempFolder = App::session()->getTempFolder(true)->createFolder('uploadChunks')->create();

		$request = new Request();

		$finalFile = App::session()->getTempFolder()->createFile($request->getFileName());

		if (Basic::save($finalFile->getPath(), $chunksTempFolder->getPath())) {
			// file saved successfully and can be accessed at './final_file_destination'

			echo $this->view->render('json', array(
					'success' => true,
					'file' => $finalFile->getRelativePath(App::session()->getTempFolder())
			));
		} else {
			// This is not a final chunk or request is invalid, continue to upload.
			echo $this->view->render('json', array(
					'success' => true
			));
		}
	}
}