<?php

require_once __DIR__ . '/../silex.phar';
require_once __DIR__ . '/../vendor/.composer/autoload.php';

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

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
));

$app->register(new Silex\Provider\SymfonyBridgesServiceProvider());

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
* Error handling
*/

$app->error(function (\Exception $e) use ($app) {
    if ($e instanceof NotFoundHttpException) {
        return new Response($app['twig']->render('error404.html.twig', array('exception' => $e)), 404);
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
        
    return new Response($app['twig']->render('error500.html.twig', array(
         'code'      => $code,
         'exception' => $e
    )), $code);
});

/**
* Run application
*/

$app->run();
