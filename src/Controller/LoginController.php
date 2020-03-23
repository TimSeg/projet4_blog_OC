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




    /**
     * @param int $id
     * @param string $first_name
     * @param string $last_name
     * @param string $nickname
     * @param string $email
     * @param string $password
     * @param string $status
     */
    public function sessionCreate(int $id, string $first_name, string $last_name, string $nickname, string $email, string $password,string $status)
    {
        $_SESSION['users'] = [
            'id'          => $id,
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'nickname'    => $nickname,
            'email'       => $email,
            'pass'        => $password,
            'status'      => $status
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
            $user = ModelFactory::getModel('Admin')->readData($this->post['email'], 'email');

            if (password_verify($this->post['pass'], $user['pass'])) {
                $this->sessionCreate(
                    $user['id'],
                    $user['first_name'],
                    $user['last_name'],
                    $user['nickname'],
                    $user['email'],
                    $user['pass'],
                    $user['status']
                );
                if($this->getUserVar('status') === 'admin')
                {
                    $this->redirect('admin');
                }
                $this->redirect('home');
            }
        }
        if($this->getUserVar('status') === 'admin')
        {
            $this->redirect('admin');
        }
        elseif ($this->getUserVar('status') === 'member')
        {
            $this->redirect('admin');
        }
        elseif ($this->getUserVar('status') === 'visitor') {
            $this->redirect('home');
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