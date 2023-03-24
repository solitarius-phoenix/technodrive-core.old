<?php

namespace Technodrive\Core;

use Technodrive\Core\Errors\ActionNotFoundException;
use Technodrive\Core\Errors\BadViewException;
use Technodrive\Core\Errors\ControllerNotFoundException;
use Technodrive\Core\Errors\FactoryNotInterfacedException;
use Technodrive\Core\Errors\FactoryNotProvidedException;
use Technodrive\Core\Errors\MissingParentController;
use Technodrive\Core\Interfaces\FactoryInterface;
use Technodrive\Core\View\Renderer\LayoutRenderer;
use Technodrive\Core\View\Renderer\ViewRenderer;

class Dispatcher
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
    }

    public function dispatch()
    {
        $routeData = $this->request->getRouteData();
        $controller = $this->getController($routeData['controller']);
        $controller->init($this->request, $this->response, $this->container);
        return $this->getAction($routeData['action'], $controller);
    }

    protected function getController(string $controllerName): AbstractController
    {
        $factories = $this->container->getConfiguration()->get('controllers')['factories'];

        if (
            !isset($controllerName)
            || !class_exists($controllerName)
        ) {
            throw new ControllerNotFoundException('Controller not provided or class missing');
        }

        if(! isset($factories[$controllerName])
            || ! class_exists($factories[$controllerName])
        ){
            throw new FactoryNotProvidedException('Factory not provided');
        }
        $factory = new $factories[$controllerName];

        if (! $factory instanceof FactoryInterface) {
            throw new FactoryNotInterfacedException('Factory must implements FactoryInterface');
        }

        $controller = $factory($this->container);
        //Maybe find a better way to have module's name
        $moduleParts = explode('\\', $controller::class);
        $this->container->addData('currentModule', array_shift($moduleParts));
        $controllerShortName = preg_replace('/Controller$/', '', array_pop($moduleParts));
        $this->container->addData('currentController', $controllerShortName);

        if(! $controller instanceOf AbstractController) {
            throw new MissingParentController('Controller must extend AbstractController');
        }

        return $controller;
    }

    protected function getAction(string $actionName, AbstractController $controller)
    {
        $this->container->addData('currentAction', $actionName);
        $actionName = strtolower($actionName) . 'Action';
        if(! method_exists($controller, $actionName)) {
            throw new ActionNotFoundException(sprintf('Action %1$s not found in controller %2$s', $actionName, $controller::class));
        }

        $view = $controller->$actionName();

        $viewRenderer = new ViewRenderer($view, $this->response, $this->container);
        $this->response->setBody($viewRenderer->render());
        $layoutRenderer = new LayoutRenderer($view, $this->response, $this->container);
        echo $layoutRenderer->render();
        die;
    }

}