<?php

namespace Sherpa\Declaration;

use Aura\Router\Map;
use DI\Container;
use DI\ContainerBuilder;
use Sherpa\App\App;

/**
 * Description of AbstractDeclaration
 *
 * @author cevantime
 */
class Declaration implements DeclarationInterface
{
    public function routes(Map $map)
    {
        
    }
    
    public function definitions(ContainerBuilder $builder)
    {
        
    }
    
    public function delayed(App $app)
    {
        
    }
    
    public function declarations(App $app)
    {
        
    }

    public function register(App $app)
    {
        $this->declarations($app);
        $this->definitions($app->getContainerBuilder());

        $map = $app->getRouterMap();

        if( ! $map->isCached()) {
            $this->routes($app->getRouterMap());
        }

        $self = $this;
        $app->delayed(function(App $app) use ($self) {
            $self->delayed($app);
        });
    }
}
