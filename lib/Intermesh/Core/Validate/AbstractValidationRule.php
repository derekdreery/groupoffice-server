<?php

namespace Intermesh\Core\Validate;

use Intermesh\Core\AbstractObject;
use Intermesh\Core\Model;

/**
 * Abstract validator for the ActiveRecord class
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class AbstractValidationRule extends AbstractObject{
	
	private $_id;
	
	/**
	 * Set to an error code if it doesn't validate.
	 * 
	 * @var string 
	 */
	protected $errorCode="";
	
	
	/**
	 * Set extra error information to provide to the client
	 * 
	 * @var array 
	 */
	protected $errorInfo=array();
	
	/**
	 * Creates a new validation rule
	 * 
	 * @param string $id In most cases it should be set to an attribute name.
	 */
	public function __construct($id) {
		$this->_id=$id;
		
		parent::__construct();
	}
	
	/**
	 * Get the validation rule identifier. In most cases it should be set to an attribute name.
	 * @return string $id
	 */
	public function getId(){
		return $this->_id;
	}
	
	/**
	 * Get's the error code if validation failed.
	 * 
	 * @return string
	 */
	public function getErrorCode(){
		return $this->errorCode;
	}
	
	/**
	 * Get's the error code if validation failed.
	 * 
	 * @return string
	 */
	public function getErrorInfo(){
		return $this->errorInfo;
	}
	
	/**
	 * Validate this rule on a model
	 * 
	 * @param Model $model The model to apply the rule on.
	 * @return bool
	 */
	abstract public function validate(Model $model);
	
}