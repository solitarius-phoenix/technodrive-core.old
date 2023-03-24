<?php

namespace Technodrive\Core\ResponseStrategy;

use Technodrive\Core\Services\NavigationService;

/**
 *
 */
class RedirectResponse
{

    /**
     * @var NavigationService
     */
    protected NavigationService $navigationService;

    /**
     * @param NavigationService $navigationService
     * @todo check si on ne redirige pas sur la même url, et donc en boucle.
     */
    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * @param string $routeName
     * @param array $params
     * @param $code
     * @return void
     */
    public function toRoute(string $routeName, array $params=[], $code=307): void
    {
        $url = $this->navigationService->createUrl($routeName, $params);
        $this->toUrl($url, $code);
    }

    /**
     * @param string $url
     * @param int $code
     * @return void
     */
    public function toUrl(string $url, int $code=307): void
    {
        header(sprintf('location: %1$s', $url), true, $code);
    }

}