<?php

require_once __DIR__ . '/../silex.phar';

use Silex\Application;
use SilexWorkshop\Model\Comment;
use Symfony\Component\HttpFoundation\Response;

/**
* Setup
*/

$convertApp = new Application();

$app['autoloader']->registerNamespace('SilexWorkshop', __DIR__.'/../lib');

/**
* Controller setup
*/

$convertApp->get('/{id}', function (Comment $comment) use ($convertApp)
{
    return new Response($comment->content);
})
->assert('id', '\d+')
->convert('comment', function ($id) {
    // in real life, fetch Comment from storage
    $comment = new Comment();
    $comment->id = $id;

    return $comment;
});



return $convertApp;
