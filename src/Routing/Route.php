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

    public function __clone()
    {
        parent::__clone();
        $this->attributes([
            '_route' => $this
        ]);
    }

    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    public function pipe($middleware, ?int $priority = 1, ?string $before = null)
    {
        $this->middlewares[] = [
            'middleware' => $middleware,
            'priority' => $priority,
            'before' => $before
        ];
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