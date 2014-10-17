<?php

namespace Intermesh\Core\Http;

use Intermesh\Core\App as App;
use Intermesh\Core\Controller\AbstractController;

/**
 * The router routes requests to their controller actions
 * 
 * 
 * eg.
 * 
 * index.php?r=intermesh/auth/auth/login 
 * 
 * Get's routed to:
 * 
 * Intermesh\Modules\Auth\AuthController::actionLogin()
 * 
 * <code>
 * App::router()->runController();
 * 
 * App::router()->getRoute();
 * </code>
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Router {

	/**
	 * Analyzes the request URL and finds the controller.
	 * 
	 * URL Should be like index.php?r=module/controller/method&param=value
	 * 
	 * If a controller consist of two words then the second word should start with
	 * a capital letter.
	 * 
	 */
	private $_controller;
	private $_action;
	private $_r;

	/**
	 * Get the controller route. eg. email/message/view
	 * 
	 * @return string 
	 */
	public function getControllerRoute() {
		return $this->_r;
	}

	/**
	 * Get the currently active controller for this request.
	 * 
	 * @return AbstractController 
	 */
	public function getController() {
		return $this->_controller;
	}

	/**
	 * Get the currently processing controller action in lowercase and without the
	 * action prefix.
	 * 
	 * @return string
	 */
	public function getControllerAction() {
		return $this->_action;
	}

	/**
	 * Runs a controller action with the given params
	 * 
	 * @param array $params 
	 */
	public function runController() {

		$r = isset(App::request()->get['r']) ? App::request()->get['r'] : false;

		if (!$r) {
			$this->_httpError("HTTP/1.1 400", "Bad request. No controller route given");
			return false;
		}


		$r = explode('/', $r);

		//Camel casisfy
		$r = array_map('ucfirst', $r);


		if (count($r) !== 4) {
			$this->_httpError("HTTP/1.1 400", "Bad request. Invalid controller route given. It should have 4 components: 'namespace/appName/controllerName/actionMethod'.");
			return false;
		}

		list($namespace, $module, $controller, $action) = $r;


		$controllerClass = $namespace . '\\Modules\\' . $module . '\\Controller\\' . $controller . 'Controller';

		if (preg_match('/[^A-Za-z0-9_\\\\]+/', $controllerClass, $matches)) {
			$err = "Only these charactes are allowed in controller names: A-Za-z0-9_";
			echo $err;
			trigger_error($err, E_USER_ERROR);
		}

		$this->_action = $action;


		if (!class_exists($controllerClass)) {
			$this->_httpError("404 Not found", "Controller class ('" . $controllerClass . "') does not exist.");
			return false;
		}


		$this->_controller = new $controllerClass;

		$actionMethod = 'action' . ucfirst($action);
		if (!method_exists($this->_controller, $actionMethod)) {
			$this->_httpError("404 Not found", "Action method ('" . $actionMethod . "') does not exist in controller ('" . $controllerClass . "').");
			return false;
		}

		$this->_controller->callActionMethod($actionMethod);

		return true;
	}

	private function _httpError($status, $errorMsg) {
		header("HTTP/1.1 $status");
		header("Status: $status");

		if (empty($_SERVER['QUERY_STRING'])) {
			$_SERVER['QUERY_STRING'] = "[EMPTY QUERY_STRING]";
		}

		echo '<h1>' . $status . '</h1>';
		echo '<p>' . $errorMsg . '</p>';
		echo '<p>Query string: ' . $_SERVER['QUERY_STRING'] . '</p>';

//		trigger_error($errorMsg, E_USER_ERROR);		
	}
	
	
	
	private function getBaseUrl(){
		
		$https = App::request()->isHttps();
		
		$url = $https ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
	
			
		if ((!$https && $_SERVER["SERVER_PORT"] != 80) || ($https && $_SERVER["SERVER_PORT"] != 443)){
			$url .= ":".$_SERVER["SERVER_PORT"];
		}
		
		$url .= $_SERVER['PHP_SELF'];
		
		return $url;

	}

	/**
	 * Generate a controller URL.
	 *
	 * @param string $route To controller. eg. addressbook/contact/submit
	 * @param array $params eg. ['id' => 1, 'someVar' => 'someValue']
	 * @return string
	 */
	public function buildUrl($route = '', $params = []) {
		
		$url = $this->getBaseUrl();
		
		if(!empty($route)){
			$params['r'] = $route;
		}
		
		if(count($params)){
			$queryParams = '?';

			foreach($params as $key=>$value){
				$queryParams .= $key.'='.urlencode($value).'&';
			}
			
			$url .= rtrim($queryParams,'&');
		}
		
		return $url;
	}

}
