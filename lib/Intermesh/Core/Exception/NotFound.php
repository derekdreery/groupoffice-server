<?php
namespace Intermesh\Core\Exception;

/**
 * Thrown when an item was not found
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class NotFound extends HttpException
{
	public function __construct() {
		parent::__construct(404);
	}
}