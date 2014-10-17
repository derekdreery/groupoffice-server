<?php
namespace Intermesh\Core\View;

/**
 * Abstract view class to render output in controllers
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class AbstractView extends \Intermesh\Core\AbstractObject{
	
	/**
	 * Extra headers to be set in the response
	 * 
	 * @var array 
	 */
	public $extraHeaders=array();
	
	/**
	 * The Content-Type header setting
	 * 
	 * @var string 
	 */
	public $contentType = 'Content-Type: text/html; charset=UTF-8';
	
	abstract public function render($viewName, $data);
	
	/**
	 * Default headers to send. 
	 */
	protected function headers(){
		
		if(!headers_sent()){
			header($this->contentType);
			//XSS prevention headers for IE and Chrome
			header('X-XSS-Protection: 1; mode=block');
			header('X-Content-Type-Options: nosniff');

			foreach($this->extraHeaders as $header){
				header($header);
			}
		}
	}
}
