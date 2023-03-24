<?php

namespace Technodrive\Core;


use Technodrive\Core\Errors\PluginNotFoundException;

abstract class AbstractController
{

    protected Request $request;

    protected Response $response;

    private Container $container;

    public function init(Request $request, Response $response, Container $container)
    {
        $this->response = $response;
        $this->request = $request;
        $this->container = $container;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    public function __call(string $name, array $arguments)
    {
        $plugins = $this->container->getConfiguration()->get('controller_plugins');
        if (isset($plugins['alias'][$name])) {
            $className = $plugins['alias'][$name];
            if (!isset($plugins['factories'][$className])) {
                throw new PluginNotFoundException('class not found in plugin configuration');
            }
        } elseif (isset($plugins['factories'][$name])) {
            $className = $plugins['factories'][$name];
        } else {
            throw new PluginNotFoundException('plugin not found');
        }

        $factory = new $plugins['factories'][$className]();
        $class = $factory($this->container);
        return $class();
    }

    public function run(ViewInterface $view)
    {
        $renderer = new ViewRenderer($view, $this->layout);
        $renderer->init($this->request, $this->response, $this->container);
        $renderer->render();
    }

}