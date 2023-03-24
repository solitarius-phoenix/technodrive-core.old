<?php

namespace Technodrive\Core\Controllers_plugins\Factories;


use Technodrive\Core\Container;
use Technodrive\Core\Controllers_plugins\RedirectPlugin;
use Technodrive\Core\Interfaces\FactoryInterface;
use Technodrive\Core\Services\NavigationService;

class RedirectPluginFactory implements FactoryInterface
{
    
    public function __invoke(Container $container, ?string $controllerName = null)
    {
        $NavigationService = $container->get(NavigationService::class);
        return new RedirectPlugin($NavigationService);
    }

}