<?php

namespace Technodrive\Core\View_helpers;

class DoctypeHelper
{
    public function __invoke($doctype = null): string
    {
        return '<!doctype html>';
    }

}