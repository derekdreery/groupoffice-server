<?php

namespace Intermesh\Modules\Tags\Controller;

use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Tags\Model\Tag;
use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\NotFound;

/**
 * The controller for address books
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class TagController extends AbstractAuthenticationController {


	/**
	 * Fetch tags
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function actionStore($orderColumn = 'name', $orderDirection = 'ASC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = []) {


		$findParams = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset)
				->search($searchQuery, ['name']);


		$tags = Tag::find($findParams);

		$store = new Store($tags);
		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
	}

	/**
	 * Create a new tag. Use GET to fetch the default attributes or POST to add a new tag.
	 *
	 * The attributes of this tag should be posted as JSON in a tag object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"tag":{"attributes":{"tagname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($returnAttributes = []) {

		$tag = new Tag();

		if (isset(App::request()->post['tag'])) {
			$tag->setAttributes(App::request()->post['tag']['attributes']);

			if (!isset($tag->addressbookId)) {
				$tag->addressbookId = Tags::getDefault()->id;
			}

			$tag->save();
		}

		echo $this->view->render('form', array('tag' => $tag, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Update a tag. Use GET to fetch the default attributes or POST to add a new tag.
	 *
	 * The attributes of this tag should be posted as JSON in a tag object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"tag":{"attributes":{"tagname":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the tag
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$tag = Tag::findByPk($id);

		if (!$tag) {
			throw new NotFound();
		}

		if (isset(App::request()->post['tag'])) {
			$tag->setAttributes(App::request()->post['tag']['attributes']);
			$tag->save();
		}
		echo $this->view->render('form', array('tag' => $tag, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Delete a tag
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$tag = Tag::findByPk($id);

		if (!$tag) {
			throw new NotFound();
		}

		$tag->delete();

		echo $this->view->render('delete', array('tag' => $tag));
	}

}
