<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 10/10/18
 * Time: 22:12
 */

namespace Sherpa\Routing;


use Aura\Router\Exception\RouteNotFound;
use Aura\Router\Route;

class Map extends \Aura\Router\Map
{
    protected $isCached = false;
    protected $originalRoute;

    public function __construct(Route $protoRoute)
    {
        parent::__construct($protoRoute);
        $this->originalRoute = $protoRoute;
    }

    /**
     * @return Route
     */
    public function getProtoRoute(): Route
    {
        return $this->protoRoute;
    }

    /**
     * @param Route $protoRoute
     */
    public function setProtoRoute(Route $protoRoute): void
    {
        $this->protoRoute = $protoRoute;
    }

    /**
     * @return Route
     */
    public function getOriginalRoute(): Route
    {
        return $this->originalRoute;
    }

    public function getRoute($name)
    {
        if (!isset($this->routes[$this->protoRoute->getNamePrefix() . $name])) {
            throw new RouteNotFound($this->protoRoute->getNamePrefix() . $name);
        }

        return $this->routes[$this->protoRoute->getNamePrefix() . $name];
    }

    public function reset()
    {
        $this->protoRoute = $this->originalRoute;
    }

    /**
     * @return bool
     */
    public function isCached(): bool
    {
        return $this->isCached;
    }

    /**
     * @param bool $isCached
     */
    public function setCached(bool $isCached): void
    {
        $this->isCached = $isCached;
    }
}