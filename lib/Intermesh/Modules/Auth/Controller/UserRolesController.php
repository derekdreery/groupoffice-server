<?php

namespace Intermesh\Modules\Auth\Controller;

use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Criteria;
use Intermesh\Core\Db\Query;
use Intermesh\Modules\Auth\Model\Role;
use Intermesh\Modules\Auth\Model\UserRole;

class UserRolesController extends AbstractRESTController {

	protected function httpGet($userId, $orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $availableOnly = false) {

		if ($availableOnly) {
			$roles = Role::find(Query::newInstance()
									->orderBy([$orderColumn => $orderDirection])
									->limit($limit)
									->offset($offset)
									->search($searchQuery, array('t.name'))
									->joinAdvanced(
											UserRole::className(), Criteria::newInstance()
											->where('t.id = userRole.roleId')
											->andWhere(['userRole.userId' => $userId])
											, 'userRole', 'LEFT')
									->where(['userRole.roleId' => null])
			);
		} else {
			$roles = Role::find(Query::newInstance()
									->orderBy([$orderColumn => $orderDirection])
									->limit($limit)
									->offset($offset)
									->search($searchQuery, array('t.name'))
									->joinRelation('userRole')
									->groupBy(['t.id'])
									->where(['userRole.userId' => $userId]));
		}

		$store = new Store($roles);

		return $this->renderStore($store);
	}

}
