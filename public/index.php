<?php

require_once __DIR__ . '/../silex.phar';

/**
 * Setup & extensions
 */

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexWorkshop', __DIR__.'/../lib');

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));

/**
 * Services
 */

$app['converter'] = $app->share(function()
{
    return new SilexWorkshop\Model\Converter();
});

/**
 * Controller setup
 */

$app->get('/hello/{name}', function ($name) use ($app)
{
    return $app['twig']->render('hello.html.twig', array('name' => $name));
});

$app->get('/convert/{sourceFormat}/{degrees}', function ($sourceFormat, $degrees) use ($app)
{
    if ($sourceFormat == 'celsius')
    {
        $result = $app['converter']->toFahrenheit($degrees);
    }
    else
    {
        $result = $app['converter']->toCelsius($degrees);
    }

    return $app['twig']->render('converter.html.twig', array(
        'source_format' => $sourceFormat,
        'degrees'       => $degrees,
        'result'        => $result
    ));
})
->assert('sourceFormat', 'celsius|fahrenheit')
->value('degrees', 100);

/**
 * Run application
 */

$app->run();
