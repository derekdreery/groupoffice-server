<?php

namespace Intermesh\Modules\Auth\Controller;

use Exception;
use Intermesh\Core\App;
use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Criteria;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Auth\Model\Role;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Auth\Model\UserRole;

/**
 * The controller for roles. Admin role is required.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class RoleController extends AbstractRESTController {

	protected function authenticate() {
		return parent::authenticate() && User::current()->isAdmin();
	}

	/**
	 * Fetch roles
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	protected function store($orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = []) {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, array('t.name'));

		$roles = Role::find($query);

		$store = new Store($roles);
		$store->setReturnAttributes($returnAttributes);

		return $this->renderStore($store);
	}

	/**
	 * GET a list of roles or fetch a single role
	 *
	 * The attributes of this role should be posted as JSON in a role object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"data":{"attributes":{"name":"test",...}}}
	 * </code>
	 * 
	 * @param int $roleId The ID of the role
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	protected function httpGet($roleId = null, $returnAttributes = []) {
		if (!isset($roleId)) {
			return $this->callMethodWithParams('store');
		} else {

			if ($roleId == 0) {
				$role = new Role();
			} else {
				$role = Role::findByPk($roleId);
			}

			if (!$role) {
				return $this->renderError(404);
			}

			return $this->renderModel($role, $returnAttributes);
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

		$role = new Role();


		$role->setAttributes(App::request()->payload['data']['attributes']);

		$role->save();


		return $this->renderModel($role, $returnAttributes);
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
	 * @param int $roleId The ID of the field
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function httpPut($roleId, $returnAttributes = []) {

		$role = Role::findByPk($roleId);

		if (!$role) {
			return $this->renderError(404);
		}

		$role->setAttributes(App::request()->payload['data']['attributes']);
		$role->save();

		return $this->renderModel($role, $returnAttributes);
	}

	/**
	 * Delete a field
	 *
	 * @param int $roleId
	 * @throws NotFound
	 */
	public function httpDelete($roleId) {
		$role = Role::findByPk($roleId);

		if (!$role) {
			return $this->renderError(404);
		}

		$role->delete();

		return $this->renderModel($role);
	}

	/**
	 * Fetch all users in a role
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @return array JSON Model data
	 */
//	public function actionUsers($roleId, $orderColumn = 'username', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {
//
//		$users = User::find(Query::newInstance()
//								->orderBy([$orderColumn => $orderDirection])
//								->limit($limit)
//								->offset($offset)
//								->search($searchQuery, ['t.username'])
//								->joinRelation('userRole')
//								->groupBy(['t.id'])
//								->where(['userRole.roleId' => $roleId])
//		);
//
//		$store = new Store($users);
//
//		echo $this->view->render('store', $store);
//	}

	/**
	 * Fetch all users that are not in a role
	 *
	 * @param string $orderColumn
	 * @param string $orderDirection
	 * @param string $limit
	 * @param string $offset
	 */
	public function actionAvailableUsers($roleId, $orderColumn = 'username', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$users = User::find(Query::newInstance()
								->orderBy([$orderColumn => $orderDirection])
								->limit($limit)
								->offset($offset)
								->search($searchQuery, ['t.username'])
								->joinAdvanced(
										UserRole::className(), Criteria::newInstance()
										->where('t.id = userRole.userId')
										->andWhere(['userRole.roleId' => $roleId])
										, 'userRole', 'LEFT')
								->where(['userRole.userId' => null])
		);

		$store = new Store($users);

		echo $this->view->render('store', $store);
	}

	public function actionGetPermissions($modelId, $modelName, $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$model = $modelName::findByPk($modelId);

		if (!$model->currentUserCanManagePermissions()) {
			throw new Forbidden();
		}

		$relation = $model->getRolesRelation();
		$roleModelName = $relation->getRelatedModelName();

		$rf = new RelationFactory(Role::className());

		Role::addRuntimeRelation($rf->hasMany('permissions', $roleModelName, 'roleId'));

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, array('t.name'))
				->joinRelation(
				'permissions', true, 'LEFT', Criteria::newInstance()->where(['permissions.' . $roleModelName::resourceKey() => $modelId])
		);



		$roles = Role::find($query);

		$store = new Store($roles);
		$store->setReturnAttributes(['*', 'permissions.*', 'permissions.permissionDependencies']);


		echo $this->view->render('store', $store);
	}

	public function actionSetPermissions($modelId, $modelName) {

		$result = array('success' => true, 'permissions' => []);

		$model = $modelName::findByPk($modelId);

		if (!$model->currentUserCanManagePermissions()) {
			throw new Forbidden();
		}

		$relation = $model->getRolesRelation();
		$roleModelName = $relation->getRelatedModelName();


		foreach (App::request()->post['permissions'] as $permissions) {


			$model = $roleModelName::findByPk([
						'roleId' => $permissions['roleId'],
						$roleModelName::resourceKey() => $modelId
			]);

//			var_dump($model);

			if (!$model) {
				$model = new $roleModelName;
				$model->{$roleModelName::resourceKey()} = $modelId;
			}
			$model->setAttributes($permissions);
			if (!$model->save()) {
				throw new Exception(var_export($model->getValidationErrors()));
			}
		}


		echo $this->view->render('json', $result);
	}

}
