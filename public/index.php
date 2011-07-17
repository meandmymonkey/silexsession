<?php

require_once __DIR__ . '/../silex.phar';

use Silex\Application;
use SilexWorkshop\Model\Converter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Setup & extensions
 */

$app = new Application();

$app['autoloader']->registerNamespace('SilexWorkshop', __DIR__.'/../lib');

$app->register(new Silex\Extension\UrlGeneratorExtension());
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib'
));
$app->register(new SilexWorkshop\Extension\BaseExtension());

$app->before(function() use ($app)
{
    $app['twig']->addGlobal('url_generator', $app['url_generator']);
});

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
    if ($sourceFormat == 'celsius')
    {
        $result = $app['converter']->toFahrenheit($degrees);
    }
    else
    {
        $result = $app['converter']->toCelsius($degrees);
    }
    
    return new Response($app['twig']->render('converter.html.twig', array(
        'source_format' => $sourceFormat,
        'degrees'       => $degrees,
        'result'        => $result
    )), 200);
})
->assert('degrees', '\d+')
->assert('sourceFormat', 'fahrenheit|celsius')
->value('degrees', 37)
->bind('temp');

/**
* Run application
*/

$app->run();
