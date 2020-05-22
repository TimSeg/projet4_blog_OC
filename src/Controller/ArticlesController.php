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
     * @return array
     */
    private $post_content = [];



    /**
     * Renders the View Home
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function launchMethod()
    {


        $articles = ModelFactory::getModel('articles')->listData();

        return $this->twig->render('articles.twig', ['articles' => $articles]);
    }


    public function readMethod()
    {

        $articles = ModelFactory::getModel('articles')->readData(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $comments = ModelFactory::getModel('comments')->listData(filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT),  'article_id');

        return $this->twig->render('fullArticle.twig', [
            'article' => $articles,
            'comments' => $comments
        ]);

    }

    /**
     * @return string
     */
    private function postData()
    {
        $this->post_content['title'] = $this->post['title'];
        $this->post_content['content'] = $this->post['content'];
    }



    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createMethod()
    {
        $title   = $this->post['title'];
        $content = $this->post['content'];
        if (empty($title && $content)) {
            $this->redirect('admin');
        }
        $createdArticle = ModelFactory::getModel('articles')->createIt($title,$content);
        $this->redirect('admin', ['createdArticle' => $createdArticle]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteMethod()
    {
        $id_article = $this->get['id'];

        $article_confirmed = ModelFactory::getModel('comments')->listData($id_article, 'article_id');

        if (!empty($article_confirmed))
        {
            ModelFactory::getModel('comments')->deleteData($id_article, 'article_id');
        }

        ModelFactory::getModel('articles')->deleteData($id_article);

        $this->redirect('admin');
    }



    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function modifyMethod()
    {
        if (!empty($this->post)) {
            $this->postData();

            ModelFactory::getModel('articles')->modifyIt($this->get['id'], $this->post_content['title'],$this->post_content['content']);

            $this->redirect('admin');
        }
        $articles = ModelFactory::getModel('articles')->readData($this->get['id']);
        $comments = ModelFactory::getModel('comments')->listData($this->get['id'], 'article_id');

        return $this->render('articlesModify.twig', [
            'articles' => $articles,
            'comments' => $comments
        ]);
    }

}