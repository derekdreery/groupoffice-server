<?php

namespace Intermesh\Modules\Modules\Controller;

use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Modules\Model\Module;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;


/**
 * The controller for roles. Admin role is required.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ModuleController extends AbstractAuthenticationController {


	/**
	 * Fetch modules
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function actionStore($orderColumn = 'id', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, ['t.name']);

		$modules = Module::findPermitted($query);

		$store = new Store($modules);
//		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
	}
}