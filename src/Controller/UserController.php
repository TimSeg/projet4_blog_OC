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
        if (!empty($this->post['email']) && !empty($this->post['pass'])) {
            $user = ModelFactory::getModel('users')->readData($this->post['email'], 'email');

            if (password_verify($this->post['pass'], $user['pass'])) {
                $this->sessionCreate(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['pass'],
                    $user['admin']
                );
                if($user['admin'] === true)
                {
                   return $this->twig->render('admin.twig');
                }
                else return $this->twig->render('home.twig');
            }


        }
        return $this->twig->render('login.twig');

    }



    public function createMethod()
    {
        $name = $this->post['name'];
        $email = $this->post['email'];
        $pass = $this->post['pass'];




        $pass_encrypted = password_hash($pass, PASSWORD_DEFAULT);
        ModelFactory::getModel('Users')->createData([
            'name' => $name,
            'email' => $email,
            'pass' => $pass_encrypted
        ]);

        // if signup form is complete - subscriber is redirected
        if (isset($this->post['signup'])) {
            $user = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');
            $this->sessionCreate(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['pass'],
                $user['admin']
            );


            return $this->twig->render('admin.twig');

        }
        return $this->twig->render('home.twig');
    }






        /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function logoutMethod()
    {
        $this->sessionDestroy();
        $this->redirect('home');
    }










}