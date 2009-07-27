<?php

define('BASE_PATH', dirname(__FILE__));
define('LIBRARY_PATH', '/var/Library');
defined('APPLICATION_PATH') || define('APPLICATION_PATH',LIBRARY_PATH . '/Application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
set_include_path('.' . PATH_SEPARATOR . LIBRARY_PATH  . PATH_SEPARATOR  . get_include_path());//block2
include_once(LIBRARY_PATH . '/Ig/Application.php');
$zfApp = new Ig_Application;
$zfApp->setEnvironment(APPLICATION_ENV);
$zfApp->bootstrap(LIBRARY_PATH . '/Sites/localhost/configs/application.ini');


