<?php

namespace Intermesh\Core\REST;

use Exception;
use Intermesh\Core\App;

class Router {

	private function getModuleRoutes($module) {

		$className = "\\Intermesh\\Modules\\" . $module . "\\" . $module . "Module";


		if (!class_exists($className)) {
			throw new Exception("Module (" . $className . ") has no routes!");
		} else {
			return $className::getRoutes();
		}
	}
	
	/**
	 * The current route. 
	 * 
	 * eg. /contacts/1
	 * 
	 * @var string 
	 */
	public $route;

	public function run() {

//		try {

			$this->route = isset(App::request()->get['r']) ? App::request()->get['r'] : false;

			$this->_routeParts = explode('/', $this->route);

			$module = ucfirst($this->_routeParts[0]);

			$routes = $this->getModuleRoutes($module);

			$this->walkRoute($routes);
//		} catch (\Exception $e) {
//			
//		}
	}

	/**
	 * Get the params passed in a route.
	 * 
	 * eg. /contacts/1 where 1 is a "contactId" would return ["contactId" => 1];
	 * 
	 * @var array
	 */
	public $routeParams = [];
	
	/**
	 * The configuration of the current route
	 * 
	 * eg.
	 * 
	 * 'contacts' => [
						'routeParams' => ['contactId'], 
						'controller' => "Intermesh\Core\Controller\RESTController",						
						'children' => [
								'thumb' => [
									'routeParams' => ['contactId'],
									'controller' => "Intermesh\Contacts\Controller\Contact",
									'actions' => ['thumb', 'original','upload'],
									'args' => ['modelName' => Contact::className(), 'primaryKeyName' => 'contactId'],
								]
							]
						]
	 * 
	 * @var array
	 */
	
	public $routeConfig;
	
	private $_routeParts;

	

	private function walkRoute($routes) {

		$routePart = array_shift($this->_routeParts);

		foreach ($routes as $path => $options) {
			if ($routePart === $path) {
				$this->getParams($options);

				if (!empty($this->_routeParts)) {
					if (!isset($options['children'])) {
						$options['children'] = [];
					}
					return $this->walkRoute($options['children']);
				} else {

					if (!isset($options['controller'])) {
						throw new Exception("No controller defined for this route!");
					}

					if (!isset($options['constructorArgs'])) {
						$options['constructorArgs'] = [];
					}
					
					$this->routeOptions = $options;

					$controller = new $options['controller']($this);
					return $controller->run();
				}
			}
		}

		throw new Exception("Route $routePart not found! " . var_export($routes, true));
	}

	private function getParams($options) {
		if (!empty($options['routeParams'])) {
			foreach ($options['routeParams'] as $paramName) {
				$this->routeParams[$paramName] = array_shift($this->_routeParts);
			}
		}
	}

	private function getBaseUrl() {

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

		if (!empty($route)) {
			$url .= '?r='.$route;
		}

		if (count($params)) {
			$queryParams = empty($route) ? '?' : '&';

			foreach ($params as $key => $value) {
				
				$queryParams .= $key . '=' . urlencode($value) . '&';
			}

			$url .= rtrim($queryParams, '&');
		}

		return $url;
	}

}
