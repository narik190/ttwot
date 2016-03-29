<?php  if ( ! defined('__SITE_PATH')) exit('No direct script access allowed');

//header("Content-Type: text/html; charset=UTF8-NOBOM");
//putenv('TZ=Asia/calcutta');
date_default_timezone_set("Asia/Kolkata"); 
//define('DS', '/');
/*if (preg_match("/sunilsada.onmypc.net/i", $_SERVER['HTTP_HOST'])) 
{
	//define('BASEURL', 'http://' . $_SERVER['HTTP_HOST'] .DS);
	//define('DATA', 'http://' . $_SERVER['HTTP_HOST'] .DS."data".DS);

	define('BASEURL', 'http://sunilsada.onmypc.net/politicana'.DS);
	define('DATA', 'http://sunilsada.onmypc.net/politicana/data'.DS);


} else {
	//define('BASEURL', 'http://dev.tssil.com/sahivalue/testing'.DS);
	//define('DATA', 'http://dev.tssil.com/sahivalue/testing/data'.DS);
	
	define('BASEURL', 'http://localhost:8081/t2t'.DS);
	
}*/
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', 'roott2t');
define('DB_NAME', 't2t');
