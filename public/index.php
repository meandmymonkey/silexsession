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

// TODO comments

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



$app->run();
