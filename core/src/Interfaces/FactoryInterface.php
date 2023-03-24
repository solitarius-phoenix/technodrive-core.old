<?php

namespace Technodrive\Core\Interfaces;

use Technodrive\Core\Container;

/**
 *
 */
interface FactoryInterface
{
    /**
     * @param Container $container
     * @param string|null $controllerName
     * @return mixed
     */
    public function __invoke(Container $container, ?string $controllerName = null);
}