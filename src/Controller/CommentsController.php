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
        $user = ModelFactory::getModel('users')->listData();
        $comments = ModelFactory::getModel('comments')->listData();

        return $this->render("fullArticle.twig", [
            'comments' => $comments,
            'user'     => $user
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

        if (($this->session['user']['admin'] === '0') || ($this->session['user']['admin'] === '1') ) {

            $author     = $this->session['user']['name'] ;
            $content    = $this->post['content'];
            $user_id    = $this->session['user']['id'] ;
            $article_id = $this->get['id'];



            ModelFactory::getModel('comments')->createData([
                'author'     => $author,
                'content'    => $content,
                'user_id'    => $user_id,
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
    public function reportMethod()
    {
        if (($this->session['user']['admin'] === '0') || ($this->session['user']['admin'] === '1')) {

           $comment_id        = $this->get['id'];
           $comment           = ModelFactory::getModel('comments')->readData($comment_id);
           $article_id        = $comment['article_id'];
           $data['moderated'] = 1;

           ModelFactory::getModel('comments')->updateData($comment_id, $data);

           $this->redirect('articles!read', ['id' => $article_id]);
        }
        return $this->twig->render('error.twig');
    }



    public function approvedMethod()
    {

        ModelFactory::getModel('comments')->updateData($this->get['id'], ['moderated' => 0]);

        $this->redirect('admin');

    }




    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteMethod()
    {
        ModelFactory::getModel('comments')->deleteData($this->get['id']);

        $this->redirect('admin');
    }



}