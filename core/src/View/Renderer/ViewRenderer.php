<?php

namespace Technodrive\Core\View\Renderer;

use Technodrive\Core\Container;
use Technodrive\Core\Errors\TemplateNotFoundException;
use Technodrive\Core\Response;
use Technodrive\Core\View\Model\ViewModel;

class ViewRenderer extends AbstractTemplateRenderer
{
    protected ViewModel $view;
    protected Response $response;
    protected Container $container;

    protected string $template;

    public function __construct(ViewModel $view, Response $response, Container $container)
    {
        parent::__construct($view, $response, $container);
        $this->template = $this->view->getTemplate();
    }

    protected function getDefaultTemplate(): string
    {
        $module = $this->container->getData('currentModule');
        $controller = $this->container->getData('currentController');
        $action = $this->container->getData('currentAction');

        return ROOT_PATH . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR .
            'View' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action . '.phtml';
    }

}