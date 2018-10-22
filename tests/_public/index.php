<?php

use Sherpa\App\App;
use Zend\Diactoros\Response\HtmlResponse;

require __DIR__ . '/../../vendor/autoload.php';

$app = new App();

//$app->setDebug(true);

$routerMap = $app->getMap();

$routerMap->get('home', '/', function () {
    return new HtmlResponse("Hello Sherpa !!");
});

$routerMap->get('middleware', '/middleware', function () {
    return new HtmlResponse("Hello middleware");
})->with(function(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler){
    $response = $handler->handle($request);
    $response->getBody()->write('I hacked you !!');
});

$app->bootstrap();


