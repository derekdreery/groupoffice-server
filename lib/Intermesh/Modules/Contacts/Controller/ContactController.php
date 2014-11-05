<?php

namespace Intermesh\Modules\Contacts\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Contacts\Model\Contact;
use Intermesh\Modules\Modules\Model\Module;

/**
 * The controller for address books
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ContactController extends AbstractRESTController {
	

//	protected $checkModulePermision = true;
//
//	use ThumbControllerTrait;
//
//	protected function thumbGetFile() {
//
//		$contact = false;
//
//		if (isset($_GET['userId'])) {
//			$user = User::findByPk($_GET['userId']);
//
//			if ($user) {
//				$contact = $user->contact;
//			}
//		} else if (isset($_GET['contactId'])) {
//			$contact = Contact::findByPk($_GET['contactId']);
//		} elseif (isset($_GET['email'])) {
//
//			$query = Query::newInstance()
//					->joinRelation('emailAddresses')
//					->groupBy(['t.id'])
//					->where(['emailAddresses.email' => $_GET['email']]);
//
//			$contact = Contact::findPermitted($query, 'readAccess')->single();
//		}
//
//
//		if ($contact) {
//
//			if (!$contact->checkPermission('readAccess')) {
//
//
//				throw new Forbidden();
//			}
//
//			return $contact->getPhotoFile();
//		}
//
//
//		return false;
//	}

//	protected function thumbUseCache() {
//		return true;
//	}
	
	
	

	/**
	 * Fetch contacts
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function store($orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = [], $where = null) {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset);

		if (!empty($searchQuery)) {
			$query->search($searchQuery, ['name']);
		}

		if (!empty($where)) {

			$where = json_decode($where, true);

			if (count($where)) {
				$query
						->groupBy(['t.id'])
						->whereSafe($where);
			}
		}

		$contacts = Contact::findPermitted($query);


		$store = new Store($contacts);
		$store->setReturnAttributes($returnAttributes);

		return $this->renderStore($store);
	}
	
	
	/**
	 * GET a list of contacts or fetch a single contact
	 *
	 * The attributes of this contact should be posted as JSON in a role object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"data":{"attributes":{"name":"test",...}}}
	 * </code>
	 * 
	 * @param int $contactId The ID of the role
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	protected function httpGet($contactId = null, $returnAttributes = []){
		if(!isset($contactId)){
			return $this->callMethodWithParams('store');
		}  else {
			
			if($contactId == 0){
				$contact = new Contact();
			}else
			{
				$contact = Contact::findByPk($contactId);
				
				if (!$contact->checkPermission('readAccess')) {
					return $this->renderError(403);
				}
			}

			if (!$contact) {
				return $this->renderError(404);					
			}
			
			return $this->renderModel($contact, $returnAttributes);
		}
	}

	
	/**
	 * Create a new field. Use GET to fetch the default attributes or POST to add a new field.
	 *
	 * The attributes of this field should be posted as JSON in a field object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"field":{"attributes":{"fieldname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function httpPost($returnAttributes = []) {
		
		
		if (!Module::find(['name' => 'contact'])->single()->checkPermission('createAccess')) {
			return $this->renderError(403);
		}


		$field = new Contact();
		

		$field->setAttributes(App::request()->payload['data']['attributes']);

		$field->save();
		

		return $this->renderModel($field, $returnAttributes);
	}

	/**
	 * Update a field. Use GET to fetch the default attributes or POST to add a new field.
	 *
	 * The attributes of this field should be posted as JSON in a field object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"field":{"attributes":{"fieldname":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the field
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function httpPut($id, $returnAttributes = []) {

		$contact = Contact::findByPk($id);

		if (!$contact) {
			return $this->renderError(404);
		}
		
		if (!$contact->checkPermission('editAccess')) {
			return $this->renderError(403);
		}

		$contact->setAttributes(App::request()->payload['data']['attributes']);
		$contact->save();
		
		return $this->renderModel($contact, $returnAttributes);
	}

	/**
	 * Delete a field
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function httpDelete($id) {
		$contact = Contact::findByPk($id);

		if (!$contact) {
			return $this->renderError(404);
		}
		
		if (!$contact->checkPermission('deleteAccess')) {
			return $this->renderError(403);
		}

		$contact->delete();

		return $this->renderModel($contact);
	}

//	public function actionCreateLots() {
//
//		header('Content-Type: text/plain');
//
//		ini_set('max_execution_time', 0);
//
//		for ($i = 1000; $i < 10000; $i++) {
//			$contact = new Contact();
//			$contact->firstName = 'test';
//			$contact->lastName = $i;
//			$contact->save();
//
//			echo $i . "\n";
//			flush();
//		}
//	}

}
