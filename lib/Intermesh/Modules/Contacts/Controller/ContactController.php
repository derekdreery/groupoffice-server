<?php

namespace Intermesh\Modules\Contacts\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Controller\CrudControllerInterface;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Contacts\Model\Contact;
use Intermesh\Modules\Upload\Controller\ThumbControllerTrait;

/**
 * The controller for address books
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ContactController extends AbstractAuthenticationController implements CrudControllerInterface {

	protected $checkModulePermision = true;

	use ThumbControllerTrait;

	protected function thumbGetFile() {

		$contact = false;

		if (isset($_GET['userId'])) {
			$user = User::findByPk($_GET['userId']);

			if ($user) {
				$contact = $user->contact;
			}
		} else if (isset($_GET['contactId'])) {
			$contact = Contact::findByPk($_GET['contactId']);
		} elseif (isset($_GET['email'])) {

			$query = Query::newInstance()
					->joinRelation('emailAddresses')
					->groupBy(['t.id'])
					->where(['emailAddresses.email' => $_GET['email']]);

			$contact = Contact::findPermitted($query, 'readAccess')->single();
		}


		if ($contact) {

			if (!$contact->checkPermission('readAccess')) {


				throw new Forbidden();
			}

			return $contact->getPhotoFile();
		}


		return false;
	}

	protected function thumbUseCache() {
		return true;
	}

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
	public function actionStore($orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = [], $where = null) {

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

		echo $this->view->render('store', $store);
	}

	/**
	 * Create a new contact. Use GET to fetch the default attributes or POST to add a new contact.
	 *
	 * The attributes of this contact should be posted as JSON in a contact object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"contact":{"attributes":{"contactname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. *,emailAddresses.*. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($returnAttributes = []) {

		$contact = new Contact();

		if (isset(App::request()->post['contact'])) {
			$contact->setAttributes(App::request()->post['contact']['attributes']);

			$contact->save();
		}

		echo $this->view->render('form', array('contact' => $contact, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Get a contact
	 * 
	 * @param int $id
	 * @param array $returnAttributes
	 * @throws NotFound
	 * @throws Forbidden
	 */
	public function actionRead($id, $returnAttributes = []) {
		$contact = Contact::findByPk($id);

		if (!$contact) {
			throw new NotFound();
		}

		if (!$contact->checkPermission('readAccess')) {
			throw new Forbidden();
		}


		echo $this->view->render(
				'read', [
			'contact' => $contact,
			'returnAttributes' => $returnAttributes
				]
		);
	}

	/**
	 * Update a contact. Use GET to fetch the default attributes or POST to add a new contact.
	 *
	 * The attributes of this contact should be posted as JSON in a contact object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"contact":{"attributes":{"contactname":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the contact
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$contact = Contact::findByPk($id);

		if (!$contact) {
			throw new NotFound();
		}

		if (isset(App::request()->post['contact'])) {
			if (!$contact->checkPermission('editAccess')) {
				throw new Forbidden();
			}
			$contact->setAttributes(App::request()->post['contact']['attributes']);
			$contact->save();
		} else {
			if (!$contact->checkPermission('readAccess')) {
				throw new Forbidden();
			}
		}

		echo $this->view->render(
				'form', [
			'contact' => $contact,
			'returnAttributes' => $returnAttributes
				]
		);
	}

	/**
	 * Delete a contact
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$contact = Contact::findByPk($id);

		if (!$contact) {
			throw new NotFound();
		}

		if (!$contact->checkPermission('deleteAccess')) {
			throw new Forbidden();
		}

		$contact->delete();

		echo $this->view->render('delete', array('contact' => $contact));
	}

	public function actionCreateLots() {

		header('Content-Type: text/plain');

		ini_set('max_execution_time', 0);

		for ($i = 1000; $i < 10000; $i++) {
			$contact = new Contact();
			$contact->firstName = 'test';
			$contact->lastName = $i;
			$contact->save();

			echo $i . "\n";
			flush();
		}
	}

}
