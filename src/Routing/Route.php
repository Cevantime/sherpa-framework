<?php

namespace Sherpa\Routing;

/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 10/10/18
 * Time: 22:09
 */

class Route extends \Aura\Router\Route
{
    protected $middlewares = [];

    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    public function pipe($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function unpipe()
    {
        $this->middlewares = [];
    }
}