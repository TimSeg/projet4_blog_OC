<?php

namespace App\Controller;






/**
 * Class GlobalsController
 * @package App\Controller
 */
abstract class GlobalsController
{
    /**
     * @var mixed
     */
    protected $get = null;

    /**
     * @var mixed
     */
    protected $post = null;

    /**
     * @var mixed|null
     */
    private $session = null;

    /**
     * @var mixed
     */
    private $user = null;

    /**
     * SuperGlobalsController constructor
     */
    public function __construct()
    {
        $this->get      = filter_input_array(INPUT_GET);
        $this->post     = filter_input_array(INPUT_POST);

        $this->session = filter_var_array($_SESSION);
        if (isset($this->session['users']))
        {
            $this->user = $this->session['users'];
        }
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     */
    public function sessionCreate(int $id, string $name, string $email, string $password,string $role)
    {
        $_SESSION['users'] = [
            'id'     => $id,
            'name'   => $name,
            'email'  => $email,
            'pass'   => $password,
            'role'   => $role
        ];
    }

    /**
     * @return void
     */
    public function sessionDestroy()
    {
        $_SESSION['users'] = [];
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        if (array_key_exists('users', [$this->session])) {
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
}