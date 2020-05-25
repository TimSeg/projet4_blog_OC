<?php

//use Tracy\Debugger;

/* Call Required of the Composer Autoload to load Classes */
require_once '../vendor/autoload.php';

session_start();

//Debugger::enable();

/* Create the Router */
$router = new App\Router();

/* Run Application */
$router->run();