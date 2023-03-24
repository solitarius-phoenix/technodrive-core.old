<?php

namespace Technodrive\Core\Services\Factory;

use Technodrive\Core\Container;
use Technodrive\Core\Services\NavigationService;

class NavigationServiceFactory implements \Technodrive\Core\Interfaces\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(Container $container, ?string $controllerName = null)
    {
        $routes = $container->getConfiguration()->get('Routes');
        return new NavigationService($routes);
    }
}