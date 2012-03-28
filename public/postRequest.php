<?php

require_once __DIR__ . '/../silex.phar';

use Silex\ControllerCollection;
use SilexWorkshop\Model\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
* Setup
*/

$postApp = new ControllerCollection();

/**
* Controller setup
*/

$postApp->post('/new', function (Request $request) use ($postApp)
{
    $comment = new Comment();
    $comment->content = $request->get('comment');

    // do something with $comment - save, update...

    $postApp->redirect('/comment/new');
});



return $postApp;
