<?php

require_once __DIR__ . '/../silex.phar';



$app = new Silex\Application();

$app['autoloader']->registerNamespace('DevSession', __DIR__.'/../lib');

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));



$app['converter'] = $app->share(function()
{
    return new DevSession\Converter();
});



$app->get('/hello/{name}', function ($name) use ($app)
{
    return $app['twig']->render('hello.html.twig', array('name' => $name));
});

$app->get('/convert/fahrenheit/{fahrenheit}', function ($fahrenheit) use ($app)
{
    $result = $app['converter']->toCelsius($fahrenheit);

    return $app['twig']->render('converter.html.twig', array('result' => $result));
});

$app->get('/convert/celsius/{celsius}', function ($celsius) use ($app)
{
    $result = $app['converter']->toFahrenheit($celsius);

    return $app['twig']->render('converter.html.twig', array('result' => $result));
});



$app->run();
