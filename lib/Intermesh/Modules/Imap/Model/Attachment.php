<?php

namespace Intermesh\Modules\Imap\Model;

use Flow\Exception;
use Intermesh\Core\App;
use Intermesh\Core\Db\AbstractRecord;
use Intermesh\Core\Db\RelationFactory;
use Intermesh\Core\Fs\File;


/**
 * The Attachment model
 *
 * @property int $id
 * @property int $messageId
 * @property string $filename
 * @property string $contentType
 * @property string $contentId
 * @property boolean $inline
 * 
 * @property Message $message
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Attachment extends AbstractRecord {
	
	private $_newFile;
	
	protected static function defineRelations(RelationFactory $r) {
		return [
			$r->belongsTo('message', Message::className(), 'messageId'),
		];
	}
	
	/**
	 * Set file on the filesystem as the data of this file model
	 * 
	 * @param File $file
	 */
	public function setFile(File $file) {
		$this->_newFile = $file;

		$this->size = $file->getSize();		
	}

	public function save() {

		$success = parent::save();

		if ($success && isset($this->_newFile)) {
			$destinationFile = $this->_getFilesystemFile();

			//make sure folder exists
			$destinationFile->getFolder()->create();

			if (!$this->_newFile->move($destinationFile)) {
				throw new Exception("Failed to set file data!");
			}

			unset($this->_newFile);
		}

		return $success;
	}
	
	/**
	 * Output the file to the browser for download
	 */
	public function output() {
		
		header('Content-Type: ' . $this->contentType);
		header('Content-Disposition: inline; filename="' . $this->filename . '"');
		header('Content-Length: ' . $this->size);
		
		$this->_getFilesystemFile()->output();
	}
	
	
	
	/**
	 * 
	 * @return File
	 * @throws Exception
	 */
	private function _getFilesystemFile() {

		if (!$this->id) {
			throw new Exception("Save file first!");
		}

		return App::config()->getDataFolder()->createFile('imap/' . $this->messageId . '/' . $this->id);
	}
	
	public function getUrl(){
		return App::router()->buildUrl('intermesh/imap/message/attachment',['id' => $this->id]);
	}
}