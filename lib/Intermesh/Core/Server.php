<?php
namespace Intermesh\Core;

/**
 * Server information class.
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Server{
	public function isWindows(){
		return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
	}	
}