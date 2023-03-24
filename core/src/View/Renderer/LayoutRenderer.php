<?php

namespace Technodrive\Core\View\Renderer;

use Technodrive\Core\Container;
use Technodrive\Core\Errors\TemplateNotFoundException;
use Technodrive\Core\Response;
use Technodrive\Core\View\Model\ViewModel;

class LayoutRenderer extends AbstractTemplateRenderer
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

        return ROOT_PATH . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR .
            'View' . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'layout.phtml';
    }

    public function render(): string
    {
        $this->view->setVariable('content', $this->response->getBody());
        return parent::render();
    }

}