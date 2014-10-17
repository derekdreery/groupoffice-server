<?php
namespace Intermesh\Modules\Dropbox;

use Dropbox as dbx;
use Dropbox\AppInfo;
use Dropbox\Client;
use Intermesh\Core\App;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Modules\Dropbox\Model\Account;

class Config{
	const API_KEY = 'hv5gbqnwa7kafj8';
	const API_SECRET = 'veeyxk4bvxmevi7';
	
	/**
	 * 
	 * @return AppInfo
	 */
	public static function getAppInfo(){
		return new dbx\AppInfo(
				self::API_KEY, 
				self::API_SECRET);
	}
	
	/**
	 * 
	 * @return Account
	 */
	public static function getAccount(){
		$accessTokenModel = Account::findByPk(User::current()->id);
		if(!$accessTokenModel){
			$accessTokenModel = new Account();
			$accessTokenModel->ownerUserId = User::current()->id;
		}
		
		return $accessTokenModel;
	}
	
	/**
	 * 
	 * @return Client
	 */
	public static function getClient(){
		return self::getAccount()->getClient();
	}
}