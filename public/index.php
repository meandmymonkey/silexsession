<?php

require_once __DIR__ . '/../silex.phar';

/**
* Setup
*/

$app = new Silex\Application();

/**
* Controller setup
*/

$app->get('/hello/{name}', function ($name) use ($app)
{
    return 'Hello ' . $app->escape($name);
});

/**
* Run application
*/

$app->run();
