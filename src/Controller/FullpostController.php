<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class FullPostController
 * @package App\Controller
 */
class FullpostController extends MainController
{
    public function readMethod()
    {
        $posts = ModelFactory::getModel('Posts')->readData($_GET['id']);

       // $title = ModelFactory::getModel('Posts')->readData($_GET['id'])['title'];
       // $content = ModelFactory::getModel('Posts')->readData($_GET['id'])['content'];


        $comments = ModelFactory::getModel('Comments')->listData($_GET['id'], 'post_id');

        return $this->twig->render('fullpost.twig', [
            'post' => $posts,
            //'post' => array('title' => $title, 'content' => $content ),
            'comments' => $comments
        ]);
    }


}