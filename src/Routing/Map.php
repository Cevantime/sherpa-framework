<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 10/10/18
 * Time: 22:12
 */

namespace Sherpa\Routing;


use Aura\Router\Exception\RouteNotFound;

class Map extends \Aura\Router\Map
{
    protected $isCached = false;

    public function getRoute($name)
    {
        if (!isset($this->routes[$this->protoRoute->getNamePrefix() . $name])) {
            throw new RouteNotFound($this->protoRoute->getNamePrefix() . $name);
        }

        return $this->routes[$this->protoRoute->getNamePrefix() . $name];
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