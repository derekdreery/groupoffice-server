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
		
		$response = ['mailboxes' => [], 'messages'=>[]];
		
		$connection = new Connection('localhost', 143, 'admin@intermesh.dev', 'admin');
		
		
		

//		$mailbox = new \Intermesh\Modules\Email\Imap\Mailbox($connection);
//		
//		$mailboxes = $mailbox->getChildren();
//		
//		foreach($mailboxes as $mailbox){
//			$response['mailboxes'][] = ['name' => $mailbox->name, 'unseenCount' => $mailbox->getUnseenCount(), 'messagesCount' => $mailbox->getMessagesCount()];
//		}
//		
		
		
		
		$mailbox = Mailbox::findByName($connection, "INBOX");
		
		$messages = $mailbox->getMessages('DATE', true,1,0);
		
		foreach($messages as $message){
			
			
//			echo  $message->getBody();
			
			
			$atts = $message->getAttachments();
			
			$atts[0]->output();
			exit();

			
			//$response['messages'][] = ['subject' => $message->subject, 'date' => $message->date->format('Y-m-d H:i')];
//			$response['messages'][] = $message->toArray();
		}
		
		
		return $this->renderJson($response);
		
	}
	
	
}