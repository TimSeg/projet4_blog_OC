<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends MainController
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function launchMethod()
    {
//secure access only for main administrator login - avoid access with url

        if ($this->session['user']['admin'] === '1') {


            $articles = ModelFactory::getModel('Articles')->listData();
            $comments = ModelFactory::getModel('Comments')->listData();
            $users = ModelFactory::getModel('Users')->listData();

            return $this->twig->render("admin.twig", [
                'articles' => $articles,
                'comments' => $comments,
                'users' => $users
            ]);
        }

        $this->redirect('home.twig');

    }
}