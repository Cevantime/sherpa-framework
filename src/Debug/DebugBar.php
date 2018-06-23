<?php

namespace Sherpa\Debug;

/**
 * Description of DebugBar
 *
 * @author cevantime
 */
class DebugBar extends \DebugBar\StandardDebugBar
{
    public function __construct($basePath)
    {
        parent::__construct();
        $this->jsRenderer = new \DebugBar\JavascriptRenderer($this, 'debugbar', $basePath);
    }
}
