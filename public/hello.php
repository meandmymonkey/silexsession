<?php

require_once __DIR__ . '/../silex.phar';

use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Response;

/**
* Setup
*/

$helloApp = new ControllerCollection();

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
