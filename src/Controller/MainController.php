<?php

namespace App\Controller;

use App\Controller\Extension\PhpAdditionalExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class MainController
 * Manages the Main Features
 * @package App\Controller
 */
abstract class MainController
{
    /**
     * @var Environment|null
     */
    protected $twig = null;

    protected $post = null;
    protected $get = null;
    protected $session = null;

    /**
     * MainController constructor
     * Creates the Template Engine & adds its Extensions
     */
    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader('../src/View'), array('cache' => false,));


        $this->twig->addExtension(new PhpAdditionalExtension());

        $this->post     = filter_input_array(INPUT_POST);
        $this->get      = filter_input_array(INPUT_GET);

        $this->session = filter_var_array($_SESSION);

    }

    /**
     * Redirects to another URL
     * @param string $page
     * @param array $params
     */
    public function redirect(string $page, array $params = [])
    {
        $params['access'] = $page;
        header('Location: index.php?' . http_build_query($params));

        exit;
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $view, array $params = [])
    {
        return $this->twig->render($view, $params);
    }



    
}