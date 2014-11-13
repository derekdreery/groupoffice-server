<?php

namespace Intermesh\Modules\Email\Imap;

abstract class AbstractPart {

	public $partNumber;
	public $type = 'multipart';
	public $subtype;
	public $params;

	/**
	 *
	 * @var Message 
	 */
	public $message;

	protected function incrementPartNumber($partNumber) {
		if (!strstr($partNumber, '.')) {
			$partNumber++;
		} else {
			$parts = explode('.', $partNumber);
			$parts[(count($parts) - 1)] ++;
			$partNumber = implode('.', $parts);
		}
		return $partNumber;
	}

	public function getData($peek = true, Streamer $streamer = null) {

		$peek_str = $peek ? '.PEEK' : '';

		$command = "UID FETCH " . $this->message->uid . " BODY" . $peek_str . "[" . $this->partNumber . "]";

		$conn = $this->message->mailbox->connection;		

		$conn->sendCommand($command);
		$response = $conn->getResponse($streamer);
		return $response[0][1];
		
	}
	
	

}
