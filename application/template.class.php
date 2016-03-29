<?php

Class Template{

private $registry;

private $db;

private $vars = array();

function __construct($registry) {
	$this->registry = $registry;

	$this->db = new Db();

//echo "<pre>";
//print_r($this->db->testing_db_globally());
//exit;

}

public function __set($index, $value)
{
	$this->vars[$index] = $value;
}


function show($name) {
	$path = __SITE_PATH . '/views' . '/' . $name . '.php';

	if (file_exists($path) == false)
	{
		throw new Exception('Template not found in '. $path);
		return false;
	}


	foreach ($this->vars as $key => $value)
	{
		$$key = $value;
	}
	include ($path);               
}


}

