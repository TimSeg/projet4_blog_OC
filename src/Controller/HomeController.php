<?php

namespace App\Controller;


use App\Model\Factory\ModelFactory;
use App\Model\Factory\PdoFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class HomeController
 * Manages the Homepage
 * @package App\Controller
 */
class HomeController extends MainController
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

        $lastPost = ModelFactory::getModel('Posts')->getLastpost();
        return $this->twig->render('home.twig', ['posts' => $lastPost]);



    }



}

