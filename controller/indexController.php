<?php
   
Class indexController Extends baseController {    

	public function index() 
	{
		$polls = new polls();
		$names = $polls->get_authorize();
		foreach($names as $val){
			echo "aa".$val['name'];
		}
	}
	public function save_staff() 
	{
		$polls = new polls();
		$data=json_decode(file_get_contents('php://input'));
		$result = $polls->save_staff($data);
		echo $result;
	}
	public function get_staff() 
	{
		$polls = new polls();
		$result = $polls->get_staff($_GET);
		echo $result;
	}
	public function update_staff() 
	{
		$polls = new polls();
		$data=json_decode(file_get_contents('php://input'));
		$result = $polls->update_staff($data);
		echo $result;
	}
	public function get_staff_authorize() 
	{
		$polls = new polls();
		$result = $polls->get_staff_authorize($_GET);
		echo $result;
	}
	public function get_admin_dashboard() 
	{
		$polls = new polls();
		$result = $polls->get_admin_dashboard($_GET);
		echo $result;		
	}
	public function get_orders() 
	{
		$polls = new polls();
		$result = $polls->get_orders($_GET);
		echo $result;		
	}
	public function get_orders_detail() 
	{
		$polls = new polls();
		$result = $polls->get_orders_detail($_GET);
		echo $result;		
	}
	public function get_staff_list() 
	{
		$polls = new polls();
		$result = $polls->get_staff_list($_GET);
		echo $result;		
	}
	public function get_staff_list_details() 
	{
		$polls = new polls();
		$result = $polls->get_staff_list_details($_GET);
		echo $result;		
	}
	public function remove_staff() 
	{
		$polls = new polls();
		$result = $polls->remove_staff($_GET);
		echo $result;		
	}
	public function manager_save_staff() 
	{
		$polls = new polls();
		$data=json_decode(file_get_contents('php://input'));
		$result = $polls->manager_save_staff($data);
		echo $result;
	}
	public function user_approve() 
	{
		$polls = new polls();
		$result = $polls->user_approve($_GET);
		echo $result;
	}
	public function user_approve_list() 
	{
		$polls = new polls();
		$result = $polls->user_approve_list($_GET);
		echo $result;
	}
	public function get_manager_dashboard() 
	{
		$polls = new polls();
		$result = $polls->get_manager_dashboard($_GET);
		echo $result;		
	}
	public function get_sorter_list() 
	{
		$polls = new polls();
		$result = $polls->get_sorter_list($_GET);
		echo $result;		
	}
	public function assign_order_cook() 
	{
		$polls = new polls();
		$result = $polls->assign_order_cook($_GET);
		echo $result;		
	}
	public function get_sorter_dashboard() 
	{
		$polls = new polls();
		$result = $polls->get_sorter_dashboard($_GET);
		echo $result;		
	}
	public function get_sorter_orders_detail() 
	{
		$polls = new polls();
		$result = $polls->get_sorter_orders_detail($_GET);
		echo $result;		
	}
	public function assign_cook_pick() 
	{
		$polls = new polls();
		$result = $polls->assign_cook_pick($_GET);
		echo $result;		
	}
	public function db_track() 
	{
		$polls = new polls();
		$result = $polls->db_track($_GET);
		echo $result;		
	}
	public function get_DB_list() 
	{
		$polls = new polls();
		$result = $polls->get_DB_list($_GET);
		echo $result;		
	}
	public function assign_order_db() 
	{
		$polls = new polls();
		$result = $polls->assign_order_db($_GET);
		echo $result;		
	}
	public function get_db_dashboard() 
	{
		$polls = new polls();
		$result = $polls->get_db_dashboard($_GET);
		echo $result;		
	}
	public function get_db_orders_detail() 
	{
		$polls = new polls();
		$result = $polls->get_db_orders_detail($_GET);
		echo $result;		
	}
	public function assign_pick_del() 
	{
		$polls = new polls();
		$result = $polls->assign_pick_del($_GET);
		echo $result;		
	}
	public function assign_del_paid() 
	{
		$polls = new polls();
		$result = $polls->assign_del_paid($_GET);
		echo $result;		
	}

}


