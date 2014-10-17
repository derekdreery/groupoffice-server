<?php

namespace Intermesh\Core\Controller;

use Exception;
use Intermesh\Core\AbstractObject;
use Intermesh\Core\App;
use Intermesh\Core\Exception\MissingControllerActionParameter;
use Intermesh\Core\Http\Router;
use Intermesh\Core\View\JsonView;
use ReflectionMethod;

/**
 * Abstract controller class.
 *
 * The router routes requests to controller actions.
 * All controllers must extend this or a subclass of this class.
 *
 * @see Router The router routes requests to controllers
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class AbstractController extends AbstractObject {

	/**
	 * The view renderer
	 *
	 * @var JsonView
	 */
	protected $view = 'json';

	public function __construct() {

		if (is_string($this->view)) {
			$name = '\\Intermesh\\Core\\View\\' . ucfirst($this->view) . 'View';
			$this->view = new $name();
		}

		//Handles preflight OPTIONS request
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

			$this->view->render('options');

			App::debug("OPTIONS request");
			exit(0);
		}
	}


	/**
	 * Get the route to this controller. Eg.
	 *
	 * route = intermesh/addressbook/contact
	 *
	 * @param string $actionMethodName The name of the action method. eg. "actionStore".
	 * @return string
	 */
	public static function getRoute($actionMethodName=''){
		$arr = explode('\\',  get_called_class());

		$route=lcfirst($arr[0]).'/'.lcfirst($arr[2]).'/'.str_replace('Controller','',lcfirst($arr[4]));

		if($actionMethodName!=''){
			$route .= '/'.lcfirst(substr($actionMethodName,6));
		}

		return $route;
	}

	/**
	 * If you declare it as actionMethod($test1, $test2, $hasDefault=true) then
	 * the named parameters will be taken from the $_GET args.
	 *
	 * @param string $methodName
	 * @return mixed Action method return value
	 * @throws Exception If a required parameter is missing from the $_REQUEST args
	 */
	public function callActionMethod($methodName){

		try{
			$method = new ReflectionMethod($this, $methodName);

			$rParams = $method->getParameters();

			$data = App::request()->get;

	//		$param = new ReflectionParameter();
			if(count($rParams)==0){
				return $this->$methodName();
			}else{
				//call method with all parameters from the $_REQUEST object.
				$methodArgs = array();
				foreach($rParams as $param){
					if(!isset($data[$param->getName()]) && !$param->isOptional()){
						throw new MissingControllerActionParameter("Missing argument '".$param->getName()."' for action method '".get_class ($this)."->".$methodName."'");
					}

					$methodArgs[]=isset($data[$param->getName()]) ? $data[$param->getName()] : $param->getDefaultValue();

				}
				return call_user_func_array(array($this, $methodName),$methodArgs);
			}
		}catch(Exception $e){
			echo $this->view->render('exception', $e);
		}
	}
}
