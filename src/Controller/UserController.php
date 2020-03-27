<?php


namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * Class UsersController
 * @package App\Controller
 */




class UserController extends MainController

{


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function launchMethod()
    {
        if ($this->getUserVar('role') === 'admin')
        {
            $posts = ModelFactory::getModel('Posts')->listData();
            $comments = ModelFactory::getModel('Comments')->listData();
            $user = ModelFactory::getModel('Users')->listData();

            return $this->twig->render('admin.twig', [
                'posts' => $posts,
                'comments' => $comments,
                'user' => $user
            ]);
        }
        elseif ($this->getUserVar('role') === 'member')
        {
            return $this->twig->render('admin.twig');
        }
        $this->redirect('home');
    }

    /**
     * @var array
     */
    private $post_content = [];

    private function postData()
    {
        $this->post_content['name']    = $this->post['name'];
        $this->post_content['email']   = $this->post['email'];
        $this->post_content['role']  = $this->post['role'];
    }

    private function postDataUser()
    {

        $this->post_content['name']   = $this->post['name'];
        $this->post_content['email']   = $this->post['email'];
        $this->post_content['role']   = $this->getUserVar('role');
    }




    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createMethod()
    {
        $name = $this->post['name'];
        $email = $this->post['email'];
        $pass = $this->post['pass'];

        if (empty($name && $email && $pass)) {
            if ($this->getUserVar('role') === 'admin') {
                return $this->twig->render('adminCreate.twig');
            }
            $this->redirect('home');
        }

        $pass_encrypted = password_hash($pass, PASSWORD_DEFAULT);
        ModelFactory::getModel('Users')->createData([
            'name' => $name,
            'email' => $email,
            'pass' => $pass_encrypted
        ]);

        // Redirection if signup form complete
        if (isset($this->post['signup'])) {
            $user = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');
            $this->sessionCreate(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['pass'],
                $user['role']
            );
            $this->redirect('home');
        }
        return $this->twig->render('admin.twig');
    }


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteMethod()
    {
        $id_User = $this->get['id'];

        $id_confirmed = ModelFactory::getModel('Comments')->listData($id_User, 'user_id');

        if (!empty($id_confirmed))
        {
            ModelFactory::getModel('Comments')->deleteData($this->get['id'], 'user_id');
        }

        ModelFactory::getModel('Users')->deleteData($this->get['id']);

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

            ModelFactory::getModel('Users')->updateData($this->get['id'], $this->post_content);

            $this->redirect('admin');
        }
        if ($this->getUserVar('role') == 'admin') {
            $admin = ModelFactory::getModel('Users')->readData($this->get['id']);

            return $this->twig->render('adminModify.twig', [
                'admin' => $admin
            ]);

        } $this->redirect('home');
    }

    /**
     * @return string|mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function usereditMethod()
    {
        if (!empty($this->post)) {
            $this->postDataUser();

            ModelFactory::getModel('Users')->updateData($this->get['id'], $this->post_content);
            $user = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');
            $this->sessionDestroy();
            $this->sessionCreate(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['pass'],
                $user['role']
            );

            $this->redirect('admin');
        }
        $admin = ModelFactory::getModel('Users')->readData($this->get['id']);

        return $this->twig->render('admin.twig',[
            'admin' => $admin
        ]);
    }



}