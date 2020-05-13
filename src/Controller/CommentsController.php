<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class CommentsController
 * @package App\Controller
 */
class CommentsController extends MainController
{

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function launchMethod()
    {
        $user = ModelFactory::getModel('Users')->listData();
        $comments = ModelFactory::getModel('Comments')->listData();

        return $this->render("fullArticle.twig", [
            'comments' => $comments,
            'user'   => $user
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createMethod()
    {

        if ($this->session['user']['admin'] === '0') {

            $author = $this->session['user']['name'] ;
            $content = $this->post['content'];
            $user_id = $this->session['user']['id'] ;
            $article_id = $this->get['id'];

            ModelFactory::getModel('Comments')->createData([
                'author' => $author,
                'content' => $content,
                'user_id' => $user_id,
                'article_id' => $article_id
            ]);


            $this->redirect('Articles!launch');

        }

        return $this->twig->render('error.twig');

    }


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteMethod()
    {
        ModelFactory::getModel('Comments')->deleteData($this->get['id']);

        $this->redirect('admin');
    }



}