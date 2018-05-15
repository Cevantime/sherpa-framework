<?php

namespace Sherpa\Traits;

use Middlewares\Utils\RequestHandler;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of ErrorHandleTrait
 *
 * @author cevantime
 */
trait ErrorHandleTrait
{

    public function error($callable)
    {
        if (!($callable instanceof RequestHandlerInterface)) {
            $callable = new RequestHandler($callable);
        }
        $this->set('error.handler', function () use ($callable) {
            return $callable;
        });
    }

}
