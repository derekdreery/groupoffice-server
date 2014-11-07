<?php

namespace Intermesh\Modules\Imap\Controller;

use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Core\Exception\NotImplemented;
use Intermesh\Modules\Imap\Model\Attachment;
use Intermesh\Modules\Imap\Model\Message;

class MessageController extends AbstractRESTController{

	public function actionAttachment($id) {

		$attachment = Attachment::findByPk($id);

		if (!$attachment->message->checkReadPermission()) {
			throw new Forbidden();
		}

		$attachment->output();
	}
	

	public function actionRead($id, $returnAttributes = []) {
		$message = Message::findByPk($id);

		if (!$message) {
			throw new NotFound();
		}

		if (!$message->checkReadPermission()) {
			throw new Forbidden();
		}

		echo $this->view->render(
				'form', [
			'message' => $message,
			'returnAttributes' => $returnAttributes
				]
		);
	}

	public function actionCreate($returnAttributes = array()) {
		throw new NotImplemented();
	}

	public function actionDelete($id) {
		throw new NotImplemented();
	}

	public function actionUpdate($id, $returnAttributes = array()) {
		throw new NotImplemented();
	}

}
