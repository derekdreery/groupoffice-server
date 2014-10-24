<?php

namespace Intermesh\Modules\Auth\Controller;

use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Auth\Model\Role;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Auth\Model\UserRole;
use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Criteria;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;

/**
 * The controller for users. Admin role is required.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class UserController extends AbstractAuthenticationController {

	public function __construct() {

		parent::__construct();

		if (!User::current()->isAdmin()) {
			throw new Forbidden();
		}
	}

	/**
	 * Fetch users
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function actionStore($orderColumn = 'username', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = []) {

		$users = User::find(Query::newInstance()
								->orderBy([$orderColumn => $orderDirection])
								->limit($limit)
								->offset($offset)
								->search($searchQuery, array('t.username'))
		);

		$store = new Store($users);
		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
	}

	/**
	 * Create a new user. Use GET to fetch the default attributes or POST to add a new user.
	 *
	 * The attributes of this user should be posted as JSON in a user object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"user":{"attributes":{"username":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($returnAttributes = []) {

		$user = new User();
		if (isset(App::request()->post['user'])) {
			$user->setAttributes(App::request()->post['user']['attributes']);
			$user->save();
		}
		echo $this->view->render('form', array('user' => $user, 'returnAttributes' => $returnAttributes));
	}
	
	
	/**
	 * Read a user. 
	 *
	 * The attributes of this user should be posted as JSON in a user object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"user":{"attributes":{"username":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the user
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionRead($id, $returnAttributes) {

		$user = User::findByPk($id);

		if (!$user) {
			throw new NotFound();
		}

		echo $this->view->render('form', array('user' => $user, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Update a user. Use GET to fetch the default attributes or POST to add a new user.
	 *
	 * The attributes of this user should be posted as JSON in a user object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"user":{"attributes":{"username":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the user
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes) {

		$user = User::findByPk($id);

		if (!$user) {
			throw new NotFound();
		}

		if (isset(App::request()->post['user'])) {
			$user->setAttributes(App::request()->post['user']['attributes']);
			$user->save();
		}
		echo $this->view->render('form', array('user' => $user, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Delete a user
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$user = User::findByPk($id);

		if (!$user) {
			throw new NotFound();
		}

		$user->delete();

		echo $this->view->render('delete', array('user' => $user));
	}

	/**
	 * Fetch all roles that a user is in
	 *
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @param string $limit
	 * @param string $offset
	 */
	public function actionRoles($userId, $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$roles = Role::find(Query::newInstance()
								->orderBy([$orderColumn => $orderDirection])
								->limit($limit)
								->offset($offset)
								->search($searchQuery, array('t.name'))
								->joinRelation('userRole')
								->groupBy(['t.id'])
								->where(['userRole.userId' => $userId]));

		$store = new Store($roles);

		echo $this->view->render('store', $store);
	}

	/**
	 * Fetch all roles that the given user is not in
	 *
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @param string $limit
	 * @param string $offset
	 */
	public function actionAvailableRoles($userId, $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$roles = Role::find(Query::newInstance()
								->orderBy([$orderColumn => $orderDirection])
								->limit($limit)
								->offset($offset)
								->search($searchQuery, array('t.name'))
								->joinAdvanced(
										UserRole::className(), 
										Criteria::newInstance()
											->where('t.id = userRole.roleId')
											->andWhere(['userRole.userId' => $userId])
										, 'userRole', 'LEFT')
								->where(['userRole.roleId' => null])
		);

		$store = new Store($roles);

		echo $this->view->render('store', $store);
	}

}
