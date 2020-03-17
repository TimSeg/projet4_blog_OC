<?php

namespace App\Controller;

/**
 * Class SuperGlobalsController
 * @package App\Controller
 */
abstract class SuperGlobalsController
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
}