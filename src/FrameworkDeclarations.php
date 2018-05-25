<?php

namespace Sherpa;

use Sherpa\App\App;
use Sherpa\ErrorHandler\CustomizableErrorHandler;
use Sherpa\Debug\DebugBar;
use Zend\Diactoros\Response\SapiEmitter;
use Sherpa\Declaration\DeclarationInterface;

class FrameworkDeclarations implements DeclarationInterface
{

    public function register(App $app)
    {

        $builder = $app->getContainerBuilder();

        $builder->useAutowiring(true);
        $builder->ignorePhpDocErrors(true);

        $appClass = get_class($app);

        $builder->addDefinitions([
            'base_path' => function() {
                return (empty($_SERVER['Sherpa_BASE'])) ? '' : $_SERVER['Sherpa_BASE'];
            },
            'error.handler' => function() {
                return new \Middlewares\ErrorHandlerDefault();
            },
            'response.emitter' => function() {
                return new SapiEmitter();
            },
            App::class => $app,
            $appClass => \DI\get(App::class)
        ]);


        $app->delayed(function(\Sherpa\Kernel\Kernel $app) {

            $app->add(new \Middlewares\ErrorHandler(new CustomizableErrorHandler($app)), 10000);
            $app->add(new \Sherpa\Middlewares\PhpSession(), 500);
            $app->add(new \Middlewares\AuraRouter($app->getRouter()), 100);
            $app->add(new \Middlewares\BasePath($app->get('base_path')), 1000);
            $app->add(new \Sherpa\Middlewares\RequestInjector($app), 10);
            $app->add(new \Sherpa\Middlewares\RequestHandler($app->getContainer()));

            if ($app->isDebug()) {
                $app->add(new \Middlewares\Debugbar(new DebugBar($app)), 50);
            }
        });
    }

}
