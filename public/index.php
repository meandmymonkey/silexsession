<?php

require_once __DIR__ . '/../silex.phar';

use Silex\Application;
use SilexWorkshop\Model\Converter;
use Symfony\Component\HttpFoundation\Response;

/**
* Setup
*/

$app = new Application();

$app['autoloader']->registerNamespace('SilexWorkshop', __DIR__.'/../lib');

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

$app->get('/hello/{name}', function ($name) use ($app)
{
    return new Response('Hello ' . $app->escape($name), 200);
});

$app->get('/convert/{sourceFormat}/{degrees}', function ($sourceFormat, $degrees) use ($app)
{
    if ($sourceFormat == 'fahrenheit') {
        $result = $app['converter']->toCelsius($degrees);
    } else {
        $result = $app['converter']->toFahrenheit($degrees);
    }
    
    return new Response($result, 200);
});

/**
* Run application
*/

$app->run();
