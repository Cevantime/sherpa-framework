<?php

use Sherpa\App\App;
use Zend\Diactoros\Response\HtmlResponse;

require __DIR__.'/../../vendor/autoload.php';
    
$app = new App();

$routerMap = $app->getRouterMap();

$routerMap->get('home', '/', function(){
    return new HtmlResponse("Hello Sherpa !!");
});

$app->bootstrap();


