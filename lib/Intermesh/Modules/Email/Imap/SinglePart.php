<?php

namespace Intermesh\Modules\Email\Imap;

class SinglePart extends AbstractPart{

	
	public $id;
	
	public $description;
	
	public $encoding;
	
	public $size;
	
	public $lines;
	
	public $md5;
	
	public $disposition;
	
	public $language;
	
	public $location;
	

	
	public function __construct(Message $message, $partNumber, array $struct) {
		
		$this->message = $message;
		
		$this->partNumber = $partNumber;
		
		$atts = array('type', 'subtype', 'params', 'id', 'description', 'encoding',
						'size', 'lines', 'disposition', 'md5', 'language', 'location');
		
		for($i = 0, $c = count($struct); $i < $c; $i++) {
			$this->{$atts[$i]} = $this->_parseValue($struct[$i]);
		}
	}
	
	public function getFilename(){
		if(!empty($this->params['name'])){
			return Utils::mimeHeaderDecode($this->params['name']);
		}else if(isset($this->disposition)){
			$props = array_shift($this->disposition);
			
			if($props['filename']){
				return Utils::mimeHeaderDecode($props['filename']);
			}
		}
		
		return false;
	}
	
	
	private function _parseValue($v){
		if(is_array($v)){

			$value = [];
			for($n = 0, $c2 = count($v); $n < $c2; $n++){
				$value[$v[$n++]] = $this->_parseValue($v[$n]);					
			}
			
			return $value;

		}  else {
			return $v;
		}
	}
	
	
	public function getDataDecoded(){
		switch($this->encoding){
			case 'base64':
					return base64_decode($this->getData());
				
			case 'quoted-printable':
					return quoted_printable_decode($this->getData());
			default:
				
				return $this->getData();
		}
	}
	
	public function output(){
		
		header('Content-Type: '.$this->type.'/'.$this->subtype);
		header('Content-Disposition: inline; filename='.$this->getFilename());
		
		$streamer = new Streamer(fopen("php://output",'w'), $this->encoding);
		
		$this->getData(true, $streamer);
	}	
}