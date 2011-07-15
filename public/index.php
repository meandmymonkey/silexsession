<?php

require_once __DIR__ . '/../silex.phar';

use Silex\Application;
use SilexWorkshop\Model\Converter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Setup & extensions
 */

$app = new Application();

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

    return $app['twig']->render('converter.html.twig', array(
        'source_format' => $sourceFormat,
        'degrees'       => $degrees,
        'result'        => $result
    ));
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
