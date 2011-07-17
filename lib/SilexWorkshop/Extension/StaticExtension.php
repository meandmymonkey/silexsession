<?php

namespace SilexWorkshop\Extension;

use Silex\Application;
use Silex\ExtensionInterface;

use Symfony\Component\HttpFoundation\Response;

class StaticExtension implements ExtensionInterface
{

    public function register(Application $app)
    {
        if (!isset($app['twig']))
        {
            throw new \LogicException('TwigExtension needs to be enabled.');
        }

        $globals      = isset($app['static.globals']) ? $app['static.globals'] : array();
        $staticRoutes = isset($app['static.routes'])  ? $app['static.routes']  : array(
            'home' => array(
                'path'     => '/',
                'template' => 'index.html.twig'
            )
        );

        foreach ($globals as $key => $value)
        {
            $app['twig']->addGlobal($key, $value);
        }

        foreach ($staticRoutes as $name => $route)
        {
            $app->get($route['path'], function () use ($app, $route, $name)
            {
                return new Response($app['twig']->render($route['template']), 200);
            })
            ->bind($name);
        }

        $app->before(function() use ($app) {

            if (isset($app['url_generator']))
            {
                $app['twig']->addGlobal('url_generator', $app['url_generator']);
                $app['twig']->addGlobal('current_route', $app['request']->attributes->get('_route'));
            }
        });
    }

}