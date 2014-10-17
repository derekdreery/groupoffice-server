<?php

namespace Intermesh\Modules\Timeline\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Timeline\Model\Item;

/**
 * The controller for timeline items
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ItemController extends AbstractAuthenticationController {
	
	protected $checkModulePermision = true;
	

	/**
	 * Fetch items
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function actionStore($orderColumn = 'createdAt', $orderDirection = 'DESC', $limit = 0, $offset = 0, $searchQuery = "", $returnAttributes = [], $where = null) {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset);
		
		if(!empty($searchQuery)){
			$query->search($searchQuery, ['name']);
		}
		
		if(!empty($where)){
			
			$where = json_decode($where, true);
			
			if(count($where)){
				$query					
					->groupBy(['t.id'])				
					->whereSafe($where);			
			}
		}

		$items = Item::find($query);

		$store = new Store($items);
		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
	}

	/**
	 * Create a new item. Use GET to fetch the default attributes or POST to add a new item.
	 *
	 * The attributes of this item should be posted as JSON in a item object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"item":{"attributes":{"itemname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($contactId = 0,$returnAttributes = []) {

		$item = new Item();

		if (isset(App::request()->post['item'])) {
			
			
			
			$item->setAttributes(App::request()->post['item']['attributes']);
			$item->contactId = $contactId;
			
			
			if (!$item->contact->checkPermission('editAccess')) {
				throw new Forbidden();
			}
			
			$item->save();
		}

		echo $this->view->render('form', array('item' => $item, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Update a item. Use GET to fetch the default attributes or POST to add a new item.
	 *
	 * The attributes of this item should be posted as JSON in a item object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"item":{"attributes":{"itemname":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the item
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$item = Item::findByPk($id);

		if (!$item) {
			throw new NotFound();
		}
		
		if (isset(App::request()->post['item'])) {
			if (!$item->contact->checkPermission('editAccess')) {
				throw new Forbidden();
			}
			$item->setAttributes(App::request()->post['item']['attributes']);
			$item->save();
		}else
		{
			if (!$item->contact->checkPermission('readAccess')) {
				throw new Forbidden();
			}
		}
		
		echo $this->view->render(
				'form',
				[
					'item' => $item, 
					'returnAttributes' => $returnAttributes
				]
				);
	}

	/**
	 * Delete a item
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$item = Item::findByPk($id);

		if (!$item) {
			throw new NotFound();
		}

		if (!$item->contact->checkPermission('editAccess')) {
			throw new Forbidden();
		}

		$item->delete();

		echo $this->view->render('delete', array('item' => $item));
	}
}
