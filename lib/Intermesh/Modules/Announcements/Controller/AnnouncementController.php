<?php

namespace Intermesh\Modules\Announcements\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Controller\CrudControllerInterface;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Announcements\Model\Announcement;
use Intermesh\Modules\Upload\Controller\ThumbControllerTrait;

/**
 * The controller for address books
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class AnnouncementController extends AbstractAuthenticationController implements CrudControllerInterface {

	protected $checkModulePermision = true;

	use ThumbControllerTrait;

	protected function thumbGetFile() {
		$announcement = Announcement::findByPk($_GET['announcementId']);		

		if ($announcement) {		
			return $announcement->getImageFile();
		}
		
		return false;
	}

	protected function thumbUseCache() {
		return true;
	}

	/**
	 * Fetch announcements
	 *
	 * @param string $orderColumn Order by this column
	 * @param string $orderDirection Sort in this direction 'ASC' or 'DESC'
	 * @param int $limit Limit the returned records
	 * @param int $offset Start the select on this offset
	 * @param string $searchQuery Search on this query.
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return array JSON Model data
	 */
	public function actionStore($orderColumn = 'id', $orderDirection = 'DESC', $limit = 10, $offset = 0, $searchQuery = "", $returnAttributes = [], $where = null) {

		$query = Query::newInstance()
				->orderBy([$orderColumn => $orderDirection])
				->limit($limit)
				->offset($offset);

		if (!empty($searchQuery)) {
			$query->search($searchQuery, ['name']);
		}

		if (!empty($where)) {

			$where = json_decode($where, true);

			if (count($where)) {
				$query
						->groupBy(['t.id'])
						->whereSafe($where);
			}
		}

		$announcements = Announcement::find($query);


		$store = new Store($announcements);
		$store->setReturnAttributes($returnAttributes);

		echo $this->view->render('store', $store);
	}

	/**
	 * Create a new announcement. Use GET to fetch the default attributes or POST to add a new announcement.
	 *
	 * The attributes of this announcement should be posted as JSON in a announcement object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"announcement":{"attributes":{"announcementname":"test",...}}}
	 * </code>
	 * 
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. *,emailAddresses.*. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 */
	public function actionCreate($returnAttributes = []) {

		$announcement = new Announcement();

		if (isset(App::request()->post['announcement'])) {
			$announcement->setAttributes(App::request()->post['announcement']['attributes']);

			$announcement->save();
		}

		echo $this->view->render('form', array('announcement' => $announcement, 'returnAttributes' => $returnAttributes));
	}

	/**
	 * Get a announcement
	 * 
	 * @param int $id
	 * @param array $returnAttributes
	 * @throws NotFound
	 * @throws Forbidden
	 */
	public function actionRead($id, $returnAttributes = []) {
		$announcement = Announcement::findByPk($id);

		if (!$announcement) {
			throw new NotFound();
		}


		echo $this->view->render(
				'read', [
			'announcement' => $announcement,
			'returnAttributes' => $returnAttributes
				]
		);
	}

	/**
	 * Update a announcement. Use GET to fetch the default attributes or POST to add a new announcement.
	 *
	 * The attributes of this announcement should be posted as JSON in a announcement object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"announcement":{"attributes":{"announcementname":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the announcement
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$announcement = Announcement::findByPk($id);

		if (!$announcement) {
			throw new NotFound();
		}

		if (isset(App::request()->post['announcement'])) {
	
			$announcement->setAttributes(App::request()->post['announcement']['attributes']);
			$announcement->save();
		} 
		echo $this->view->render(
				'form', [
			'announcement' => $announcement,
			'returnAttributes' => $returnAttributes
				]
		);
	}

	/**
	 * Delete a announcement
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$announcement = Announcement::findByPk($id);

		if (!$announcement) {
			throw new NotFound();
		}


		$announcement->delete();

		echo $this->view->render('delete', array('announcement' => $announcement));
	}


}
