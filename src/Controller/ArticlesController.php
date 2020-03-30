<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class PostController
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



}