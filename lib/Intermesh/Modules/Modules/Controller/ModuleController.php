<?php

namespace Intermesh\Modules\Modules\Controller;

use Intermesh\Core\Controller\AbstractRESTController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\NotImplemented;
use Intermesh\Modules\Modules\Model\Module;


/**
 * The controller for roles. Admin role is required.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ModuleController extends AbstractRESTController {


	protected function httpGet($moduleId=null){
		if(!isset($moduleId)){
			return $this->callMethodWithParams('store');
		}else
		{
			return $this->renderError(501); //Not implemented
		}
	}
	
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
	protected function store($orderColumn = 'id', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "") {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, ['t.name']);

		$modules = Module::findPermitted($query);

		$store = new Store($modules);
//		$store->setReturnAttributes($returnAttributes);

		return $this->renderStore($store);
	}
}