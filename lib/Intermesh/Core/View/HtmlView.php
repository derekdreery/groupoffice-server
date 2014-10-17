<?php
namespace Intermesh\Core\View;

use Exception;

/**
 * Simple HTML view renderer
 * 
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class HtmlView extends AbstractView{
	
	/**
	 * The Content-Type header setting
	 * 
	 * @var string 
	 */
	public $contentType = 'Content-Type: text/html;charset=utf-8';
	
	public function render($viewName, $data) {
		
		$this->headers();
		
		$fn = "render".$viewName;
		return $this->$fn($data);
	}
	
	private function renderOptions($data){
		exit();
	}
	
	private function renderHtml($html){
		echo $html;
	}


	private function renderException(Exception $e){
		
		echo "<h1>ERROR:</h1>";
		echo '<pre>'.$e.'</pre>';
	}

}