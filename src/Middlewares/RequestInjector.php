<?php

namespace Sherpa\Middlewares;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of RequestInjector
 *
 * @author cevantime
 */
class RequestInjector implements MiddlewareInterface
{
    private $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $container = $this->container;
        
        $container->set(ServerRequestInterface::class, $request);
        $container->set(RequestHandlerInterface::class, $handler);
        
        return $handler->handle($request);
    }

}
