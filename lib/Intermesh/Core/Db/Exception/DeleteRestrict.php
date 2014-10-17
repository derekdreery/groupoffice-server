<?php
namespace Intermesh\Core\Db\Exception;

use Exception;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\Relation;

/**
 * Throw when an operation was forbidden.
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class DeleteRestrict extends Exception
{
	public function __construct(AbstractRecord $model, Relation $relation) {
		parent::__construct("model: ".$model->className().' relation: '.$relation->getName());
	}
}