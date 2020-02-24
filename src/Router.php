<?php
namespace App;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class Router
 * @package App
 */
class Router
{
    /**
     * Default path for controllers
     */
    const DEFAULT_PATH          = 'App\Controller\\';

    /**
     * Default controller
     */
    const DEFAULT_CONTROLLER    = 'HomeController';

    /**
     * By Default launch method
     */
    const LAUNCH_METHOD        = 'launchMethod';

    /**
     * @var null
     */
    private $twig = null;

    /**
     * @var string
     */
    private $controller = self::DEFAULT_CONTROLLER;

    /**
     * @var string
     */
    private $method = self::LAUNCH_METHOD;

    /**
     * Router constructor
     */
    public function __construct()
    {
        $this->setTemplate();
        $this->parseUrl();
        $this->setController();
        $this->setMethod();
    }

    /**
     * @return mixed|void
     */
    public function setTemplate()
    {
        $this->twig = new Environment(new FilesystemLoader('../src/View'), array(
            'cache' => false,
            'debug' => true
        ));

        $this->twig->addExtension(new DebugExtension());

    }

    /**
     * @return mixed|void
     */
    public function parseUrl()
    {
        $access = filter_input(INPUT_GET, 'access');

        if (!isset($access)) {
            $access = 'launch';
        }

        $access = explode('!', $access);
        $this->controller = $access[0];
        $this->method = count($access) == 1 ? 'launch' : $access[1];
    }

    /**
     * @return mixed|void
     */
    public function setController()
    {
        $this->controller = ucfirst(strtolower($this->controller)) . 'Controller';
        $this->controller = self::DEFAULT_PATH . $this->controller;

        if (!class_exists($this->controller)) {
            $this->controller = self::DEFAULT_PATH . self::DEFAULT_CONTROLLER;
        }
    }

    /**
     * @return mixed|void
     */
    public function setMethod()
    {
        $this->method = strtolower($this->method) . 'Method';

        if (!method_exists($this->controller, $this->method)) {
            $this->method = self::LAUNCH_METHOD;
        }
    }

    /**
     * @return mixed|void
     */
    public function run()
    {
        $this->controller = new $this->controller($this->twig);
        $response = call_user_func([$this->controller, $this->method]);

        echo filter_var($response);
    }
}