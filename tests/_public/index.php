<?php

use Sherpa\App\App;
use Zend\Diactoros\Response\HtmlResponse;

require __DIR__ . '/../../vendor/autoload.php';

$app = new App(true);

$routerMap = $app->getMap();

$routerMap->get('home', '/', function () {
    return new HtmlResponse("Hello Sherpa !!");
});

$routerMap->get('middleware', '/middleware', function () {
    return new HtmlResponse("Hello middleware");
})->pipe(function(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler){
    $response = $handler->handle($request);
    $response->getBody()->write($response->getBody() . ' I hacked you !!');
    return $response;
});

$app->bootstrap();


