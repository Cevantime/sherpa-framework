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
    public function redirectToRoute($routeName, $params = [], $status = 302, $header = [])
    {
        return $this->redirectTo($this->path($routeName, $params), $status, $header);
    }
    
    public function redirectTo($path, $status = 302, $header = [])
    {
        return new RedirectResponse($path, $status, $header);
    }
    
    public function path($routeName, $params = array())
    {
        return $this->container->get('router')->getGenerator()->generate($routeName, $params);
    }
    
}
