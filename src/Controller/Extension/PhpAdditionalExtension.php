<?php

namespace App\Controller\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class PhpAdditionalTwigExtension
 * Adds features to Twig Views
 */

class PhpAdditionalExtension extends AbstractExtension
{

    private $session = null;


    /**
     * Adds functions to Twig Views
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('url', array($this, 'url')),
            new TwigFunction('hasFlash', array($this, 'hasFlash')),
            new TwigFunction('typeFlash', array($this, 'typeFlash')),
            new TwigFunction('messageFlash', array($this, 'messageFlash'))
        );
    }

    /**
     * Returns the Page URL
     * @param string $page
     * @param array $params
     * @return string
     */
    public function url(string $page, array $params = [])
    {
        $params['access'] = $page;
        return 'index.php?' . http_build_query($params);
    }


    /**
     * @return bool
     */
    public function hasFlash() {
        return empty($this->session['flash']) == false;
    }

    public function typeFlash()
    {
        if (isset($this->session['flash'])){
            return $this->session['flash']['type'];
        }
    }

    public function messageFlash()
    {
        if (isset($this->session['flash'])){
            echo filter_var($this->session['flash']['message']);
            unset($_SESSION['flash']);
        }
    }




}

