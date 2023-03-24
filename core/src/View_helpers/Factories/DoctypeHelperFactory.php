<?php

namespace Technodrive\Core\View_helpers\Factories;

use Technodrive\Core\Container;
use Technodrive\Core\Interfaces\FactoryInterface;
use Technodrive\Core\View_helpers\DoctypeHelper;

/**
 * DoctypeHelperFactory
 */
class DoctypeHelperFactory implements FactoryInterface
{

    /**
     * @param Container $container
     * @param string|null $controllerName
     * @return DoctypeHelper
     */
    public function __invoke(Container $container, ?string $controllerName = null)
    {
        return new DoctypeHelper();
    }

}