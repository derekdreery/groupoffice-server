<?php

namespace Intermesh\Modules\Auth\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Criteria;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Auth\Model\Role;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Auth\Model\UserRole;

/**
 * The controller for users. Admin role is required.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class UserController extends AbstractRESTController {


	protected function authenticate() {
		return parent::authenticate() && User::current()->isAdmin();
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
	protected function store($orderColumn = 'username', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = []) {

		$users = User::find(Query::newInstance()
								->orderBy([$orderColumn => $orderDirection])
								->limit($limit)
								->offset($offset)
								->search($searchQuery, array('t.username'))
		);

		$store = new Store($users);
		$store->setReturnAttributes($returnAttributes);

		return $this->renderStore($store);
	}

	/**
	 * GET a list of users or fetch a single user
	 *
	 * The attributes of this user should be posted as JSON in a role object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"data":{"attributes":{"name":"test",...}}}
	 * </code>
	 * 
	 * @param int $userId The ID of the role
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	protected function httpGet($userId = null, $returnAttributes = []){
		if(!isset($userId)){
			return $this->callMethodWithParams('store');
		}  else {
			
			if($userId == 0){
				$user = new User();
			}else
			{
				$user = User::findByPk($userId);
			}

			if (!$user) {
				return $this->renderError(404);					
			}
			
			return $this->renderModel($user, $returnAttributes);
		}
	}

	
	/**
	 * Create a new field. Use GET to fetch the default attributes or POST to add a new field.
	 *
	 * The attributes of this field should be posted as JSON in a field object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"data":{"attributes":{"fieldname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function httpPost($returnAttributes = []) {

		$user = new User();
		

		$user->setAttributes(App::request()->payload['data']['attributes']);

		$user->save();
		

		return $this->renderModel($user, $returnAttributes);
	}

	/**
	 * Update a field. Use GET to fetch the default attributes or POST to add a new field.
	 *
	 * The attributes of this field should be posted as JSON in a field object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"data":{"attributes":{"fieldname":"test",...}}}
	 * </code>
	 * 
	 * @param int $userId The ID of the field
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function httpPut($userId, $returnAttributes = []) {

		$user = User::findByPk($userId);

		if (!$user) {
			return $this->renderError(404);
		}

		$user->setAttributes(App::request()->payload['data']['attributes']);
		$user->save();
		
		return $this->renderModel($user, $returnAttributes);
	}

	/**
	 * Delete a field
	 *
	 * @param int $userId
	 * @throws NotFound
	 */
	public function httpDelete($userId) {
		$user = User::findByPk($userId);

		if (!$user) {
			return $this->renderError(404);
		}

		$user->delete();

		return $this->renderModel($user);
	}
	
	/**
	 * Fetch all roles that a user is in
	 *
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @param string $limit
	 * @param string $offset
	 */
//	public function actionRoles($userId, $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {
//
//		$roles = Role::find(Query::newInstance()
//								->orderBy([$orderColumn => $orderDirection])
//								->limit($limit)
//								->offset($offset)
//								->search($searchQuery, array('t.name'))
//								->joinRelation('userRole')
//								->groupBy(['t.id'])
//								->where(['userRole.userId' => $userId]));
//
//		$store = new Store($roles);
//
//		echo $this->view->render('store', $store);
//	}

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
