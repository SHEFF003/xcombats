<?php
set_include_path(APP_PATH);
spl_autoload_extensions('.php');
spl_autoload_register();

require PROJECT_PATH . DS . 'lib' . DS . 'Twig' . DS . 'Autoloader.php';

Twig_Autoloader::register();

Core\Database::connect();
Core\Route::begin();

?>