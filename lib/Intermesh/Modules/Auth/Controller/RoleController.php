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
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;

/**
 * The controller for roles. Admin role is required.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class RoleController extends AbstractAuthenticationController {

	public function __construct() {

		parent::__construct();

//		if (!User::current()->isAdmin()) {
//			throw new Forbidden();
//		}
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
	public function actionStore( $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = []) {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, array('t.name'));

		$roles = Role::find($query);		

		$store = new Store($roles);
		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
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
	public function actionUsers($roleId, $orderColumn = 'username', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$users = User::find(Query::newInstance()
								->orderBy([$orderColumn => $orderDirection])
								->limit($limit)
								->offset($offset)
								->search($searchQuery, array('t.username', 't.email'))
								->joinRelation('userRole')
								->groupBy(['t.id'])
								->where(['userRole.roleId' => $roleId])
				);

		$store = new Store($users);

		echo $this->view->render('store', $store);
	}

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
								->search($searchQuery, ['t.username', 't.email'])
								->joinAdvanced(
										UserRole::className(), Criteria::newInstance()
											->where('t.id = userRole.userId')
											->andWhere(['userRole.roleId'=>$roleId])
										, 'userRole', 'LEFT')
								->where(['userRole.userId' => null])
		);

		$store = new Store($users);

		echo $this->view->render('store', $store);
	}

//	/**
//	 * Sets a role enabled for a user
//	 *
//	 * @param int $roleId
//	 * @param int $enabled
//	 * @param int $userId Optional. Defaults to the current user.
//	 */
//	public function actionToggleUser($roleId, $enabled, $userId=null){
//
//		if(!isset($userId)){
//			$userId=User::current()->id;
//		}
//
//		$ur = UserRole::findByPk(array('roleId'=>$roleId, 'userId'=>$userId));
//
//		if($enabled){
//
//			if(!$ur){
//				$ur = new UserRole();
//				$ur->roleId=$roleId;
//				$ur->userId=$userId;
//
//				$ur->save();
//			}
//
//		}else
//		{
//			$ur->delete();
//		}
//
//		echo $this->view->render('form', array('userRole'=>$ur));
//	}

	/**
	 * Create a new role. Use GET to fetch the default attributes or POST to add a new role.
	 *
	 * The attributes of this role should be posted as JSON in a role object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"role":{"attributes":{"rolename":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($returnAttributes = []) {

		$role = new Role();

		if (isset(App::request()->post['role'])) {
			$role->setAttributes(App::request()->post['role']['attributes']);
			$role->save();
		}

		echo $this->view->render('form', array('role' => $role, 'returnAttributes' => $returnAttributes));
	}
	
	
	/**
	 * Read a role
	 *
	 * The attributes of this role should be posted as JSON in a role object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"role":{"attributes":{"rolename":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the role
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionRead($id, $returnAttributes = []) {

		$role = Role::findByPk($id);

		if (!$role) {
			throw new NotFound();
		}

		echo $this->view->render('read', array('role' => $role, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Update a role. Use GET to fetch the default attributes or POST to add a new role.
	 *
	 * The attributes of this role should be posted as JSON in a role object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"role":{"attributes":{"rolename":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the role
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$role = Role::findByPk($id);

		if (!$role) {
			throw new NotFound();
		}

		if (isset(App::request()->post['role'])) {
			$role->setAttributes(App::request()->post['role']['attributes']);
			$role->save();
		}
		echo $this->view->render('form', array('role' => $role, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Delete a role
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$role = Role::findByPk($id);

		if (!$role) {
			throw new NotFound();
		}

		$role->delete();

		echo $this->view->render('delete', array('role' => $role));
	}
	
	
	public function actionGetPermissions($modelId, $modelName, $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = ""){
		
		$model = $modelName::findByPk($modelId);
		
		if(!$model->currentUserCanManagePermissions()){
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
						'permissions', 
						true, 
						'LEFT',
						Criteria::newInstance()->where(['permissions.'.$roleModelName::resourceKey() => $modelId])
						);
		
		
		
		$roles = Role::find($query);

		$store = new Store($roles);
		$store->setReturnAttributes(['*', 'permissions.*', 'permissions.permissionDependencies']);


		echo $this->view->render('store', $store);
	}
	
	public function actionSetPermissions($modelId, $modelName){
		
		$result = array('success' => true, 'permissions' => []);
		
		$model = $modelName::findByPk($modelId);
		
		if(!$model->currentUserCanManagePermissions()){
			throw new Forbidden();
		}
		
		$relation = $model->getRolesRelation();
		$roleModelName = $relation->getRelatedModelName();
		
		
		foreach(App::request()->post['permissions'] as $permissions){
			
			
			$model = $roleModelName::findByPk([
						'roleId' => $permissions['roleId'], 
						$roleModelName::resourceKey() => $modelId
					]);
			
//			var_dump($model);
			
			if(!$model){
				$model = new $roleModelName;
				$model->{$roleModelName::resourceKey()} = $modelId;
				
			}
			$model->setAttributes($permissions);
			if(!$model->save()){
				throw new \Exception(var_export($model->getValidationErrors()));
			}
		}
		
		
		echo $this->view->render('json', $result);	
	} 

}
