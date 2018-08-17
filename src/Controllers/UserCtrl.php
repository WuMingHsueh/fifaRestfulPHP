<?php
namespace FifaRestfulPHP\Controllers;

use FifaRestfulPHP\Models\User;
use FifaRestfulPHP\Service\Auth;

class UserCtrl
{
    private $authService ;

    public function __construct(Auth $auth = null)
    {
        $this->authService = $auth;
    }
    
    public function checkLogin()
    {
        if ($this->authService->isLoggined()) {
			return json_encode(['loginStatus' => true, 'user' => $this->authService->getLoginUser(), 'token' => $this->authService->getToken()], JSON_UNESCAPED_UNICODE);
		} else {
			$message = json_encode(['loginStatus' => false, 'msg' => "Please login to access this information"]);
			throw new \Exception($message, 500);
		}
    }
    
    public function login()
    {
		$input = \json_decode(file_get_contents("php://input"), true);
		$dbResult = User::find($input['username']);
		
		if (!is_null($dbResult) and password_verify($input['password'], $dbResult->user_pwd)) {
			$this->authService->loginProcess($input['username']);
			return json_encode([
				'loginStatus' => true,
				'user'        => $input['username'],
				'token'       => $this->authService->getToken()
			], JSON_UNESCAPED_UNICODE);
		} else {
			$message = json_encode(['loginStatus' => false, 'msg' =>'Unable to login']);
			throw new \Exception($message, 500);
		}
    }
    
    public function logout()
    {
		$this->authService->logoutProcess();
		return json_encode(['msg' => 'logout success']);
    }
    
    public function register()
    {
		$userInput = json_decode(file_get_contents('php://input'), true);
		$userInput['userPwd'] = password_hash($userInput['userPwd'], PASSWORD_DEFAULT);

		if ($this->checkAccountExist($userInput['userId']))
		{
			throw new \Exception(json_encode(['msg' => "your account already exist"]), 500);
		}

		User::create([
			'user_id'   => $userInput['userId'],
			'user_pwd'  => $userInput['userPwd'],
			'user_name' => $userInput['userName'],
		]);
		return json_encode(['msg' => "create user {$userInput['userId']} susscess"], JSON_UNESCAPED_UNICODE);
	}
	
	private function checkAccountExist($userId)
	{
		return !is_null(User::find($userId));
	}
}
