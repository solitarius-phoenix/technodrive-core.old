<?php

namespace Technodrive\Core;


use Technodrive\Core\Errors\BadConfigException;
use Technodrive\Core\Errors\ControllerNotFoundException;
use Technodrive\Core\Interfaces\Module;

/**
 *
 */
class Configuration
{

    protected const DI = ['controllers', 'service_managers'];

    /**
     * @var array
     */
    protected array $configuration = [];
    /**
     * @var array
     */
    protected array $moduleList = [];
    
    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @return void
     * @throws \Exception
     */
    public function init(): void
    {
        $this->moduleList = $this->getModuleList();
        $this->configuration = $this->getConfiguration();
    }
    

    /**
     * @param $element
     * @return array|string
     */
    public function get($element): array|string
    {
        if(isset($this->configuration[$element])) {
            return $this->configuration[$element];
        }

        foreach (self::DI as $di) {
            if(! isset($this->configuration[$di])){
                continue;
            }
            if(isset($this->configuration[$di]['factories'][$element])) {
                return $this->configuration[$di]['factories'][$element];
            }
        }
        throw new \Exception(sprintf('key %1$s doesn\'t exists in configuration', $element ));
    }

    /**
     * @param string $key
     * @param mixed $configuration
     * @return void
     */
    public function add(string $key, mixed $configuration): void
    {
        $this->configuration[(string) $key] = $configuration;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getModuleList(): array
    {
        $fileName = CONFIG_PATH . DIRECTORY_SEPARATOR . 'modules.php';
        if (!file_exists($fileName)) {
            throw new \Exception(sprintf('module file not found in %1$s', $fileName));
        }

        return include_once $fileName;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getConfiguration(): array
    {
        //@todo gestion des caches, trop lourd pour être chargé à chaque page
        $configuration = [];

        $mainConfigPath = CONFIG_PATH . DIRECTORY_SEPARATOR . 'autoload';
        $excludes = ['.', '..', 'master.config.php'];
        if (is_dir($mainConfigPath)) {
            $file = $mainConfigPath . DIRECTORY_SEPARATOR . 'master.config.php';
            if (file_exists($file)) {
                $content = include_once $file;
                if (!is_array($content)) {
                    throw new \Exception(sprintf('%1 must return an array', $file));
                }
                $configuration = $content;
            }
            $files = scandir($mainConfigPath);
            foreach ($files as $file) {
                if (!in_array($file, $excludes)) {
                    $file = $mainConfigPath . DIRECTORY_SEPARATOR . $file;
                    $content = include_once $file;
                    if (!is_array($content)) {
                        throw new \Exception(sprintf('%1 must return an array', $file));
                    }
                    $configuration = array_replace_recursive($configuration, $content);
                }
            }
        }

        //get configuration overrided by module
        foreach ($this->moduleList as $module) {
            $path = MODULE_PATH . DIRECTORY_SEPARATOR . $module;
            $moduleFile = $path . DIRECTORY_SEPARATOR . 'Module.php';
            if (!is_dir($path)) {
                throw new \Exception(sprintf('Module %1$s is defined, but no directory exists in %2$s', $module, $path));
            }
            if (!file_exists($moduleFile)) {
                throw new \Exception(sprintf('Module %1$s must have a Module.php file', $module));
            }

            $className = $module . '\Module';
            $class = new $className;

            if (!$class instanceof Module) {
                throw new \Exception(sprintf('Class %1$s must implements %2$s', $className, Module::class));
            }

            $files = $class->getConfig();
            foreach ($files as $file) {
                if (!in_array($file, $excludes)) {
                    $file = $path . DIRECTORY_SEPARATOR . $file;
                    $content = include_once $file;
                    if (!is_array($content)) {
                        throw new \Exception(sprintf('%1 must return an array', $file));
                    }
                    $configuration = array_replace_recursive ($configuration, $content);
                }
            }
        }

        return $configuration;
    }

}