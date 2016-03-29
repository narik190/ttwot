<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class Db {

	var $result_id = NULL;
	var $result    = NULL;

public $post = '';
public $get = '';
public $session = '';
public $action = '';
public $_product = 'product';
public $db;
	
	function __construct() {	
	$connection =	new MongoClient();
	$this->db = $connection->selectDB(DB_NAME);
	if(isset($_POST)) 
		$this->post = (object) $_POST;
	if(isset($_GET))
		$this->get = (object) $_GET;
	if(isset($_SESSION))
		$this->session = (object) $_SESSION;
	}
}

