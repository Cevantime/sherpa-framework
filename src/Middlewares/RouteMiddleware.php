<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 16/10/18
 * Time: 21:40
 */

namespace Sherpa\Middlewares;


use DI\InvokerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sherpa\Exception\NotAMiddlewareException;
use Sherpa\Kernel\Middleware\CallableMiddleware;

class RouteMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * RouteMiddleware constructor.
     * @param ContainerInterface $invoker
     */
    public function __construct(ContainerInterface $invoker)
    {
        $this->container = $invoker;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('_route');
        /* @var \Sherpa\Routing\Route $route */
        $callables = $route->getMiddlewares();
        $callables[] = new MiddlewareDelegator($handler);
        $requestHander = new \Sherpa\Kernel\RequestHandler\RequestHandler($callables, $this->container);
        return $requestHander->handle($request);
    }
}