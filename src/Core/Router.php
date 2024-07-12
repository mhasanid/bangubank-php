<?php

namespace App\Core;

class Router {
    private $routes = [];

    public function add($route, $method, $handler) {
        $this->routes[] = [$route, $method, $handler];
    }

    public function dispatch() {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // echo $url;
    

        foreach ($this->routes as $route) {
            list($routePattern, $routeMethod, $handler) = $route;
            if ($routePattern === $url && $routeMethod === $method) {
                $controller = new $handler[0];
                return call_user_func([$controller, $handler[1]]);
            }
        }

        http_response_code(404);
        echo "Page not found";
    }
}
