<?php

require_once __DIR__ . '/../silex.phar';

use Silex\Application;
use SilexWorkshop\Model\Converter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
* Setup
*/

$app = new Application();

$app['autoloader']->registerNamespace('SilexWorkshop', __DIR__.'/../lib');

$helloApp          = require(__DIR__.'/hello.php');
$paramConverterApp = require(__DIR__ . '/paramConverter.php');
$postRequestApp    = require(__DIR__ . '/postRequest.php');

$app->mount('/hello', $helloApp);
$app->mount('/param-converter', $paramConverterApp);
$app->mount('/post-request', $postRequestApp);

/**
* Services
*/

$app['converter'] = $app->share(function()
{
    return new Converter();
});

/**
* Controller setup
*/

$app->get('/convert/{sourceFormat}/{degrees}', function ($sourceFormat, $degrees) use ($app)
{
    if ($sourceFormat == 'fahrenheit') {
        $result = $app['converter']->toCelsius($degrees);
    } else {
        $result = $app['converter']->toFahrenheit($degrees);
    }
    
    return new Response($result, 200);
})
->assert('degrees', '\d+')
->assert('sourceFormat', 'fahrenheit|celsius')
->value('degrees', 100);

/**
* Error handling
*/

$app->error(function (\Exception $e) {
    if ($e instanceof NotFoundHttpException) {
        return new Response('Nothing to see here. Go away.', 404);
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
        
    return new Response('Whoa, major breakdown!', $code);
});

/**
* Run application
*/

$app->run();
