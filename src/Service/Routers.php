<?php
namespace FifaRestfulPHP\Service;

use Klein\klein;
use Klein\Request;
use FifaRestfulPHP\IEnvironment;

class Routers
{
    private $klein;
    private $kleinRequest;

    // restful API endPoint 設定陣列
    private $routers = [
    // ["method" => "post", 'path' => "", "controller" => "", "responseMethod" => "", "canActivate" => "" ]
        ["method" => "get" , 'path' => "/team"              , "controller" => "FifaRestfulPHP\Controllers\TeamCtrl", "responseMethod" => "teamList"],
        ["method" => "get" , 'path' => "/logout"            , "controller" => "FifaRestfulPHP\Controllers\UserCtrl", "responseMethod" => "logout", "injectionService" => "FifaRestfulPHP\Service\Auth"],
        ["method" => "post", 'path' => "/login"             , "controller" => "FifaRestfulPHP\Controllers\UserCtrl", "responseMethod" => "login", "injectionService" => "FifaRestfulPHP\Service\Auth"],
        ["method" => "post", 'path' => "/register"          , "controller" => "FifaRestfulPHP\Controllers\UserCtrl", "responseMethod" => "register"],
        ["method" => "get" , 'path' => "/checkLogin"        , "controller" => "FifaRestfulPHP\Controllers\UserCtrl", "responseMethod" => "checkLogin", "injectionService" => "FifaRestfulPHP\Service\Auth"],
        ["method" => "get" , 'path' => "/teamDetail/[:code]", "controller" => "FifaRestfulPHP\Controllers\TeamCtrl", "responseMethod" => "teamDetail", "injectionService" => "FifaRestfulPHP\Service\Auth"]
    ];
    
    public function __construct()
    {
        $this->initSubDirectory(); // 若專案目錄是 "sub Directory" 則加入此函數設定$_SERVER['REQUEST_URI']

        $this->klein = new klein;
        foreach ($this->routers as $router) {
            $this->klein->respond($router['method'], $router['path'], function ($request, $resopnse) use ($router) {
				$controller = (isset($router["injectionService"]))? new $router['controller'](new $router["injectionService"]) : new $router['controller'];
				try {
					return $controller->{$router['responseMethod']}($request);
				} catch (\Exception $e) {
					$resopnse->code($e->getCode());
					return $e->getMessage();
				}
            });
		}
        $this->klein->dispatch($this->kleinRequest);
        
        // initSubDirectory function (2) content
        // $this->klein->dispatch();
    }

    private function initSubDirectory()
    {
        $this->kleinRequest = Request::createFromGlobals();
        $uri = $this->kleinRequest->server()->get('REQUEST_URI');
        $this->kleinRequest->server()->set('REQUEST_URI', substr($uri, strlen(IEnvironment::ROUTER_START)));

        // https://github.com/klein/klein.php/wiki/Sub-Directory-Installation
        //
        // (2)
        // This might also work,it doesn't need a custom request object
        // $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen(IEnvironment::ROUTER_START));
    }
}
