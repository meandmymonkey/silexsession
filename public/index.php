<?php

require_once __DIR__ . '/../silex.phar';

/**
* Setup
*/

$app = new Silex\Application();

$app['autoloader']->registerNamespace('DevSession', __DIR__.'/../lib');

/**
* Services
*/

$app['converter'] = $app->share(function()
{
    return new DevSession\Converter();
});

/**
* Controller setup
*/

$app->get('/hello/{name}', function ($name) use ($app)
{
    return 'Hello ' . $app->escape($name);
});

$app->get('/convert/fahrenheit/{fahrenheit}', function ($fahrenheit) use ($app)
{
    return $app['converter']->toCelsius($fahrenheit);
});

$app->get('/convert/celsius/{celsius}', function ($celsius) use ($app)
{
    return $app['converter']->toFahrenheit($celsius);
});

/**
* Run application
*/

$app->run();
