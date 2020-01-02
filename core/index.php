<?php

error_reporting(1);
ini_set('display_errors','Off');

setlocale(LC_CTYPE ,"ru_RU.CP1251");

define('OK', time());
define('IP', $_SERVER['REMOTE_ADDR']);
define('DEV_MODE', IP == '127.0.0.1');
define('DOMAIN', 'xcombats.com/core/');
define('IMAGES', 'xcombats.com/core/static');
define('DS'	, DIRECTORY_SEPARATOR);
define('PROJECT_PATH', __DIR__);
define('MEM', FALSE);
define('APP_PATH', __DIR__ . DS . 'app');
define('DP', DS . 'core'); //доп.директория
define('PROJECT_CLOSE', FALSE);

define('DB_HOST', 'localhost');
define('DB_NAME', 'xcombats');
define('DB_USER', 'xcombats');
define('DB_PASS', '');

define('RIGHTS', 'Старый Бойцовский Клуб');
define('COPY', 'Старый Бойцовский Клуб &copy; '.date('Y',OK));

define('LANG', 'ru');
require APP_PATH . DS . 'init.php';
?>