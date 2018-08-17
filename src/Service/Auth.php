<?php
namespace FifaRestfulPHP\Service;

class Auth
{
	private $tokenLength = 13;

	public function isLoggined()
	{
		session_start();
		return (isset($_SESSION['user']) and isset($_SESSION['token']));
	}

	public function loginProcess(string $user)
	{
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['token'] = $this->generateToken();
	}

	public function logoutProcess()
	{
		session_start();
		session_destroy();
	}

	public function getLoginUser()
	{
		return $_SESSION['user'] ?? null;
	}

	public function getToken()
	{
		return $_SESSION['token'] ?? null;
	}

	private function generateToken()
	{
		$stringContainer = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
		shuffle($stringContainer);
		return implode("", array_slice($stringContainer, 0, $this->tokenLength));
	}
}
