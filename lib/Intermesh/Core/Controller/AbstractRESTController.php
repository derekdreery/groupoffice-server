<?php

namespace Intermesh\Core\Controller;

use DateTime;
use Flow\Exception;
use Intermesh\Core\AbstractObject;
use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Exception\HttpException;
use Intermesh\Core\Exception\MissingControllerActionParameter;
use Intermesh\Core\Http\Router;
use Intermesh\Modules\Auth\Model\User;
use ReflectionMethod;

/**
 * Abstract controller class for basic REST operations.
 *
 * The router routes requests to controller actions.
 * All controllers must extend this or a subclass of this class.
 * The controller will be call it's methods based on the HTTP method. So it may 
 * implement:
 * 
 * 1. httpGet
 * 2. httpPost
 * 3. httpPut
 * 4. httpDelete
 * 5. httpPatch
 * 6. httpOptions
 * 7. httpHead
 * 
 * {@see Router The router routes requests to controllers}
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

abstract class AbstractRESTController extends AbstractObject {

	/**
	 * The router object
	 * 
	 * Useful to lookup $this->router->routeParams
	 * 
	 * @var Router
	 */
	protected $router;
	

	/**
	 * Set to true to calculare an MD5 hash and return it as ETag.
	 * 
	 * @var boolean 
	 */
	protected $cacheJsonOutput = false;

	public function __construct(Router $router) {

		$this->router = $router;

		parent::__construct();
	}

	/**
	 * Set HTTP status header
	 * 
	 * @param int $httpCode
	 */
	protected function setStatus($httpCode) {
		header("HTTP/1.1 " . $httpCode);
		header("Status: $httpCode " . HttpException::$codes[$httpCode]);
	}

	/**
	 * Return error code and exit
	 * 
	 * @param int $httpCode
	 * @param string $message
	 */
	protected function renderError($httpCode, $message = null, \Exception $exception = null) {
		$this->setStatus($httpCode);

		if (!isset($message)) {
			$message = self::$codes[$httpCode];
		}

		$data['success'] = false;
		$data['errors'][] = $message;
		
		if(isset($exception)){
			$data['exception'] = $exception->getTrace();
		}

		return $this->renderJson($data);	
	}
	
	/**
	 * Authenticate the current user
	 * 
	 * Override this for special use cases.
	 * 
	 * @return boolean
	 */
	protected function authenticate(){
		return User::current() != false;
	}

	/**
	 * Runs the controller action
	 * 
	 * @return mixed
	 */
	public function run() {
		
		
		try{
		
			if(!$this->authenticate()){
				throw new HttpException(403);
			}

			$json = $this->callMethodWithParams("http" . $_SERVER['REQUEST_METHOD']);		

			if(isset($json)){
				$json = json_encode($json, JSON_PRETTY_PRINT);

				if ($this->cacheJsonOutput) {
					$this->cacheHeaders(null, md5($json));
				}
			}

	//		header('X-XSS-Protection: 1; mode=block');
	//		header('X-Content-Type-Options: nosniff');


			header("Allow: GET, HEAD, PUT, POST, DELETE");


			
			
		}catch(HttpException $e){	
			$json = $this->renderError($e->getCode(), $e->getMessage(), $e);	
			$json = json_encode($json , JSON_PRETTY_PRINT);
		}
		
		echo $json;
		
		
	}
	
	/**
	 * Runs controller method with GET and route params.
	 * 
	 * For an explanation about route params {@see Router::routeParams}
	 * 
	 * @param string $methodName
	 * @return type
	 * @throws MissingControllerActionParameter
	 */
	protected function callMethodWithParams($methodName){
		
		if(!method_exists($this, $methodName)){
			throw new HttpException(501);
		}
		
		$method = new ReflectionMethod($this, $methodName);

		$rParams = $method->getParameters();

		$givenParams = array_merge($this->router->routeParams, $_GET);

		//call method with all parameters from the $_REQUEST object.
		$methodArgs = array();
		foreach ($rParams as $param) {
			if (!isset($givenParams[$param->getName()]) && !$param->isOptional()) {
				throw new HttpException(400, "Bad request. Missing argument '" . $param->getName() . "' for action method '" . get_class($this) . "->" . $methodName . "'");
			}

			$methodArgs[] = isset($givenParams[$param->getName()]) ? $givenParams[$param->getName()] : $param->getDefaultValue();
		}

		return call_user_func_array([$this, $methodName], $methodArgs);
	}

	/**
	 * Helper funtion to render an array into JSON
	 * 
	 * @param array $json
	 * @param boolean $cache  Turn on caching for JSON. This will calcular an ETag header on the json output so the browser can
	 * cache the response.
	 * @throws Exception
	 */
	protected function renderJson(array $json = []) {

		header('Content-Type: application/json;charset=UTF-8');
		
		if (isset($json['debug'])) {
			throw new Exception('debug is a reserved data object');
		}

		if (App::debugger()->enabled) {
			$json['debug'] = App::debugger()->entries;
		}

		if (!isset($json['success'])) {
			$json['success'] = true;
		}


		return $json;
	}

	/**
	 * Send headers so the browser can cache
	 * 
	 * If the If-Modified-Since or If-None-Match headers are sent and they match
	 * a http 304 not modified status will be sent and it will exit.
	 * 
	 * @param DateTime $lastModified
	 * @param string $etagContent
	 */
	protected function cacheHeaders(DateTime $lastModified = null, $etagContent = null) {

		//get the HTTP_IF_MODIFIED_SINCE header if set
		$ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
		//get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
		$etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

		header('Cache-Control: private');

		if (isset($lastModified)) {
			header('Modified-At: ' . $lastModified->format('D, d M Y H:i:s') . ' GMT');
		}

		if (isset($etagContent)) {
			header('ETag: ' . $etagContent);
		}

//		header('Expires: '.date('D, d M Y H:i:s', time()+86400*30)); //30 days
//		header("Vary: Authorization");
		header_remove('Pragma');
		header_remove('Expires');



		if (
				(isset($lastModified) && $ifModifiedSince >= $lastModified->format('U')) || isset($etagContent) && $etagHeader == $etagContent) {
			$this->setStatus(304);
			exit;
		}
	}

	/**
	 * Used for rendering a model response
	 * 
	 * @param AbstractRecord[] $models
	 * @return type
	 */
	protected function renderModel(AbstractRecord $model, $returnAttributes = []) {


		if (App::request()->getMethod() == 'GET' && isset($model->modifiedAt)) {
			$lastModified = new DateTime($model->modifiedAt);
			$this->cacheHeaders($lastModified, $model->getETag());
		}

		$response = ['data' => []];

		if (isset($returnAttributes)) {
			$returnAttributes = AbstractRecord::parseReturnAttributes($returnAttributes);
		} else {
			$returnAttributes = [];
		}

		$response['data'] = $model->toArray($returnAttributes);
		$response['success'] = !$model->hasValidationErrors();



		return $this->renderJson($response);
	}
	
	/**
	 * Used for rendering a store response
	 * 
	 * @param Store $store
	 * @return array
	 */
	protected function renderStore(Store $store) {
		$response = ['success' => true, 'results' => $store->getRecords()];
		
		$this->cacheJsonOutput = true;

		return $this->renderJson($response);
	}
}
