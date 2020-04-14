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
    private $user = null;
    private $session = null;
    private $post = null;
    private $get = null;



    /**
     * @return bool
     */
    public function isLogged()
    {
        if (array_key_exists('users', $this->session)) {
            if (!empty($this->user)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $var
     * @return mixed
     */
    public function getUserVar($var)
    {
        if ($this->isLogged() === false) {
            $this->user[$var] = null;
        }
        return $this->user[$var];
    }





    private function postDataUser()
    {
        $this->post_content['first_name']  = $this->post['first_name'];
        $this->post_content['last_name']   = $this->post['last_name'];
        $this->post_content['nickname']    = $this->post['nickname'];
        $this->post_content['email']       = $this->post['email'];

        $this->post_content['status']      = $this->getUserVar('status');
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
        if (!empty($_POST['email']) && !empty($_POST['pass'])) {
            $user = ModelFactory::getModel('users')->readData($_POST['email'], 'email');

            if (password_verify($_POST['pass'], $user['pass'])) {
                $this->sessionCreate(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['pass'],
                    $user['admin']
                );

                if($user['admin'] === '1'){
                  $this->redirect('admin');
                }
                elseif ($user['admin'] === '0'){
                    $this->redirect('users!useredit');
                }
            }

            else echo 'adresse ou mot de passe invalide';
        }

        return $this->twig->render('login.twig');
    }



    public function createMethod()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $admin = 0;



        $pass_encrypted = password_hash($pass, PASSWORD_DEFAULT);
        ModelFactory::getModel('Users')->createData([
            'name' => $name,
            'email' => $email,
            'pass' => $pass_encrypted,
            'admin' => $admin
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
        if (!empty($_POST)) {
            $this->postDataUser();

            ModelFactory::getModel('Users')->updateData($this->get['id'], $this->post_content);
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
        //$admin = ModelFactory::getModel('Users')->readData($this->get['id']);

        return $this->twig->render('adminUser.twig');
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