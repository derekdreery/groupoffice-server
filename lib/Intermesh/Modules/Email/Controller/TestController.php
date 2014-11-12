<?php

namespace Intermesh\Modules\Email\Controller;

use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Modules\Email\Imap\Connection;
use Intermesh\Modules\Email\Imap\Mailbox;

/**
 * The controller that handles file uploads and can thumbnail the temporary files.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class TestController extends AbstractRESTController {
	protected function httpGet(){
		
		$response = ['mailboxes' => []];
		
		$connection = new Connection('localhost', 143, 'admin@intermesh.dev', 'admin');
		
		
		

//		$mailbox = new \Intermesh\Modules\Email\Imap\Mailbox($connection);
//		
//		$mailboxes = $mailbox->getChildren();
//		
//		foreach($mailboxes as $mailbox){
////			
////			var_dump($mailbox);
//			
//			$response['mailboxes'][] = ['name' => $mailbox->name, 'unseenCount' => $mailbox->getUnseenCount(), 'messagesCount' => $mailbox->getMessagesCount()];
//		}
		
		$mailbox = Mailbox::findByName($connection, "INBOX");
		
		$mailbox->getMessages();
		
		
		return $this->renderJson($response);
		
	}
	
	
}