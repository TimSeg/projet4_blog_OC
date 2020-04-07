<?php


namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * Class UserController
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
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];



        $pass_encrypted = password_hash($pass, PASSWORD_DEFAULT);
        ModelFactory::getModel('Users')->createData([
            'name' => $name,
            'email' => $email,
            'pass' => $pass_encrypted
        ]);


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
        $_SESSION['users'] = [];;
        return $this->twig->render('home.twig');



    }








}