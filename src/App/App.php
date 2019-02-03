<?php

namespace Sherpa\App;

use Aura\Router\RouterContainer;
use Sherpa\Declaration\Declaration;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Exception\InvalidDeclarationClassException;
use Sherpa\FrameworkDeclaration;
use Sherpa\Kernel\Kernel;
use Sherpa\Routing\Map;
use Sherpa\Routing\Route;
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
    protected $declarations = [];

    public function __construct($isDebug = false)
    {
        parent::__construct();

        $this->set('router', $this->router);
        $this->setDebug($isDebug);

        $this->set('base_path', $_SERVER['Sherpa_BASE'] ?? '');

        $this->addDeclaration(FrameworkDeclaration::class);
    }

    public function bootstrap()
    {
        $request = ServerRequestFactory::fromGlobals();
        $this->getRouter(); // initialize router if not provided
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
        if ($this->router === null) {
            $this->router = new RouterContainer($this->get('base_path'));

            $this->router->setMapFactory(function () {
                return new Map(new Route());
            });
            $this->router->setRouteFactory(function () {
                return new Route();
            });
            $this->set('router', $this->router);
        }
        return $this->router;
    }

    /**
     *
     * @return \Sherpa\Routing\Map
     */
    public function getMap()
    {
        return $this->getRouter()->getMap();
    }

    public function isDebug()
    {
        return $this->isDebug;
    }

    public function setDebug($isDebug)
    {
        $this->set('debug', $isDebug);
        $this->isDebug = $isDebug;
        if ($isDebug) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }
    }

    public function addDeclaration($declarationClass)
    {
        if (isset($this->declarations[$declarationClass])) {
            return;
        }

        $declaration = new $declarationClass();

        if ($declaration instanceof DeclarationInterface) {
            if ($this->router !== null) {
                $map = $this->getMap();
                $currentRoute = $map->getProtoRoute();
                $map->reset();
            }
            $declaration->register($this);
            $this->declarations[$declarationClass] = $declaration;
            if (isset($map) && isset($currentRoute)) {
                $map->setProtoRoute($currentRoute);
            }
        } else {
            throw new InvalidDeclarationClassException($declarationClass);
        }
    }

    public function addDeclarations($declarationClasses)
    {
        if (is_array($declarationClasses)) {
            foreach ($declarationClasses as $declarationClass) {
                $this->addDeclaration($declarationClass);
            }
        } else {
            $this->addDeclaration($declarationClasses);
        }
    }
}
