<?php

namespace Intermesh\Modules\Email\Imap;

class MultiPart extends AbstractPart{
	
	
	
	
	public $parts=[];
	

	
	
	public function __construct(Message $message, $partNumber, array $struct) {
		
		if(!empty($partNumber)){
			$partNumber .= ".0";
		}
		
		
		
		$this->message = $message;
		
		$this->partNumber = $partNumber;
		
		
		while($part = array_shift($struct)){			
			if(is_array($part)){
				
				$partNumber = $this->incrementPartNumber($partNumber);
				
				if(is_array($part[0])){
					$this->parts[] = new MultiPart($message, $partNumber, $part);
				}else
				{
					$this->parts[] = new SinglePart($message, $partNumber, $part);
				}
			}else
			{
				break;
			}
		}
		
		$this->subtype=$part;
		$this->params=array_shift($struct);
	}
	
}