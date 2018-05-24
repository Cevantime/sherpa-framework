<?php

namespace Sherpa\Traits;

use Zend\Diactoros\Response\RedirectResponse;

/**
 * Description of RouteTrait
 *
 * @author cevantime
 */
trait RouteTrait
{
    public function redirectToRoute($routeName, $params = array())
    {
        return $this->redirectTo($this->path($routeName, $params));
    }
    
    public function redirectTo($path)
    {
        return new RedirectResponse($path);
    }
    
    public function path($routeName, $params = array())
    {
        return $this->getRouter()->getGenerator()->generate($routeName, $params);
    }
    
}
