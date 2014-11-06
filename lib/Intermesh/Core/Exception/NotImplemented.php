<?php
namespace Intermesh\Core\Exception;

/**
 * Thrown when a method is not implemented
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class NotImplemented extends HttpException
{
	public function __construct($message=null) {
		parent::__construct(501, $message);
	}
}