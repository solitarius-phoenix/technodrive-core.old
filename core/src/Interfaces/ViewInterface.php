<?php

namespace Technodrive\Core\Interfaces;

interface ViewInterface
{

    public static function header();
    public static function getStrategy();
    public function render();

}