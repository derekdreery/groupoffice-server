<?php

namespace Intermesh\Core\Controller;

use DateTime;
use Flow\Exception;
use Intermesh\Core\AbstractObject;
use Intermesh\Core\App;
use Intermesh\Core\Data\Store;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Exception\MissingControllerActionParameter;
use Intermesh\Core\REST\Router;
use Intermesh\Modules\Auth\Model\User;
use ReflectionMethod;

abstract class AbstractRESTController extends AbstractObject {

	/**
	 *
	 * @var Router; 
	 */
	protected $router;
	

	protected $cacheJsonOutput = false;

	public function __construct(Router $router) {

		$this->router = $router;

		parent::__construct();
	}

	public static $codes = [
//		'100' => 'Continue',
//		'101' => 'Switching Protocols',
		'200' => 'OK',
		'201' => 'Created',
//		'202' => 'Accepted',
//		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
//		'205' => 'Reset Content',
//		'206' => 'Partial Content',
//		'300' => 'Multiple Choices',
//		'301' => 'Moved Permanently',
//		'302' => 'Found',
//		'303' => 'See Other',
		'304' => 'Not Modified',
//		'305' => 'Use Proxy',
//		'307' => 'Temporary Redirect',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
//		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
//		'405' => 'Method Not Allowed',
//		'406' => 'Not Acceptable',
//		'407' => 'Proxy Authentication Required',
//		'408' => 'Request Timeout',
		'409' => 'Conflict',
//		'410' => 'Gone',
//		'411' => 'Length Required',
//		'412' => 'Precondition Failed',
//		'413' => 'Request Entity Too Large',
//		'414' => 'Request-URI Too Long',
//		'415' => 'Unsupported Media Type',
//		'416' => 'Requested Range Not Satisfiable',
//		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
//		'502' => 'Bad Gateway',
//		'503' => 'Service Unavailable',
//		'504' => 'Gateway Timeout',
//		'505' => 'HTTP Version Not Supported',
	];


	/**
	 * Set HTTP status header
	 * 
	 * @param int $httpCode
	 */
	protected function setStatus($httpCode) {
		header("HTTP/1.1 " . $httpCode);
		header("Status: $httpCode " . self::$codes[$httpCode]);
	}

	/**
	 * Return error code and exit
	 * 
	 * @param int $httpCode
	 * @param string $message
	 */
	protected function renderError($httpCode, $message = null) {
		$this->setStatus($httpCode);

		if (!isset($message)) {
			$message = self::$codes[$httpCode];
		}

		$data['success'] = false;
		$data['errors'][] = $message;

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

	public function run() {		
		
		if(!$this->authenticate()){
			return $this->renderError(403);
		}
		
		$json = $this->callMethodWithParams("http" . $_SERVER['REQUEST_METHOD']);		
		
		
		$json = json_encode($json);

		if ($this->cacheJsonOutput) {
			$this->cacheHeaders(null, md5($json));
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
		$method = new ReflectionMethod($this, $methodName);

		$rParams = $method->getParameters();

		$givenParams = array_merge($this->router->routeParams, $_GET);

		//call method with all parameters from the $_REQUEST object.
		$methodArgs = array();
		foreach ($rParams as $param) {
			if (!isset($givenParams[$param->getName()]) && !$param->isOptional()) {
				throw new MissingControllerActionParameter("Missing argument '" . $param->getName() . "' for action method '" . get_class($this) . "->" . $methodName . "'");
			}

			$methodArgs[] = isset($givenParams[$param->getName()]) ? $givenParams[$param->getName()] : $param->getDefaultValue();
		}

		return call_user_func_array([$this, $methodName], $methodArgs);
	}

	/**
	 * 
	 * @param array $json
	 * @param boolean $cache  Turn on caching for JSON. This will calcular an ETag header on the json output so the browser can
	 * cache the response.
	 * @throws Exception
	 */
	protected function renderJson(array $json = []) {

		header('Content-Type: application/json;charset=UTF-8');
		//header("Allow: GET, HEAD, PUT, POST, DELETE");

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



		return $this->renderJson($response);
	}
	
	
	protected function renderStore(Store $store) {
		$response = ['success' => true, 'results' => $store->getRecords()];
		
		$this->cacheJsonOutput = true;

		return $this->renderJson($response);
	}
}
