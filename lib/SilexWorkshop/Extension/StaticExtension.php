<?php

namespace SilexWorkshop\Extension;

use Silex\Application;
use Silex\ExtensionInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaticExtension implements ExtensionInterface
{

    public function register(Application $app)
    {
        $viewBasePath   = isset($app['dc.base.view_basepath'])   ? $app['dc.base.view_basepath']    : __DIR__ . '/../../../../views';
        $globals        = isset($app['dc.base.globals'])         ? $app['dc.base.globals']          : array();
        $staticRoutes   = isset($app['dc.base.static_routes'])   ? $app['dc.base.static_routes']    : array(
            'home' => array(
                'path'     => '/',
                'template' => 'index.twig.html'
            )
        );

        if (isset($app['twig']))
        {
            foreach ($globals as $key => $value)
            {
                $app['twig']->addGlobal($key, $value);
            }

            //$app['twig']->addGlobal('url_generator', $app['url_generator']);
            //$app['twig']->addGlobal('current_route', $app['request']->attributes->get('_route'));
        }

        foreach ($staticRoutes as $name => $route)
        {
            $app->get($route['path'], function () use ($app, $route, $name)
            {
                return $app['twig']->render($route['template']);
            })
            ->bind($name);
        }
    }

}