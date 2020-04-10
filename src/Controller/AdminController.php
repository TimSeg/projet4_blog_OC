<?php

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
        if ($this-> $user['admin'] === '1')
        {
            $articles = ModelFactory::getModel('Artcicles')->listData();
            $comments = ModelFactory::getModel('Comments')->listData();
            $users = ModelFactory::getModel('Users')->listData();

            return $this->render("admin.twig", [
                'articles' => $articles,
                'comments' => $comments,
                'users' => $users
            ]);
        }
        elseif ($this->$user('admin') === '0')
        {
            return $this->twig->render("admin.twig");
        }
        return $this->twig->render('home.twig');
    }
