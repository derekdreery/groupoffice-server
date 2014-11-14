<?php

namespace Intermesh\Modules\Email\Imap;

use Intermesh\Core\Model;

/**
 * AbstractPart class
 * 
 * Base class for a single and multipart of a MIME message
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class AbstractPart extends Model {

	/**
	 * IMAP part number.
	 * 
	 * eg. "1" in a single part or "1.1" in multipart messages
	 * 
	 * @var string 
	 */
	public $partNumber;
	
	/**
	 * Type of the part. 
	 * 
	 * eg. "text", "image", "multipart"
	 * 
	 * @var string 
	 */
	public $type = 'multipart';
	
	/**
	 * Subtype
	 * 
	 * eg. "plain", "html", "jpeg", "mixed"
	 * 
	 * @var string 
	 */
	public $subtype;
	
	/**
	 * Array of extra parameters
	 * 
	 * @var array 
	 */
	public $params;

	/**
	 * The message this part belongs to
	 * 
	 * @var Message 
	 */
	public $message;

	/**
	 * Increments the part number. 
	 * 
	 * eg. 1.1 becomes 1.2
	 * 
	 * @param string $partNumber
	 * @return string
	 */
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

	/**
	 * Get the data of this part
	 * 
	 * @param boolean $peek Don't mark message as read
	 * @param \Intermesh\Modules\Email\Imap\Streamer $streamer
	 * @return string
	 */
	public function getData($peek = true, Streamer $streamer = null) {

		$peek_str = $peek ? '.PEEK' : '';

		$command = "UID FETCH " . $this->message->uid . " BODY" . $peek_str . "[" . $this->partNumber . "]";

		$conn = $this->message->mailbox->connection;		

		$conn->sendCommand($command);
		$response = $conn->getResponse($streamer);
		
		return $response[0][1];		
	}
}