<?php

namespace Sherpa;

use function DI\get;
use function DI\string;
use Doctrine\Common\Cache\ApcuCache;
use Middlewares\ErrorHandlerDefault;
use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Kernel\Kernel;
use Sherpa\Middlewares\ErrorHandler;
use Sherpa\Middlewares\PhpSession;
use Sherpa\Middlewares\RequestHandler;
use Sherpa\Middlewares\RequestInjector;
use Sherpa\Middlewares\RouteMiddleware;
use Sherpa\Routing\Map;
use Sherpa\Routing\Route;
use Zend\Diactoros\Response\SapiEmitter;

class FrameworkDeclaration implements DeclarationInterface
{

    public function register(App $app)
    {

        $builder = $app->getContainerBuilder();
        if( ! $app->isDebug()) {
            $builder->enableDefinitionCache();
            $builder->enableCompilation('../var/cache');
        }

        $builder->useAutowiring(true);
        $builder->ignorePhpDocErrors(true);

        $appClass = get_class($app);

        $builder->addDefinitions([
            'error.handler' => function() {
                return new ErrorHandlerDefault();
            },
            'response.emitter' => function() {
                return new SapiEmitter();
            },
            App::class => $app,
            $appClass => get(App::class)
        ]);

        $app->set('project.namespace', 'App\\');
        $app->set('project.root', realpath('..'));
        $app->set('project.src', string('{project.root}/src'));
        $app->set('project.cache', string('{project.root}/var/cache'));

        $app->pipe(RouteMiddleware::class, 99);
        $app->pipe(RequestHandler::class, 0);
        $app->pipe(PhpSession::class, 500);
        $app->pipe(RequestInjector::class, 0, RequestHandler::class);

        $app->delayed(function(Kernel $app) {
            $errorHandler = new ErrorHandler($app->get('error.handler'));
            $errorHandler->catchExceptions(!$app->get('debug'));
            $app->pipe($errorHandler, 10000);
            $app->pipe(new \Middlewares\AuraRouter($app->getRouter()), 100);
        });
    }

}
