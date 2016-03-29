<?php 
if (!defined('__SITE_PATH')) exit('No direct script access allowed');
class polls extends Db {

	public function get_authorize()
	{
		$names = $this->db->t2ttest->find();
		return $names;
	}
	public function get_staff_authorize($data)
	{	
		unset($data["rt"]);
		$data["password"] =base64_encode($data["password"]);
		$query=array("email"=>$data['email'],"password"=>$data["password"],"status"=>"active");
		$result = $this->db->staff->find($query);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res["name"] = $val['name'];
				$res["role"] = $val['role'];
				$res["staff_id"] = (string)$val['_id'];
				$res['message'] = "Successfully Login";
				if($val['role']!="Admin")
				{
					$att_data['staff_id']=$res["staff_id"];
					$att_data['role']=$res["role"];
					$att_data["login_date"] =date("Y-m-d");
					$att_data["login_time"] =date("H:i:s");
					$names = $this->db->attendence->insert($att_data);
				}
			}
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, username or password is invalid";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_admin_dashboard($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$query=array("role" => "Manager","login_date" =>$date);
		$result = $this->db->attendence->find($query)->count();
		if(!empty($result))
			$res["Manager"] = $result;
		else
			$res["Manager"] = 0;
		$query=array("role" => "sorter","login_date" =>$date);
		$result = $this->db->attendence->find($query)->count();
		if(!empty($result))
			$res["Sorter"] = $result;
		else
			$res["Sorter"] = 0;
		$query=array("role" => "DB","login_date" =>$date);
		$result = $this->db->attendence->find($query)->count();
		if(!empty($result))
			$res["DB"] = $result;
		else
			$res["DB"] = 0;
		$query=array("order_on" =>array("\$eq"=>$date));
		$result = $this->db->orders->find($query)->count();
		if(!empty($result))
			$res["Total Orders"] = $result;
		else
			$res["Total Orders"] = 0;
		$query=array("status" => "deliveried","order_on" =>array("\$eq"=>$date));
		$result = $this->db->orders->find($query)->count();
		if(!empty($result))
			$res["Total Completed Orders"] = $result;
		else
			$res["Total Completed Orders"] = 0;
		$query=array("status" => "","order_on" =>array("\$eq"=>$date));
		$result = $this->db->orders->find($query)->count();
		if(!empty($result))
			$res["Total Pending Orders"] = $result;
		else
			$res["Total Pending Orders"] = 0;
		$query=array(array('$match'=>array("order_on"=>array('$eq'=>$date))),array('$group'=>array("_id"=>'null',"total" =>array("\$sum"=>'$total_amount'))));
		$result = $this->db->orders->aggregate($query);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				if(is_array($val))
				{
					foreach($val as $vals)
					{
						$res["Total Amount"] = $vals['total'];
					}
				}
			}
		}
		else
			$res["Total Amount"] = 0;
		$query=array(array('$match'=>array("order_on"=>array('$eq'=>$date),"payment"=>"Pending")),array('$group'=>array("_id"=>'null',"total" =>array("\$sum"=>'$total_amount'))));
		$result = $this->db->orders->aggregate($query);
		if(!empty($result))
		{
			
			foreach($result as $val)
			{
				if(is_array($val))
				{
					foreach($val as $vals)
					{
						$res["Total Pending Amount"] = $vals['total'];
					}
				}
			}
		}
		else
			$res["Total Pending Amount"] = 0;
		$query=array(array('$match'=>array("order_on"=>array('$eq'=>$date),"payment"=>"Paid")),array('$group'=>array("_id"=>'null',"total" =>array("\$sum"=>'$total_amount'))));
		$result = $this->db->orders->aggregate($query);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				if(is_array($val))
				{
					foreach($val as $vals)
					{
						$res["Total Paid Amount"] = $vals['total'];
					}
				}
			}
		}
		else
			$res["Total Paid Amount"] = 0;
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function save_staff($data)
	{	
	//	unset($data["rt"]);
		$data_ins=array();
		foreach($data as $key => $val)
		{
			if($key=="pass")
				$data_ins["password"] =base64_encode($val);
			else
				$data_ins[$key] = $val;
		}
		$data_ins["alt_id"] =date("dmYHis");
		$data_ins["posted_on"] =date("d-m-Y H:i:s");
		$data_ins["status"]="active";
		$names = $this->db->staff->insert($data_ins);
		foreach($names as $keys => $val)
		{
			if($keys=='ok'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Your account has been created");
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, request Failed!!!";
		}
		$res = json_encode($res);
		return $res;
	}
	public function manager_save_staff($data)
	{	
	//	unset($data["rt"]);
		$data_ins=array();
		foreach($data as $key => $val)
		{
			if($key=="pass")
				$data_ins["password"] =base64_encode($val);
			else
				$data_ins[$key] = $val;
		}
		$data_ins["alt_id"] =date("dmYHis");
		$data_ins["posted_on"] =date("d-m-Y H:i:s");
		$data_ins["status"]="inactive";
		if($data_ins["role"]=="Sorter" || $data_ins["role"]=="DB")
		{
			$names = $this->db->staff->insert($data_ins);
			foreach($names as $keys => $val)
			{
				if($keys=='ok'&&$val)
				{
					$res['message'] = "request completed successfully";
					$res['result'] = array("message"=>"Your account has been created");
				}			
			}
		}
		else
		{
			$res['message'] = "Sorry, You can't create account for this ".$data_ins["role"]." role";
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, request Failed!!!";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_staff($data)
	{	
		unset($data["rt"]);
		$where["_id"]=new MongoId($data["employer_id"]);
		if(is_array($where))
			$result = $this->db->staff->find($where);
		else
			$result = $this->db->staff->find();
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res1["name"] = $val['name'];
				$res1["email"] = $val['email'];
				$res1["pass"] = base64_decode($val['password']);
				$res1["role"] = $val['role'];
				$res1["doj"] = $val['doj'];
				$res1["branch"] = $val['branch'];
				$res1["mobile"] = $val['mobile'];
				$res1["address"] = $val['address'];
				$res1["blood_group"] = $val['bgroup'];
				$res1["emg_number"] = $val['emg_number'];
				$res1["mobile"] = $val['mobile'];
				$res1["employer_id"] = (string)$val["_id"];			
			}			
		}
		if(empty($res1))
		{
			$res1['message'] = "Sorry, No Valid Output";
		}
		$res1 = json_encode($res1);
		return $res1;		
	}
	public function update_staff($data)
	{	
		//unset($data["rt"]);
		$data_ins=array();
		foreach($data as $key => $val)
		{
			if($key=="pass")
				$data_ins["password"] =base64_encode($val);
			elseif($key=="employer_id")
				$cond["_id"]=new MongoId($val);
			else
				$data_ins[$key] = $val;
		}
		$update_val = array('$set' => $data_ins);
		$upsert = array("upsert" => "true");
		$names = $this->db->staff->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Your account has been updated");
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, Updatation Failed";
		}
		$res = json_encode($res);
		return $res;


	}
	
	
	public function get_orders($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where="";
		if(isset($data["location"]))
		{
			if($data["location"]!="")
			{
				$where["branch"]=$data["location"];			
			}
		}
		if(isset($data["date"]))
		{
			if($data["date"]!="")
			{
				$where["order_on"]=array('$gte'=>$date);			
			}
		}
		if(isset($data["from_date"]) && isset($data["to_date"]))
		{
			if($data["from_date"]!=""&&$data["to_date"]!="")
			{
				$where["order_on"]=array('$gte'=>$data["from_date"],'$lte'=>$data["to_date"]);			
			}
		}
		else
		{
			$where["order_on"]=array('$gte'=>$date);
		}
		$sort_order=array("order_on"=>-1);
		if(is_array($where))
			$result = $this->db->orders->find($where)->sort($sort_order);
		else
			$result = $this->db->orders->find()->sort($sort_order);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res1["user_name"] = $val['user_name'];
				$res1["order_qty"] = $val['qty'];
				$res1["amount"] = $val['total Amount'];
				$res1["branch"] = $val['branch'];
				$res1["payment_mode"] = $val['payment_mode'];
				$res1["payment"] = $val['payment'];
				$res1["status"] = $val['status'];
				$res1["order_id"] = (string)$val["_id"];
				$res[]=$res1;
			}
			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_orders_detail($data)
	{
		unset($data["rt"]);$res2=array();$res4=array();
		$date=date("Y-m-d");
		$where="";
		if(isset($data["order_id"]))
		{
			if($data["order_id"]!="")
			{
				$where["_id"]=new MongoId($data["order_id"]);			
			}
		}
		if(is_array($where))
		{
			$result = $this->db->orders->find($where,array("order_details"));
		}
		if(!empty($result))
		{		
			foreach($result as $val)
			{
				foreach($val as $keys => $val1)
				{
					//print_r($val1);
					$i=0;
					foreach($val1 as $keys => $val2)
					{
						if(is_numeric($val2['item_name']))
						{
							continue;
						}
						$res1['item_name']=$val2['item_name'];
						$res1['catg']=$val2['catg'];
						$res1['cost']=$val2['cost'];
						$res1['qty']=$val2['qty'];
						$res1['popularity']=$val2['popularity'];
						$res1['user_note']=$val2['user_note'];
						$res2[]=$res1;
					}
					
					$res["order_details"]=$res2;
				}			
			}
			
		}
		if(is_array($where))
		{
			$result = $this->db->orders->find($where,array("user_details"));
		}
		if(!empty($result))
		{		
			foreach($result as $val)
			{
				foreach($val as $keys => $val1)
				{
					foreach($val1 as $keys => $val2)
					{
						if(is_numeric($val2['user_email']))
						{
							continue;
						}
						$res3['user_email']=$val2['user_email'];
						$res3['mobile']=$val2['mobile'];
						$res3['address']=$val2['address'];
						$res4[]=$res3;
					}
					$res["user_details"]=$res4;
				}			
			}
			
		}
		//print_r($res);
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_staff_list($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where["status"]="active";
		if(isset($data["role"]))
		{
			if($data["role"]!="")
			{
				$where["role"]=$data["role"];			
			}
			else
				$where["role"]="Manager";	
		}
		else
			$where["role"]="Manager";
		if(isset($data["location"]))
		{
			if($data["location"]!="")
			{
				$where["branch"]=$data["location"];			
			}
		}
		if(is_array($where))
		{
			$result = $this->db->staff->find($where)->count();
		}
		if(!empty($result))
		{			
			$res["total_count"]=$result;			
		}
		if(is_array($where))
		{
			$result = $this->db->staff->find($where);
		}
		if(!empty($result))
		{		
			$date=date("Y-m-d");
			foreach($result as $val)
			{
				$res1["Employer_id"] = (string)$val["_id"];
				$res1["Manager_name"] = $val['name'];
				$res1["branch"] = $val['branch'];
				$where1["staff_id"]=$res1["Employer_id"];
				$where1["login_date"] =$date;
				$result1 = $this->db->attendence->find($where1)->count();
				if(!empty($result1))
					$res1["Status"] = 1;
				else
					$res1["Status"] = 0;
				$res2[]=$res1;
			}
			if(!empty($res2))
				$res["Managers_details"]=$res2;
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_staff_list_details($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where="";
		$where["status"]="active";
		if(isset($data["role"]))
		{
			if($data["role"]!="")
			{
				$where["role"]=$data["role"];			
			}
			else
				$where["role"]="Manager";	
		}
		else
			$where["role"]="Manager";	
		if(isset($data["employer_id"]))
		{
			if($data["employer_id"]!="")
			{
				$where["_id"]=new MongoId($data["employer_id"]);			
			}
		}
		if(is_array($where))
		{
			$result = $this->db->staff->find($where);
		}
		if(!empty($result))
		{		
			$to_date=date("Y-m-d");
			$from_date=date("Y-m-01");
			$day=(int)date("d");
            $abse = 0;
			foreach($result as $val)
			{
				$res["name"] = $val['name'];
				$res["email"] = $val['email'];
				$res["role"] = $val['role'];
				$res["doj"] = $val['doj'];
				$res["branch"] = $val['branch'];
				$res["mobile"] = $val['mobile'];
				$res["address"] = $val['address'];
				$res["blood_group"] = $val['bgroup'];
				$res["emg_number"] = $val['emg_number'];
				$res["mobile"] = $val['mobile'];
				$res["employer_id"] = (string)$val["_id"];	
				$where1["login_date"]=array('$gte'=>$from_date,'$lte'=>$to_date);
				$result1 = $this->db->attendence->find($where1)->count();
				if(!empty($result1))
				{
					$res["days-worked-current-month"] = $result1;
					$abse  = $day - $result1;
				}
				$res["any-leaves"] = $abse;			
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function remove_staff($data)
	{
		unset($data["rt"]);
		$where="";
		if(isset($data["employer_id"]))
		{
			if($data["employer_id"]!="")
			{
				$where["_id"]=new MongoId($data["employer_id"]);			
			}
		}
		$update["status"]="delete";
		$rem =array('$set'=>$update);
		if(is_array($where))
		{
			$result = $this->db->staff->update($where,$rem);
		}
		if(!empty($result))
		{		
			$res['message'] = "Deleted Successfully!!";
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}

	
	function user_approve($data)
	{
		unset($data["rt"]);
		$cond["_id"]=new MongoId($data['employer_id']);
		$data_ins["status"]=$data['status'];
		$update_val = array('$set' => $data_ins);
		$upsert = array("upsert" => "true");
		$names = $this->db->staff->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Your account has been approved");
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, User approve Failed";
		}
		$res = json_encode($res);
		return $res;
	}
	function user_approve_list($data)
	{
		unset($data["rt"]);
		$where["status"]="inactive";
		$result = $this->db->staff->find($where);
		foreach($result as $val)
		{
			$res1['name']=$val["name"];
			$res1['employer_id']=(string)$val['_id'];
			$res1['DOJ']=$val['doj'];
			$res[]=$res1;
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, User approve List invalid";
		}
		$res = json_encode($res);
		return $res;
	}

	function get_manager_dashboard($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where1['_id']=new MongoId($data['employer_id']);;
		$result = $this->db->staff->find($where1);
		foreach($result as $val)
		{
			$location=$val["branch"];			
		}
		$where["branch"]=$location;			
			
		if(isset($data["from_date"]) && isset($data["to_date"]))
		{
			if($data["from_date"]!=""&&$data["to_date"]!="")
			{
				$where["order_on"]=array('$gte'=>$data["from_date"],'$lte'=>$data["to_date"]);			
			}
		}
		else
		{
//			$where["order_on"]=$date;
		}
		$sort_order=array("order_on"=>-1);
		if(is_array($where))
			$result = $this->db->orders->find($where)->sort($sort_order);
		else
			$result = $this->db->orders->find()->sort($sort_order);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res1["user_name"] = $val['user_name'];
				$res1["order_qty"] = $val['qty'];
				$res1["amount"] = $val['total_amount'];
				$res1["branch"] = $val['branch'];
				$res1["payment_mode"] = $val['payment_mode'];
				$res1["payment"] = $val['payment'];
				$res1["status"] = $val['status'];
				$res1["order_id"] = (string)$val["_id"];
				$res[]=$res1;
			}
			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function get_sorter_list($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where1['_id']=new MongoId($data['employer_id']);;
		$result = $this->db->staff->find($where1);
		$location="";
		foreach($result as $val)
		{
			$location=$val["branch"];			
		}
		$where["branch"]=$location;
		$where["role"]="Sorter";
		if(is_array($where))
			$result = $this->db->staff->find($where);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res1["name"] = $val['name'];
				$res1["employer_id"] = (string)$val['_id'];
				$where2["sorter_id"]=$val['_id'];
				$where2['order_on']=$date;
				$result1 = $this->db->orders->find($where2)->count();
				if(!empty($result1))
					$res1["order_count"] = $result1;
				else
					$res1["order_count"] = 0;
				$res[]=$res1;
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function assign_order_cook($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$time=date("H:i:s");
		$cond['_id']=new MongoId($data['order_id']);
		$data_ins1['status']="cooking";
		$data_ins1['sorter_id']=$data['sorter_id'];
		$update_val = array('$set' => $data_ins1);
		$upsert = array("upsert" => "true");
		$names = $this->db->orders->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Order successfully assign to sorter");				
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function get_sorter_dashboard($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where["sorter_id"]=$data["employer_id"];
		$where["status"]="cooking";
		if(isset($data["from_date"]) && isset($data["to_date"]))
		{
			if($data["from_date"]!=""&&$data["to_date"]!="")
			{
				$where["order_on"]=array('$gte'=>$data["from_date"],'$lte'=>$data["to_date"]);			
			}
		}
		else
		{
			//$where["order_on"]=$date;
		}
		$sort_order=array("order_on"=>-1);
		if(is_array($where))
			$result = $this->db->orders->find($where)->sort($sort_order);
		else
			$result = $this->db->orders->find()->sort($sort_order);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res1["order_qty"] = $val['qty'];
				$res1["status"] = $val['status'];
				$res1["order_id"] = (string)$val["_id"];
				$res[]=$res1;
			}
			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_sorter_orders_detail($data)
	{
		unset($data["rt"]);
		$res2=array();
		$res4=array();
		$date=date("Y-m-d");
		$where="";
		if(isset($data["order_id"]))
		{
			if($data["order_id"]!="")
			{
				$where["_id"]=new MongoId($data["order_id"]);			
			}
		}
		if(is_array($where))
		{
			$result = $this->db->orders->find($where,array("order_details"));
		}
		if(!empty($result))
		{		
			foreach($result as $val)
			{
				foreach($val as $keys => $val1)
				{
					//print_r($val1);
					$i=0;
					foreach($val1 as $keys => $val2)
					{
						if(is_numeric($val2['item_name']))
						{
							continue;
						}
						$res1['item_name']=$val2['item_name'];
						$res1['catg']=$val2['catg'];
						$res1['qty']=$val2['qty'];
						$res1['user_note']=$val2['user_note'];
						$res2[]=$res1;
					}
					
					$res["order_details"]=$res2;
				}			
			}
			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}

	function assign_cook_pick($data)
	{
		unset($data["rt"]);
		$cond['_id']=new MongoId($data['order_id']);
		$data_ins1['status']="ready to pick";
		$update_val = array('$set' => $data_ins1);
		$upsert = array("upsert" => "true");
		$names = $this->db->orders->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Order Status successfully changed");
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;	
	}
	function get_DB_list($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where1['_id']=new MongoId($data['employer_id']);
		$result = $this->db->staff->find($where1);
		$name=$eid="";
		$location="";
		foreach($result as $val)
		{
			$location=$val["branch"];			
		}	
		$where["branch"]=$location;
		$where["role"]="DB";		
		if(is_array($where))
			$result = $this->db->staff->find($where);
		if(!empty($result))
		{			
			foreach($result as $val)
			{					
				$where2["employer_id"]=(string)$val['_id'];				
				$where2['current_date']=$date;
				$sort_data=array("current_date_time"=>-1);				
				$result1 = $this->db->db_track->find($where2)->sort($sort_data);
				$ready=1;	
				if(!empty($result1))
				{
					foreach($result1 as $val1)
					{			
						if($val1["current_status"]=="ready to pick")
						{
							$res1["name"] = $val['name'];
							$res1["employer_id"] = (string)$val['_id'];
							$res[]=$res1;
						}
						break;
					}
					
				}						
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No DB is free";
		}
		$res = json_encode($res);
		return $res;
	}	
	function assign_order_db($data)
	{
		$date=date("Y-m-d");
		$time=date("H:i:s");
		$cond['_id']=new MongoId($data['order_id']);
		$data_ins1['db_id']=$data['employer_id'];
		$update_val = array('$set' => $data_ins1);
		$upsert = array("upsert" => "true");
		$names = $this->db->orders->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Order successfully assign to DB");				
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function get_db_dashboard($data)
	{
		unset($data["rt"]);
		$date=date("Y-m-d");
		$where = array("db_id"=>$data['employer_id'],"status"=>"ready to pick");
		if(isset($data["from_date"]) && isset($data["to_date"]))
		{
			if($data["from_date"]!=""&&$data["to_date"]!="")
			{
				$where["order_on"]=array('$gte'=>$data["from_date"],'$lte'=>$data["to_date"]);			
			}
		}
		else
		{
			//$where["order_on"]=$date;
		}
		$sort_order=array("order_on"=>-1);
		if(is_array($where))
			$result = $this->db->orders->find($where)->sort($sort_order);
		else
			$result = $this->db->orders->find()->sort($sort_order);
		if(!empty($result))
		{			
			foreach($result as $val)
			{
				$res1["order_qty"] = $val['qty'];
				$res1["status"] = $val['status'];
				$res1["order_id"] = (string)$val["_id"];
				$res[]=$res1;
			}
			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	public function get_db_orders_detail($data)
	{
		unset($data["rt"]);
		$res2=array();$res4=array();
		$date=date("Y-m-d");
		$where="";
		if(isset($data["order_id"]))
		{
			if($data["order_id"]!="")
			{
				$where["_id"]=new MongoId($data["order_id"]);			
			}
		}
		if(is_array($where))
		{
			$result = $this->db->orders->find($where,array("order_details"));
		}
		if(!empty($result))
		{		
			foreach($result as $val)
			{
				foreach($val as $keys => $val1)
				{
					//print_r($val1);
					$i=0;
					foreach($val1 as $keys => $val2)
					{
						if(is_numeric($val2['item_name']))
						{
							continue;
						}
						$res1['item_name']=$val2['item_name'];
						$res1['catg']=$val2['catg'];
						$res1['qty']=$val2['qty'];
						$res2[]=$res1;
					}
					
					$res["order_details"]=$res2;
				}			
			}
			
		}
		if(is_array($where))
		{
			$result = $this->db->orders->find($where,array("user_details"));
		}
		if(!empty($result))
		{		
			foreach($result as $val)
			{
				foreach($val as $keys => $val1)
				{
					foreach($val1 as $keys => $val2)
					{
						if(is_numeric($val2['user_email']))
						{
							continue;
						}
						$res3['user_email']=$val2['user_email'];
						$res3['mobile']=$val2['mobile'];
						$res3['address']=$val2['address'];
						$res4[]=$res3;
					}
					$res["user_details"]=$res4;
				}			
			}
			
		}
		//print_r($res);
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function assign_pick_del($data)
	{
		unset($data["rt"]);
		$cond['_id']=new MongoId($data['order_id']);
		$data_ins1['status']="Out to delivery";
		$update_val = array('$set' => $data_ins1);
		$upsert = array("upsert" => "true");
		$names = $this->db->orders->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Order successfully out to delivery");				
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function assign_del_paid($data)
	{
		unset($data["rt"]);
		$cond['_id']=new MongoId($data['order_id']);
		$data_ins1['status']="Delivered";
		$data_ins1['payent']="paid";
		$update_val = array('$set' => $data_ins1);
		$upsert = array("upsert" => "true");
		$names = $this->db->orders->update($cond,$update_val,$upsert);
		foreach($names as $keys => $val)
		{
			if($keys=='nModified'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"Order successfully delivered");				
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
	function db_track($data)
	{
		unset($data["rt"]);
		$data_ins1['employer_id']=$data["employer_id"];
		$data_ins1['longitude']=$data["longitude"];
		$data_ins1['latitude']=$data["latitude"];
		$data_ins1['current_status']=$data["status"];
		$data_ins1['current_date']=date("Y-m-d");
		$data_ins1['current_time']=date("H:i:s");
		$data_ins1['current_date_time']=date("Y-m-d H:i:s");
		$names = $this->db->db_track->insert($data_ins1);
		foreach($names as $keys => $val)
		{
			if($keys=='ok'&&$val)
			{
				$res['message'] = "request completed successfully";
				$res['result'] = array("message"=>"DB track was store");
			}			
		}
		if(empty($res))
		{
			$res['message'] = "Sorry, No Valid Output";
		}
		$res = json_encode($res);
		return $res;
	}
}
