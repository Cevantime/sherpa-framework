<?php


namespace Sherpa\Middlewares;

use Invoker\InvokerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of RequestHandler
 *
 * @author cevantime
 */
class RequestHandler implements MiddlewareInterface
{
    /**
     * @var InvokerInterface Used to resolve the handlers
     */
    private $invoker;

    /**
     * @var string Attribute name for handler reference
     */
    private $handlerAttribute = 'request-handler';

    /**
     * Set the resolver instance.
     */
    public function __construct(InvokerInterface $invoker = null)
    {
        $this->invoker = $invoker;
    }

    /**
     * Set the attribute name to store handler reference.
     */
    public function handlerAttribute(string $handlerAttribute): self
    {
        $this->handlerAttribute = $handlerAttribute;

        return $this;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = $request->getAttribute($this->handlerAttribute);

        if ($requestHandler instanceof MiddlewareInterface) {
            return $requestHandler->process($request, $handler);
        }

        if ($requestHandler instanceof RequestHandlerInterface) {
            return $requestHandler->handle($request);
        }
        
        if($this->invoker !== null) {
            return $this->invoker->call($requestHandler, $request->getAttributes());
        } else {
            throw new \RuntimeException(sprintf('Unusual request handler: % and no invoker provided.', $this->getType($requestHandler)));
        } 
        
        throw new \RuntimeException(sprintf('Invalid request handler: %s.', $this->getType($requestHandler)));
    }
    
    private function getType($var)
    {
        return is_object($var) ? get_class($var) : gettype($var);
    }

}
