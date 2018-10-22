<?php

namespace Sherpa;

use function DI\get;
use Doctrine\Common\Cache\ApcuCache;
use Middlewares\ErrorHandler;
use Middlewares\ErrorHandlerDefault;
use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Kernel\Kernel;
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
        $cache = new ApcuCache();

        if( ! $app->isDebug()) {
            $builder->setDefinitionCache($cache);
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

        $app->set('namespace', 'App\\');
        $app->set('projectDir', realpath('..'));
        $app->set('projectSrc', function(\DI\Container $container) {
            return $container->get('projectDir') . '/src';
        });

        $app->pipe(RequestInjector::class, 2);
        $app->pipe(RouteMiddleware::class, 1);
        $app->pipe(RequestHandler::class, 0);
        $app->pipe(PhpSession::class, 500);

        $app->delayed(function(Kernel $app) {
            $app->pipe(new ErrorHandler($app->get('error.handler')), 10000);
            $app->pipe(new \Middlewares\AuraRouter($app->getRouter()), 100);
        });
    }

}
