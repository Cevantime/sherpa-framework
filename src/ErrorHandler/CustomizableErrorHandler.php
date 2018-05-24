<?php

namespace Sherpa\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of CustomizableErrorHandler
 *
 * @author cevantime
 */
class CustomizableErrorHandler extends \Middlewares\ErrorHandlerDefault
{
    private $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->app->get('error.handler')->handle($request);
    }

}
