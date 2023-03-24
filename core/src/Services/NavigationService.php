<?php

namespace Technodrive\Core\Services;

/**
 *
 */
class NavigationService
{

    /**
     * @var array
     */
    protected array $routes;

    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param string $routeName
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function createUrl(string $routeName, array $params): string
    {
        if(!isset($this->routes[$routeName])){
            throw new \Exception('route not found in config');
        }
        $route = $this->routes[$routeName];
        $path = $route['path'];
        $explPath = explode('/', $path);
        $explUrl = [];
        foreach($explPath as $element){
            if(empty($element) || $element[0] !== ':'){
                $explUrl[] = $element;
                continue;
            }
            $element = substr($element, 1);
            if(!isset($params[$element])){
                continue;
            }
            $explUrl[] = $params[$element];
        }
        return implode('/', $explUrl);

    }


}