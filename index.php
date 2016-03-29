<?php

 /*** error reporting on ***/

// error_reporting(0);

/*** define the constants ***/
define('DS', '/');
define ('__SITE_PATH', realpath(dirname(__FILE__)));
define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('APP', 'application'.DS);
define('INCLUDES', 'includes'.DS);
define('MODEL', 'model'.DS);
include INCLUDES . DS . 'init' . EXT;

/*** a new db object ***/
 $registry->router = new router($registry);
 $registry->router->setPath (__SITE_PATH . '/controller');
 $registry->template = new template($registry);
 $registry->router->loader();



?> 

