<?php

namespace Sherpa\Middlewares;

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
    private $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $container = $this->app->getContainer();
        
        $container->set(ServerRequestInterface::class, $request);
        $container->set(RequestHandlerInterface::class, $handler);
        
        return $handler->handle($request);
    }

}
