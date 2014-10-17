<?php
namespace Intermesh\Core\Validate;

use Intermesh\Core\Model;

/**
 * Validates an email attribute of the ActiveRecord
 * 
 * eg. in ActiveRecord do:
 * 
 * <p>eg. in ActiveRecord do:</p>
 * 
 * <code>
 * protected static function defineValidationRules() {
 *	
 *		self::getColumn('username')->required=true;
 *		
 *		return array(
 *				new ValidateEmail("email"),
 *				new ValidateUnique('email'),
 *				new ValidateUnique('username'),
 *        new ValidatePassword('password', 'passwordConfirm') //Also encrypts it on success
 *		);
 *	}
 * </code>
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class ValidateEmail extends AbstractValidationRule {
	
	private $_regex = "/^[a-z0-9\._\-+\&]+@[a-z0-9\.\-_]+\.[a-z]{2,6}$/i";
	
	public function validate(Model $model) {
		if(preg_match($this->_regex, $model->{$this->getId()})){
			return true;
		}else
		{
			$this->errorCode='emailInvalid';
		}
	}
}