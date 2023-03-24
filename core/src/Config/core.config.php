<?php
namespace Technodrive\Core\Config;

use Technodrive\Core\Controllers_plugins\Factories\RedirectPluginFactory;
use Technodrive\Core\Controllers_plugins\RedirectPlugin;

return [
    'controller_plugins' => [
        'factories' => [
            RedirectPlugin::class=>RedirectPluginFactory::class,
        ],
        'alias' => [
            'redirect'=>RedirectPlugin::class,
        ]
    ],
];