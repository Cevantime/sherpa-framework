<?php

namespace Sherpa\App;

use Aura\Router\RouterContainer;
use Sherpa\Kernel\Kernel;

/**
 * Description of App
 *
 * @author cevantime
 */
class App extends Kernel
{
    use \Sherpa\Traits\RouteTrait;
    use \Sherpa\Traits\ErrorHandleTrait;
    use \Sherpa\Traits\RequestHelperTrait;

    protected $router;
    protected $isDebug;

    public function __construct($isDebug = false)
    {
        parent::__construct();
        $this->router = new RouterContainer();
        $this->storage['router'] = $this->router;
        $this->isDebug = $isDebug;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getRouterMap()
    {
        return $this->router->getMap();
    }
    
    function isDebug()
    {
        return $this->isDebug;
    }

    function setDebug($isDebug)
    {
        $this->isDebug = $isDebug;
    }

}
