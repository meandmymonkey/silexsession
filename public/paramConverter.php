<?php

require_once __DIR__ . '/../silex.phar';

use Silex\ControllerCollection ;
use SilexWorkshop\Model\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
* Setup
*/

$convertApp = new ControllerCollection();

/**
* Controller setup
*/

$convertApp->get('/{id}', function (Comment $comment) use ($convertApp)
{
    return new Response($comment->id . ' - ' . $comment->content);
})
->assert('id', '\d+')
->convert('comment', function ($id, Request $request) {
    // in real life, fetch Comment from storage
    $comment = new Comment();
    $comment->id = $request->attributes->get('id');

    return $comment;
});



return $convertApp;
