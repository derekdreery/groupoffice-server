<?php

namespace Intermesh\Core\View;

use Exception;
use Intermesh\Core\App;
use Intermesh\Core\Data\JSONObject;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\AbstractRecord;

/**
 * JSON view renderer
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class JsonView extends AbstractView {

	/**
	 * The Content-Type header setting
	 *
	 * @var string
	 */
	public $contentType = 'Content-Type: application/json;charset=utf-8';

	public function render($viewName, $data) {

		$this->headers();

		$fn = "render" . $viewName;
		return $this->$fn($data);
	}

	private function renderOptions($data) {
		exit();
	}

	private function renderJson($data) {

		if (isset($data['debug'])) {
			throw new Exception('debug is a reserved data object');
		}

		if (App::debugger()->enabled) {
			$data['debug'] = App::debugger()->entries;
		}

		if (!isset($data['success'])) {
			$data['success'] = true;
		}

		return new JSONObject($data);
	}

	private function renderStore(Store $store) {
		$response = ['success' => true, 'results' => $store->getRecords()];

		return $this->renderJson($response);
	}
	
	private function renderRead($models){
		return $this->renderForm($models);
	}

	/**
	 *
	 * @param AbstractRecord[] $models
	 * @return type
	 */
	private function renderForm($models) {
		$response = ['data' => []];

		if (isset($models['returnAttributes'])) {
			$returnAttributes = AbstractRecord::parseReturnAttributes($models['returnAttributes']);
			
			unset($models['returnAttributes']);
		} else {
			$returnAttributes = [];
		}

		foreach ($models as $key => $model) {

			$response['data'][$key] = $model->toArray($returnAttributes);
		}

		return $this->renderJson($response);
	}

	private function renderDelete($models) {
		$response = ['data' => []];
		foreach ($models as $key => $model) {
			$response['data'][$key] = $model->toArray();
		}

		return $this->renderJson($response);
	}

	private function renderException(Exception $e) {

		$response['success'] = false;
		$response['exception'] = [
			'className' => get_class($e),
			'code' => $e->getCode(),
			'message' => $e->getMessage(),
			'trace' => $e->getTrace()
		];

//		var_dump($response);
		return $this->renderJson($response);
	}

}
