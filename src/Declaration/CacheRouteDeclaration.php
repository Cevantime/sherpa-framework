<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 10/10/18
 * Time: 22:57
 */

namespace Sherpa\Declaration;


use Doctrine\Common\Cache\ApcuCache;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sherpa\App\App;
use Sherpa\Routing\Map;

class CacheRouteDeclaration implements DeclarationInterface
{

    public function register(App $app)
    {
        $routerContainer = $app->getRouter();
        $cache = new ApcuCache();

        $routerContainer->setMapBuilder(function(Map $map) use ($cache, $app){
            $cacheKey = 'sherpa.router.map';
            if($cache->contains($cacheKey)) {
                $routes = $cache->fetch($cacheKey);
                $map->setRoutes($routes);
                $map->setCached(true);
            }
            $app->pipe(function(ServerRequestInterface $request, RequestHandlerInterface $handler) use ($cache, $map, $cacheKey) {
                $routes = $map->getRoutes();
                $cache->save($cacheKey, $routes);
                return $handler->handle($request);
            }, 10000);
        });
    }
}