<?php

namespace Intermesh\Modules\CustomFields\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\CustomFields\Model\Field;

/**
 * The controller for address books
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class FieldController extends AbstractAuthenticationController {


	/**
	 * Fetch fields
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function actionStore($orderColumn = 'sortOrder', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = [], $where=null) {


		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, ['name']);
				
		if(isset($where)){
			$where = json_decode($where, true);
			$query->where($where);
		}

		$fields = Field::find($query);

		$store = new Store($fields);
		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
	}
	
	public function actionSaveSort(){		
			
		$sortOrder = App::request()->post['sortOrder'];

		$index = count($sortOrder);

		foreach (App::request()->post['sortOrder'] as $key){
			$field = Field::findByPk($key);

			if($field){
				$field->sortOrder = $index;
				$field->save();
			}
			$index++;
		}

		$response = array('success'=>true);

		echo $this->view->render('json', $response);		
	}

	/**
	 * Create a new field. Use GET to fetch the default attributes or POST to add a new field.
	 *
	 * The attributes of this field should be posted as JSON in a field object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"field":{"attributes":{"fieldname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($fieldSetId = 0, $returnAttributes = []) {

		$field = new Field();
		$field->fieldSetId = $fieldSetId;

		if (isset(App::request()->post['field'])) {
			$field->setAttributes(App::request()->post['field']['attributes']);

			$field->save();
		}

		echo $this->view->render('form', array('field' => $field, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Update a field. Use GET to fetch the default attributes or POST to add a new field.
	 *
	 * The attributes of this field should be posted as JSON in a field object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"field":{"attributes":{"fieldname":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the field
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$field = Field::findByPk($id);

		if (!$field) {
			throw new NotFound();
		}

		if (isset(App::request()->post['field'])) {
			$field->setAttributes(App::request()->post['field']['attributes']);
			$field->save();
		}
		echo $this->view->render('form', array('field' => $field, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Delete a field
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$field = Field::findByPk($id);

		if (!$field) {
			throw new NotFound();
		}

		$field->delete();

		echo $this->view->render('delete', array('field' => $field));
	}
}
