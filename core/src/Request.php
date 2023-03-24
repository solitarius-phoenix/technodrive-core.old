<?php

namespace Technodrive\Core;

class Request
{

    protected string $uri;

    public function __construct()
    {
        $this->setUri($_SERVER['REQUEST_URI']);
    }

    /**
     * @var string
     * Route matched's name
     */
    protected string $routeMatched;

    /**
     * @var array
     * Route matched's data
     */
    protected array $routeData;

    /**
     * @var array
     * elements given in the URL
     */
    protected array $getData =[];

    /**
     * @var array
     * elements given in the by old method with ?
     */
    protected array $queryData = [];

    /**
     * @var array
     * elements given by POST
     */
    protected array $postData = [];

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = urldecode($uri);
    }

    public function setRouteMatched(string $route): self
    {
        $this->routeMatched = $route;
        return $this;
    }

    public function getRouteMatched(): string
    {
        return $this->routeMatched;
    }

    /**
     * @return array
     */
    public function getGetData(): array
    {
        return $this->getData;
    }

    /**
     * @param array $getData
     * @return self
     */
    public function setGetData(array $getData): self
    {
        $this->getData = $getData;
        return $this;
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * @param array $postData
     * @return self
     */
    public function setPostData(array $postData): self
    {
        $this->postData = $_POST;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryData(): array
    {
        return $this->queryData;
    }

    /**
     * @param array $queryData
     * @return self
     */
    public function setQueryData(array $queryData): self
    {
        $this->queryData = $queryData;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteData(): array
    {
        return $this->routeData;
    }

    /**
     * @param array $routeData
     * @return self
     */
    public function setRouteData(array $routeData): self
    {
        $this->routeData = $routeData;
        return $this;
    }

}