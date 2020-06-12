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
    protected $session = null;


    private function postDataUser()
    {
        $this->post_content['name']    = $this->post['name'];
        $this->post_content['email']   = $this->post['email'];

    }

//create session for admin ou user
    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $pass
     * @param string $role
     */
    public function sessionCreate(int $id, string $name, string $email, string $pass,string $admin)
    {
        $_SESSION['user'] = [
            'id'     => $id,
            'name'   => $name,
            'email'  => $email,
            'pass'   => $pass,
            'admin'   => $admin
        ];
    }


    //create session on login + redirect to admin or user navigation
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

                $name = $user['name'];
                $id   = $user['id'];

                if($user['admin'] === '1'){
                    $this->redirect('Admin');
                }
                return $this->twig->render('Welcome.twig',['name' => $name, 'id' => $id]);
            }
            else echo '<h3 class = "warning">ERREUR : adresse ou mot de passe invalide </h3>';
        }
        return $this->twig->render('Login.twig');
    }


//avoid password typing for admin when session is still open and direct admin or user to correct personnal page
    public function adminredirectMethod()
    {

        $name = $this->session['user']['name'];
        $id = $this->session['user']['id'];


        if ($this->session['user']['admin'] === '1') {
            $this->redirect('Admin');
        } elseif ($this->session['user']['admin'] === '0') {
            return $this->twig->render('AdminUser.twig',['name' => $name, 'id' => $id]);
        }
        return $this->twig->render('Error.twig');
    }



    //create new user in database + session when sign-up form is complete
    public function createMethod()
    {

        if (!empty($this->post['name']) && !empty($this->post['email']) && !empty($this->post['pass'])) {

            $user['name'] = $this->post['name'];
            $user['email'] = $this->post['email'];
            $user['pass'] = $this->post['pass'];
            $user['admin'] = 0;


            $pass_encrypted = password_hash($user['pass'], PASSWORD_DEFAULT);
            $users = ModelFactory::getModel('users')->readData($this->post['email'], 'email');

// check if mail already exists in database -> send to error page
            if ($this->post['email'] === $users['email']) {
                return $this->twig->render('ErrorMail.twig');
            }
// create new user
            ModelFactory::getModel('users')->createData([
                'name' => $user['name'],
                'email' => $user['email'],
                'pass' => $pass_encrypted,
                'admin' => $user['admin']
            ]);

// create session if new user has completed the form
                $user = ModelFactory::getModel('users')->readData($this->post['email'], 'email');
                $this->sessionCreate(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['pass'],
                    $user['admin']
                );
                return $this->twig->render('NewUser.twig');

            }

    }




    // update user personnal infos
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

            ModelFactory::getModel('users')->updateData($this->session['user']['id'], $this->post_content);
            $user = ModelFactory::getModel('users')->readData($this->post['email'], 'email');
            $_SESSION['user'] = [];
            $this->sessionCreate(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['pass'],
                $user['admin']
            );

            $this->redirect('AdminUser');
        }

    }



    // delete a user
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */

    public function deleteMethod()
    {
        $user_id = $this->get['id'];
        $confirmed_id = ModelFactory::getModel('comments')->listData($user_id, 'user_id');

        if (!empty($confirmed_id))
        {
            ModelFactory::getModel('comments')->deleteData($this->get['id'], 'user_id');
        }
        ModelFactory::getModel('users')->deleteData($this->get['id']);

        $this->sessionDestroy();
        return $this->twig->render('DeleteConfirmation.twig');

    }


    //disconnect and close user/admin session
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function logoutMethod()
    {
        $this->sessionDestroy();
        return $this->twig->render('LogoutConfirmation.twig');

    }




}