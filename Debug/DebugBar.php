<?php

namespace Sherpa\Debug;

/**
 * Description of DebugBar
 *
 * @author cevantime
 */
class DebugBar extends \DebugBar\StandardDebugBar
{
    public function __construct($app)
    {
        parent::__construct();
        $this->jsRenderer = new \DebugBar\JavascriptRenderer($this, 'debugbar', $app->get('base_path'));
    }
}
