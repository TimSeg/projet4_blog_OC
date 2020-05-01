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
        $createdArticle = ModelFactory::getModel('Articles')->createIt($title,$content);
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

        $article_confirmed = ModelFactory::getModel('Comments')->listData($id_article, 'article_id');

        if (!empty($article_confirmed))
        {
            ModelFactory::getModel('Comments')->deleteData($id_article, 'article_id');
        }

        ModelFactory::getModel('Articles')->deleteData($id_article);

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

    }

}