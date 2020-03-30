<?php


namespace App\Controller;


use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LoginController
 * @package App\Controller
 */
class LoginController extends MainController
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
                    $user['role']
                );
                if($this->getUserVar('role') === 'admin')
                {
                    $this->redirect('admin');
                }
                $this->redirect('home');
            }
        }
        if($this->getUserVar('role') === 'admin')
        {
            $this->redirect('admin');
        }
        elseif ($this->getUserVar('status') === 'member')
        {
            $this->redirect('admin');
        }

        return $this->twig->render('login.twig');




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