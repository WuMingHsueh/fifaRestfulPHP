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
        ["method" => "post", 'path' => "/user/create", "controller" => "sqlSrvAPI\Controllers\Users", "responseMethod" => "createUser"],
    ];
    
    public function __construct()
    {
        $this->initSubDirectory(); // 若專案目錄是 "sub Directory" 則加入此函數設定$_SERVER['REQUEST_URI']

        $this->klein = new klein;
        foreach ($this->routers as $router) {
            $this->klein->respond($router['method'], $router['path'], function ($request, $resopnse) use ($router) {
                $controller = new $router['controller'];
                return $controller->{$router['responseMethod']}($request);
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
