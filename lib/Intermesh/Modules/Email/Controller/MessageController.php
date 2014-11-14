<?php
namespace Intermesh\Modules\Email\Controller;

use Intermesh\Core\Controller\AbstractCrudController;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Email\Imap\Mailbox;
use Intermesh\Modules\Email\Model\Account;

class MessageController extends AbstractCrudController{
	
	/**
	 * Fetch accounts
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	protected function actionStore($accountId, $mailboxName, $orderColumn = 'DATE', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = ['uid','subject','from','to','size','flags','messageId','xPriority','date']) {

		$account = Account::findByPk($accountId);		

		if (!$account) {
			throw new NotFound();			
		}
		
		$mailbox = $account->findMailbox($mailboxName);
		
		$response['results'] = [];
		
		$messages = $mailbox->getMessages($orderColumn, $orderDirection == 'DESC', $limit, $offset, "ALL");
		
		while($message = array_shift($messages)){
			$response['results'][] = $message->toArray($returnAttributes);
		}
		
		

		return $this->renderJson($response);
	}

	/**
	 * GET a list of accounts or fetch a single account
	 *
	 * 
	 * @param int $accountId The ID of the role
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	protected function actionRead($accountId, $mailboxName, $uid, $returnAttributes = ["uid","flags","size","date","from","subject","to","cc","bcc","replyTo","contentType","messageId","xPriority","dispositionNotificationTo", "body", "attachments"]){
	
		$account = Account::findByPk($accountId);		

		if (!$account) {
			throw new NotFound();			
		}
		
		$mailbox = Mailbox::findByName($account->getConnection(), $mailboxName);
		
		$message = $mailbox->getMessage($uid);
		
		

		return $this->renderModel($message, $returnAttributes);
		
	}
}