<?php

namespace Sherpa;

use Middlewares\ErrorHandler;
use Middlewares\ErrorHandlerDefault;
use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Kernel\Kernel;
use Sherpa\Middlewares\PhpSession;
use Sherpa\Middlewares\RequestHandler;
use Sherpa\Middlewares\RequestInjector;
use Zend\Diactoros\Response\SapiEmitter;

class FrameworkDeclarations implements DeclarationInterface
{

    public function register(App $app)
    {

        $builder = $app->getContainerBuilder();
        
        if( ! $app->isDebug()) {
            $builder->setDefinitionCache(new \Doctrine\Common\Cache\ApcuCache());
        }

        $builder->useAutowiring(true);
        $builder->ignorePhpDocErrors(true);

        $appClass = get_class($app);

        $builder->addDefinitions([
            'base_path' => function(\DI\Container $container) {
                $originalRequest = $container->get('original_request');
                $serverParams = $originalRequest->getServerParams();
                return $serverParams['Sherpa_BASE'] ?? '';
            },
            'error.handler' => function() {
                return new ErrorHandlerDefault();
            },
            'response.emitter' => function() {
                return new SapiEmitter();
            },
            App::class => $app,
            $appClass => \DI\get(App::class)
        ]);


        $app->delayed(function(Kernel $app) {

            $app->add(new ErrorHandler($app->get('error.handler')), 10000);
            $app->add(new PhpSession(), 500);
            $app->add(new \Middlewares\AuraRouter($app->getRouter()), 100);
            $app->add(new \Middlewares\BasePath($app->get('base_path')), 1000);
            $app->add(new RequestInjector($app->getContainer()), 10);
            $app->add(new RequestHandler($app->getContainer()), 0);

        });
    }

}
