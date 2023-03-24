<?php

namespace Technodrive\Core\View\Renderer;

use Technodrive\Core\Container;
use Technodrive\Core\Errors\PluginNotFoundException;
use Technodrive\Core\Errors\TemplateNotFoundException;
use Technodrive\Core\Response;
use Technodrive\Core\View\Model\ViewModel;

abstract class AbstractTemplateRenderer
{

    protected ViewModel $view;
    protected Response $response;
    protected Container $container;

    protected string $template;

    public function __construct(ViewModel $view, Response $response, Container $container)
    {
        $this->view = $view;
        $this->response = $response;
        $this->container = $container;
    }

    public function __get(string $name): string
    {
        if (!$this->view->hasVariable($name)) {
            return '';
        }

        return $this->view->getVariable($name);
    }

    public function __isset(string $name)
    {
        return $this->view->hasVariable($name);
    }

    public function __call(string $name, array $params)
    {
        $plugins = $this->container->getConfiguration()->get('view_helpers');
        if(!isset($plugins['alias'][$name])){
            throw new PluginNotFoundException('plugin not found ' . $name);
        }
        $className = $plugins['alias'][$name];
        if(!isset($plugins['factories'][$className])){
            throw new PluginNotFoundException('class not found in plugin configuration');
        }
        $factory = new $plugins['factories'][$className]();

        $class = $factory($this->container);

        return $class();
    }

    protected function checkTemplate(string $template): void
    {
        if(! file_exists($template)) {
            throw new TemplateNotFoundException(sprintf('Can\'t find the template file : %1$s', $template));
        }
    }

    public function render()
    {
        $template = $this->template;
        if($template === ''){
            $template=$this->getDefaultTemplate();
        }

        $this->checkTemplate($template);

        ob_start();
        require_once $template;
        return ob_get_clean();

    }

}