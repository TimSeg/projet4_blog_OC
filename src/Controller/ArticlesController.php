<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ArticlesController
 * @package App\Controller
 */

class ArticlesController extends MainController
{

    /**
     * Renders the View Home
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function launchMethod()
    {


        $articles = ModelFactory::getModel('Articles')->listData();

        return $this->twig->render('articles.twig', ['articles' => $articles]);
    }


    public function readMethod()
    {
        $articles = ModelFactory::getModel('Articles')->readData(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $comments = ModelFactory::getModel('Comments')->listData(filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT),  'article_id');

        return $this->twig->render('fullArticle.twig', [
            'article' => $articles,
            'comments' => $comments
        ]);
    }






}