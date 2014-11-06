<?php

namespace Intermesh\Modules\Auth\Controller;

use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Criteria;
use Intermesh\Core\Db\Query;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Auth\Model\UserRole;

class RoleUsersController extends AbstractRESTController {

	protected function httpGet($roleId, $orderColumn = 'username', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $availableOnly = false) {

		if ($availableOnly) {
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
		
		} else {
			$users = User::find(Query::newInstance()
									->orderBy([$orderColumn => $orderDirection])
									->limit($limit)
									->offset($offset)
									->search($searchQuery, ['t.username'])
									->joinRelation('userRole')
									->groupBy(['t.id'])
									->where(['userRole.roleId' => $roleId])
			);
		}

		$store = new Store($users);

		return $this->renderStore($store);
	}

}
