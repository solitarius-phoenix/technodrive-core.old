<?php

namespace Technodrive\Core\Controllers_plugins;

use Technodrive\Core\Services\NavigationService;

class RedirectPlugin
{

    protected NavigationService $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    public function __invoke()
    {
        die('redirection en cours');
        return new RedirectResponse($this->navigationService);
    }
}