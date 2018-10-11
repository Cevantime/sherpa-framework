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
    public function getNamePrefix()
    {
        return $this->namePrefix;
    }
}