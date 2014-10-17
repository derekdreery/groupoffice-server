<?php
namespace Intermesh\Modules\Auth\Controller;

use Intermesh\Modules\Auth\Model\Token;
use Intermesh\Modules\Auth\Model\User;
use Intermesh\Core\App;
use Intermesh\Core\Controller\AbstractController;

/**
 * The controller that handles authentication
 *
 * @copyright (c) 2014, Intermesh BV http://www.intermesh.nl
 * @author Merijn Schering <mschering@intermesh.nl>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class AuthController extends AbstractController{

	/**
	 * Logs the current user out.
	 */
	public function actionLogout(){

		App::session()->end();
		echo $this->view->render('json', array('success'=>true));

	}

	/**
	 * Logs the current user in.
	 *
	 * <p>Sample JSON post:</p>
	 *
	 * <code>
	 * {
	 *	"username": "user",
	 *	"password": "secret"
	 * }
	 * </code>
	 *
	 * @returns JSON {"userId": "Current ID of user", "securityToken": "token required in each request"}
	 */
	public function actionLogin(){

		$user = User::login(App::request()->post['username'], App::request()->post['password']);
		
		$response = array(
				'success'=>$user!==false
		);

		if($response['success']){	
			//todo remember for different clients
			if(App::request()->post['remember']){				
				Token::generateSeries($user->id);				
			}
		}else
		{
			$response['errors']=array('badlogin');
		}

		echo $this->view->render('json', $response);
	}
}