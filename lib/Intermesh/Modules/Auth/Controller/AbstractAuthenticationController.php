<?php
namespace Intermesh\Modules\Auth\Controller;

use Intermesh\Core\Controller\AbstractController;
use Intermesh\Core\Db\Query;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Modules\Model\Module;


/**
 * Abstract controller that requires a user to be logged in to access it.
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class AbstractAuthenticationController extends AbstractController{
	/**
	 * Check if a user is logged in
	 * 
	 * @var boolean 
	 */
	protected $authenticationRequired = true;
	
	protected $checkModulePermission = false;
	
	public function __construct() {
		
		$this->authenticate();
		
		parent::__construct();		
	}	
	
	private function _checkModulePermission(){
		
		if(!$this->checkModulePermission){
			return true;
		}  else {
			$module = Module::find(['name'=>$this->moduleName()])->single();

			if(!$module){
				throw new \Exception("Module '".$this->moduleName()."' not found in database.");
			}

			return $module->checkPermission('useAccess');
		}
	}
	
	protected function authenticate() {
		if ($this->authenticationRequired) {
			if (!User::current() || !$this->_checkModulePermission()) {
				header('Content-Type: text/html;charset=UTF-8');
				header("HTTP/1.0 403 Forbidden");
				header("Status: 403 Forbidden");
				
				echo '<h1>403 Forbidden</h1>';
				echo '<p>You must be logged in to view this page</p>';
				exit();
			}
		}
	}
}