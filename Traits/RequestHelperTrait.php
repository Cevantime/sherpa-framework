<?php

namespace Sherpa\Traits;

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;

/**
 * Description of RequestHelperTrait
 *
 * @author cevantime
 */
trait RequestHelperTrait
{
    public function renderUri($uri)
    {
        return $this->run($this->getOriginalRequest()->withUri(new Uri($uri)))->getBody();
    }
}
