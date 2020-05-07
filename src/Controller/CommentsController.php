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

        return $this->render("post.twig", [
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
        $author  = $this->get['name'];
        $content = $this->post['content'];
        $article_id = $this->get['id'];
        $user_id = $this->get['id'];

        if (empty($content)) {
            $this->redirect('articles');
        }
        ModelFactory::getModel('Comments')->createData([
            'author'  => $author,
            'content' => $content,
            'post_id' => $post_id,
            'user_id' => $user_id
        ]);
        $this->commentRedirect($article_id,'!read');
    }

}