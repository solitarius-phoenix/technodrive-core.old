<?php

namespace Technodrive\Core;

/**
 *
 */
final class Container
{
    /**
     * @var Configuration
     */
    private  Configuration $configuration;

    private const DI = ['controllers', 'services'];

    /**
     * @var array
     */
    private $loadedClasses = [];

    /**
     * @var array
     * @todo translate into a class
     */
    private array $currentData;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
        $this->configuration->init();
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @param Configuration $configuration
     * @return self
     */
    public function setConfiguration(Configuration $configuration): self
    {
        $this->configuration = $configuration;
        return $this;
    }

    public function addData(string $key, mixed $value): void
    {
        $this->currentData[(string) $key] = $value;
    }

    public function getData(string $key)
    {
        return $this->currentData[$key];
    }

    public function get(string $key): mixed
    {
        if(isset($this->loadedClasses[$key])){
            return $this->loadedClasses[$key];
        }

        foreach (self::DI as $di) {
            $injection = $this->configuration->get($di);

            if (!isset($injection[$key])) {
                continue;
            }
            $factoryName = $injection[$key];
            $factory = new $factoryName();

            $this->loadedClasses[$key] = $factory($this);
            return $this->loadedClasses[$key];
        }
        $this->loadedClasses[$key] = $this->configuration->get($key);
        return $this->loadedClasses[$key];
    }
}