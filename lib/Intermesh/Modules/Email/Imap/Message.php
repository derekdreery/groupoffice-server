<?php

namespace Intermesh\Modules\Email\Imap;

use Intermesh\Core\AbstractObject;

class Message extends AbstractObject {

	private $mailbox;

	public function __construct(Mailbox $mailbox) {
		parent::__construct();

		$this->mailbox = $mailbox;
	}

	public static function createFromImapResponse(array $response) {
		
		$start = strpos($response[0], 'UID');
		$end = strpos($response[0], 'BODY');

		$line = substr($response[0], $start, $end - $start - 1);

		$arr = str_getcsv($line, ' ');

//			preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\((?:\\\\.|[^\\\)])*\)|\S+|/', $line, $matches);
//			var_dump($arr);

		for ($i = 0, $c = count($arr); $i < $c; $i++) {
			$name = strtolower($arr[$i]);

			$value = $arr[$i + 1];


			if (substr($value, 0, 1) == '(') {
				$values = [];

				do {

					$i++;

					$values[] = trim($arr[$i], '()');
				} while (substr($arr[$i], -1, 1) != ')' && $i < $c);


				$attr[$name] = $values;
			} else {

				$attr[$name] = $value;

				$i++;
			}
		}


		//headers

		$response[1] = str_replace("\r", "", trim($response[1]));
		$response[1] = str_replace("\n ", "", $response[1]);


		$lines = explode("\n", $response[1]);

		foreach ($lines as $line) {
			$parts = explode(':', $line);

			$name = str_replace('-', '_', strtolower(array_shift($parts)));

			$attr[$name] = trim(implode(':', $parts));
		}

		var_dump($attr);
	}

}
