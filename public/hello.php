<?php

require_once __DIR__ . '/../silex.phar';

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/**
* Setup
*/

$helloApp = new Application();

/**
* Controller setup
*/

$helloApp->get('/{name}', function ($name) use ($helloApp)
{
    return new Response('Hello ' . $helloApp->escape($name), 200);
})
->assert('name', '[a-zA-Z]{2,}')
->value('name', 'Dude');



return $helloApp;
