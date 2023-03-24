<?php

namespace Technodrive\Core;

/**
 * class Bootstrap
 * initializes the framework
 */
class Bootstrap
{

    protected Container $container;


    /**
     *
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->onBootstrap();
    }

    /**
     * @return void
     * @todo
     */
    protected function onBootstrap()
    {

    }

    public function getContainer()
    {
        return $this->container;
    }
}