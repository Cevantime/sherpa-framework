<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 26/10/18
 * Time: 23:51
 */

namespace Sherpa\Controller;

class AppController
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * AppController constructor.
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }
}