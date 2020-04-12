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
                    return $this->twig->render('home.twig');
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
        $admin = $_POST['admin'];



        $pass_encrypted = password_hash($pass, PASSWORD_DEFAULT);
        ModelFactory::getModel('Users')->createData([
            'name' => $name,
            'email' => $email,
            'pass' => $pass_encrypted,
            'admin' => $admin = 0
        ]);


        $this->redirect('Articles');
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