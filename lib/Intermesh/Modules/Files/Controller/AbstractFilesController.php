<?php
namespace Intermesh\Modules\Files\Controller;

use Flow\Basic;
use Flow\Request;
use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Exception\Forbidden;
use Intermesh\Core\Exception\NotFound;
use Intermesh\Core\Fs\File as File2;
use Intermesh\Modules\Auth\Controller\AbstractAuthenticationController;
use Intermesh\Modules\Files\Model\File;

/**
 * The controller that handles file uploads and can thumbnail the temporary files.
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class AbstractFilesController extends AbstractAuthenticationController{
	
	/**
	 * 
	 * @param int $modelId
	 * @return AbstractRecord|boolean
	 */
	abstract protected function getModel($modelId);
	
	abstract protected function canRead(AbstractRecord $model);
	
	abstract protected function canWrite(AbstractRecord $model);
	
	abstract protected function canUpload(AbstractRecord $model);
	
	public function actionStore($modelId, $returnAttributes=[]){
		
		$model = $this->getModel($modelId);
		
		if (!$model) {
			throw new NotFound();
		}
		
		$folder = $model->getFolder();
		
		if(!$folder){
			echo $this->view->render('json', ['success' => true, 'results' => []]);		
		}else{		
			$store = new Store($folder->children);
			$store->setReturnAttributes($returnAttributes);
			echo $this->view->render('store', $store);		
		}
	}
	
	
	/**
	 * Use Flow.js to upload files. This controller returns the filenames relative
	 *
	 * to the App::session()->getTempFolder();
	 */
	public function actionUpload($modelId) {
		$model = $this->getModel($modelId);
		
		if(!$this->canUpload($model)){
			throw new Forbidden();
		}

		$chunksTempFolder = App::session()->getTempFolder(true)->createFolder('uploadChunks')->create();

		$request = new Request();	
		

		$finalFile = File2::tempFile();
			

		if (Basic::save($finalFile->getPath(), $chunksTempFolder->getPath())) {
			// file saved successfully and can be accessed at './final_file_destination'
			
			
			$folder = $model->getFolder(true);
			
			$file = new File();
			$file->name = $request->getFileName();
			$file->parent = $folder;
			$file->setModel($model);
			$file->setFile($finalFile);
			
			if(!$file->save()){
				throw new \Exception(var_export($file->getValidationErrors()));
			}
			

			echo $this->view->render(
				'form',
				[
					'file' => $file
				]
				);
		} else {
			// This is not a final chunk or request is invalid, continue to upload.
			echo $this->view->render('json', array(
					'success' => true
			));
		}
	}
	
	public function actionDownload($id){
//		$model = $this->getModel($modelId);
//		$folder = $model->getFolder();
//		
//		$file = $folder->children(['isFolder' => false, 'parentId' => $folder->id, 'name' => $filename])->single();		
		
		$file = File::findByPk($id);
		if(!$this->canRead($file->getModel())){
			throw new Forbidden();
		}
		$file->output();
	}
	
	
	
	/**
	 * Update a file. Use GET to fetch the default attributes or POST to add a new file.
	 *
	 * The attributes of this file should be posted as JSON in a file object
	 *
	 * <p>Example for POST and return data:</p>
	 * <code>
	 * {"file":{"attributes":{"filename":"test",...}}}
	 * </code>
	 * 
	 * @param int $id The ID of the file
	 * @param array|JSON $returnAttributes The attributes to return to the client. eg. ['\*','emailAddresses.\*']. See {@see Intermesh\Core\Db\ActiveRecord::getAttributes()} for more information.
	 * @return JSON Model data
	 * @throws NotFound
	 */
	public function actionUpdate($id, $returnAttributes = []) {

		$file = File::findByPk($id);

		if (!$file) {
			throw new NotFound();
		}
		
		if (isset(App::request()->post['file'])) {
			if (!$this->canWrite($file->getModel())) {
				throw new Forbidden();
			}
			$file->setAttributes(App::request()->post['file']['attributes']);
			$file->save();
		}else
		{
			if (!$this->canRead($file->getModel())) {
				throw new Forbidden();
			}
		}
		
		echo $this->view->render(
				'form',
				[
					'file' => $file, 
					'returnAttributes' => $returnAttributes
				]
				);
	}

	/**
	 * Delete a file
	 *
	 * @param int $id
	 * @throws NotFound
	 */
	public function actionDelete($id) {
		$file = File::findByPk($id);

		if (!$file) {
			throw new NotFound();
		}

		if (!$this->canWrite($file->getModel())) {
			throw new Forbidden();
		}

		$file->delete();

		echo $this->view->render('delete', ['file' => $file]);
	}
}