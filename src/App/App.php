<?php

namespace Sherpa\App;

use Aura\Router\Map;
use Aura\Router\RouterContainer;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Exception\InvalidDeclarationClassException;
use Sherpa\FrameworkDeclarations;
use Sherpa\Kernel\Kernel;
use Sherpa\Traits\ErrorHandleTrait;
use Sherpa\Traits\RequestHelperTrait;
use Sherpa\Traits\RouteTrait;
use Zend\Diactoros\ServerRequestFactory;

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
        $this->addDeclaration(FrameworkDeclarations::class);
    }

    public function bootstrap()
    {
        $request = ServerRequestFactory::fromGlobals();
        $response = $this->handle($request);
        $emitter = $this->get('response.emitter');
        $emitter->emit($response);
        $this->terminate();
    }

    /**
     * 
     * @return RouterContainer
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * 
     * @return Map
     */
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
        if($isDebug && !$this->isDebug) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            $this->isDebug = $isDebug;
        }
    }

    function addDeclaration($declarationClass)
    {
        $declaration = new $declarationClass();

        if ($declaration instanceof DeclarationInterface) {
            $declaration->register($this);
        } else {
            throw new InvalidDeclarationClassException();
        }
    }

    function addDeclarations($declarationClasses)
    {
        if (is_array($declarationClasses)) {
            foreach ($declarationClasses as $declarationClass) {
                $this->addDeclaration($declarationClass);
            }
        } else {
            $this->addDeclaration($declarationClass);
        }
    }

}
