<?php

namespace Sherpa\App;

use Aura\Router\RouterContainer;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Exception\InvalidDeclarationClassException;
use Sherpa\Kernel\Kernel;
use Sherpa\Traits\ErrorHandleTrait;
use Sherpa\Traits\RequestHelperTrait;
use Sherpa\Traits\RouteTrait;

/**
 * Description of App
 *
 * @author cevantime
 */
class App extends Kernel
{
    use RouteTrait;
    use ErrorHandleTrait;
    use RequestHelperTrait;

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

    function addDeclaration($declarationClass)
    {
        $declaration = new $declarationClass();
        
        if($declaration instanceof DeclarationInterface){
            $declaration->register($this);
        } else {
            throw new InvalidDeclarationClassException();
        }
    }
}
