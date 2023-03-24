<?php

namespace Technodrive\Core;

use Technodrive\Core\Errors\PageNotFoundException;

class Router
{

    protected Container $container;

    protected Request $request;
    protected Response $response;

    protected array $routes;

    public function __construct(Container $container, Request $request, Response $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;

        //@todo sera à mettre en cache !important perte de perfs importantes
        $this->createRoutes();
    }

    protected function createRoutes(): void
    {
        $routes = $this->container->getConfiguration()->get('Routes');
        foreach($routes as &$route) {
            //Make sure that we'll have one and only one slash before and after string
            $path = '/' . trim($route['path'], '/') . '/';
            preg_match_all('#\:([a-zA-Z0-9]*)\/#', $path, $matches);
            $route['get-keys'] = $matches[1];
            $pattern = '#^' . preg_quote($path) . '$#';
            $pattern = preg_replace('#\\\:([a-zA-Z0-9._-]*)\/#', '([a-zA-Z0-9._-]*)/', $pattern);
            $route['pattern'] = $pattern;
        }
        $this->routes = $routes;
    }

    public function route(): void
    {
        $uri = '/' . trim($this->request->getUri(), '/') . '/';
        foreach ($this->routes as $key=>$candidate) {
            if(preg_match($candidate['pattern'],$uri, $matches))
            {
                array_shift($matches);
                $gets = [];
                if( count($candidate['get-keys']) > 0 ) {
                    $gets = array_combine($candidate['get-keys'], $matches);
                }
                $this->request->setGetData($gets);
                $this->request->setRouteMatched($key);
                $this->request->setRouteData($candidate);
                return;
            }
        }

        // route not found throw an error catchable
        throw new PageNotFoundException('Page not found');

    }

}