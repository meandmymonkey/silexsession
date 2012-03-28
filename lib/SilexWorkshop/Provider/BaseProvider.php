<?php

namespace SilexWorkshop\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        /**
         * Check requirements
         */

        if (!isset($app['twig']))
        {
            throw new \LogicException('TwigExtension needs to be enabled.');
        }

        /**
         * Set defaults
         */
        
        $globals      = isset($app['static.globals']) ? $app['static.globals'] : array();
        $staticRoutes = isset($app['static.routes'])  ? $app['static.routes']  : array(
            'home' => array(
                'path'     => '/',
                'template' => 'index.html.twig'
            )
        );

        /**
         * Set twig global vars from config
         */

        foreach ($globals as $key => $value)
        {
            $app['twig']->addGlobal($key, $value);
        }

        /**
         *  Set static routes from config
         */

        foreach ($staticRoutes as $name => $route)
        {
            $app->get($route['path'], function () use ($app, $route, $name)
            {
                return new Response($app['twig']->render($route['template']), 200);
            })
            ->bind($name);
        }

        /**
         *  Add router vars if possible
         */

        $app->before(function() use ($app) {
            if (isset($app['url_generator']))
            {
                $app['twig']->addGlobal('current_route', $app['request']->attributes->get('_route'));
            }
        });

        /**
         * Default error handling
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
    }

}