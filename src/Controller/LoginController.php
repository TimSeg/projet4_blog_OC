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
     * @var mixed|null
     */
    protected $session = null;

    /**
     * @var mixed
     */
    private $user = null;



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
    public function sessionCreate(int $id, string $name, string $email, string $password,string $role)
    {
        $_SESSION['users'] = [
            'id'          => $id,
            'name'        => $name,
            'email'       => $email,
            'pass'        => $password,
            'role'        => $role
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
            $user = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');

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