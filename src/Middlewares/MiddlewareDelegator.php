<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 16/10/18
 * Time: 21:51
 */

namespace Sherpa\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDelegator implements MiddlewareInterface
{
    /**
     * @var RequestHandlerInterface $handler
     */
    private $handler;

    /**
     * MiddlewareDelegator constructor.
     * @param RequestHandlerInterface $handler
     */
    public function __construct(RequestHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}