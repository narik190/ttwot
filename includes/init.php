<?php 
session_start();

//echo "<pre>";
//print_r($_SESSION);

 /*** include the controller class ***/
 include __SITE_PATH  . DS. APP . DS . 'controller_base.class'.EXT;

 /*** include the registry class ***/
 include __SITE_PATH  . DS. APP . DS . 'registry.class'.EXT;

 /*** include the router class ***/
 include __SITE_PATH  . DS. APP . DS . 'router.class'.EXT;

 /*** include the template class ***/
 include __SITE_PATH  . DS. APP . DS . 'template.class'.EXT;
 
 include __SITE_PATH  . DS. INCLUDES . DS . 'config.inc'.EXT;
  //include __SITE_PATH  . DS. INCLUDES . DS . 'tables'.EXT;

 include __SITE_PATH . DS. INCLUDES . DS . 'common'.EXT;
 
 include __SITE_PATH . DS. INCLUDES . DS . 'db'.EXT;
 
 //include __SITE_PATH . DS. INCLUDES . DS . 'pagination'.EXT;  
 
// include __SITE_PATH . DS. INCLUDES . DS . 'class.phpmailer'.EXT;  
 
//  include __SITE_PATH . DS. INCLUDES . DS . 'class.smtp'.EXT;  
  // Tumb Generator	



  //include __SITE_PATH . DS. INCLUDES . DS . 'ipaddress'.EXT;  


/***************  Don't Remove The Below Code   ********************/

//echo "<pre>";
//print_r($_SERVER);
//exit;

	$ip = $_SERVER['REMOTE_ADDR'];
	// remember chmod 0777 for folder 'cache'
	/*
	$file = $_SERVER['DOCUMENT_ROOT'] . "/cache/".$ip;
	if(file_exists($file))
	{
		$get_ip_details = file_get_contents($file);
	} 
	else 
	{
	*/

 
 /*** auto load model classes ***/
    function __autoload($class_name) {
    $filename = strtolower($class_name) . '.class'.EXT;
    $file = __SITE_PATH . DS. MODEL . DS . $filename;

    if (file_exists($file) == false)
    {
        return false;
    }
  include ($file);
}
	
 /*** a new registry object ***/
 $registry = new registry;

/*** a new db object ***/
 global $db;
 $db = new Db();
 //echo"<pre>";
//print_r($db);

 //define('DB_ACCESS', $db);


 
 //$connect=$registry->db->connect();
 

?>
