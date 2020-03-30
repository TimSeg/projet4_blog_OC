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
        $articles = ModelFactory::getModel('Articles')->readData($_GET['id']);

        $comments = ModelFactory::getModel('Comments')->listData($_GET['id'], 'article_id');

        return $this->twig->render('fullpost.twig', [
            'article' => $articles,
            'comments' => $comments
        ]);
    }


}
