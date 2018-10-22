<?php

namespace Sherpa\Declaration;

use DI\ContainerBuilder;
use Sherpa\App\App;
use Sherpa\Routing\Map;

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
    
    public function custom(App $app)
    {
        
    }

    public function register(App $app)
    {
        $this->custom($app);
        $this->definitions($app->getContainerBuilder());

        $map = $app->getMap();

        if( ! $map->isCached()) {
            $this->routes($app->getMap());
        }

        $self = $this;
        $app->delayed(function(App $app) use ($self) {
            $self->delayed($app);
        });
    }
}
