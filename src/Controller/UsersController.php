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




class UsersController extends MainController

{



    /**
     * @return array
     */
    private $post_content = [];




    private function postDataUser()
    {
        $this->post_content['name']    = $this->post['name'];
        $this->post_content['email']   = $this->post['email'];

    }


    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $pass
     * @param string $role
     */
    public function sessionCreate(int $id, string $name, string $email, string $pass,string $admin)
    {
        $_SESSION['users'] = [
            'id'     => $id,
            'name'   => $name,
            'email'  => $email,
            'pass'   => $pass,
            'admin'   => $admin
        ];
    }



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

                var_dump($_SESSION);

                $name = $user['name'];

                if($user['admin'] === '1'){
                  $this->redirect('admin');
                }
                return $this->twig->render('adminUser.twig',['name' => $name]);
            }
            else echo 'adresse ou mot de passe invalide';
        }
        return $this->twig->render('login.twig');



    }



    public function createMethod()
    {
        $user['name'] = $this->post['name'];
        $user['email'] = $this->post['email'];
        $user['pass'] = $this->post['pass'];
        $user['admin'] = 0;


        $pass_encrypted = password_hash($user['pass'], PASSWORD_DEFAULT);
        ModelFactory::getModel('Users')->createData([
            'name' => $user['name'],
            'email' => $user['email'],
            'pass' => $pass_encrypted,
            'admin' => $user['admin']
        ]);


        $this->redirect('Articles');
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

            ModelFactory::getModel('Users')->updateData($this->session['user']['id'], $this->post_content);
            $user = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');
            $_SESSION['users'] = [];
            $this->sessionCreate(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['pass'],
                $user['admin']
            );

            $this->redirect('adminUser');
        }

    }








        /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function logoutMethod()
    {
        $_SESSION['users'] = [];
        $this->redirect('home');


    }




}