<?php

require_once __DIR__ . '/../silex.phar';



$app = new Silex\Application();

$app['autoloader']->registerNamespace('DevSession', __DIR__.'/../lib');



$app['converter'] = $app->share(function()
{
    return new DevSession\Converter();
});



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



$app->run();
