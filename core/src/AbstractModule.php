<?php

namespace Technodrive\Core;

class AbstractModule
{
    public function getConfig(): array
    {
        return require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . "Config.php";
    }

    public static function getModule(): string
    {
        return __NAMESPACE__;
    }
}