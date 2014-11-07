<?php
namespace Intermesh\Modules\Notes\Controller;

use Intermesh\Core\App;
use Intermesh\Core\Controller\AbstractCrudController;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\Query;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Modules\Notes\Model\Note;
use Intermesh\Modules\Notes\Model\NoteImage;
use Intermesh\Modules\Upload\Controller\ThumbControllerTrait;

/**
 * The controller for the notes module
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Wesley Smits <wsmits@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class NoteController extends AbstractCrudController{	

//	use ThumbControllerTrait;
	
	public function actionAvailableColors(){
		
		$response = array('success'=>true, 'results'=>array());
		
		$colors = Note::$availableColors;
		
		foreach ($colors as $color){
			$response['results'][] = $color;
		}
		
		echo $this->view->render('json', $response);
	}
	
	public function actionSaveSort(){
		
		if(isset(App::request()->post['sortOrder'])){
			
			$sortOrder = App::request()->post['sortOrder'];
			
			$index = count($sortOrder);
			
			foreach (App::request()->post['sortOrder'] as $key){
				$note = Note::findByPk($key);
				
				if($note){
					$note->sortOrder = $index;
					$note->save();
				}
				$index++;
			}
			
			$response = array('success'=>true);
			
			echo $this->view->render('json', $response);	
		}
	}
	
	public function actionStore($orderColumn='sortOrder', $orderDirection='ASC', $limit=10, $offset=0, $returnAttributes=['*','listItems','images']){
		
		$findParams = Query::newInstance()
						->orderBy([$orderColumn => $orderDirection])
						->limit($limit)
						->offset($offset);		
		
		$notes = Note::findPermitted($findParams);
		
		$store = new Store($notes);
		$store->setReturnAttributes($returnAttributes);
			
		echo $this->view->render('store', $store);		
	}
	
	
	public function actionCreate($returnAttributes=['*','listItems','images']){
		
		$note = new Note();
		
		if(isset(App::request()->post['note'])){
			$note->setAttributes(App::request()->post['note']['attributes']);
			$note->save();
		}
		
		echo $this->view->render('form', array('note'=>$note,'returnAttributes'=>$returnAttributes));
		
	}
	
	public function actionUpdate($id, $returnAttributes=['*','listItems','images']){

		$note = Note::findByPk($id);
		
		if(!$note)
			throw new NotFound();			
		
		$editAccess = $note->checkPermission('editAccess');
		
		if(isset(App::request()->post['note'])){
			
			if (!$editAccess) {
				throw new Forbidden();
			}
			
			$note->setAttributes(App::request()->post['note']['attributes']);
			$note->save();
		} else {
			if (!$note->checkPermission('readAccess')) {
				throw new Forbidden();
			}
		}
		
		echo $this->view->render('form', array('note'=>$note,'returnAttributes'=>$returnAttributes));
		
	}
	
	/**
	 * Delete a note
	 * 
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id){
		$note = Note::findByPk($id);
		
		if(!$note){
			throw new NotFound();			
		}
		
		if (!$note->checkPermission('deleteAccess')) {
			throw new Forbidden();
		}
		
		$note->delete();
		
		echo $this->view->render('delete', array('note'=>$note));
	}
	
	
//	protected function thumbGetFile() {
//		//TODO permissions!
//		return NoteImage::getImagesFolder()->createFile($_GET['src']);
//	}
//	
//	protected function thumbUseCache() {
//		return true;
//	}
//	
}