<?php

load::model('user/user_voter');

class profile_user_voter_action extends frontend_controller
{
	public function execute()
	{
		$this->set_renderer("ajax");
		$this->json['success'] = true;
		
		$act = request::get_string('act');
		if(in_array($act, array('get_item', 'save', 'add_info', 'add_contact', 
			'save_task', 'remove_contact', 'save_money_collection', 'remove_money_collection',
			'save_opinion_leaders', 'remove_opinion_leaders', 'save_agents_influence',
			'remove_agents_influence', 'set_command_member', 'save_command_member_tasks')))
		{
			return $this->json['success'] = $this->$act();
		}
	}
	
	public function remove_contact()
	{
		$user_id = request::get_int('user_id');
		$index = request::get_int('index');
		
		$cond = array('user_id' => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		if( ! $item = user_voter_peer::instance()->get_item($list[0]))
			return false;
		
		unset($item['informator'][$index]);
		
		$data = array(
			'id' => $item['id'],
			'informator' => $item['informator']
		);
		
		user_voter_peer::instance()->update($data);
		
		return true;
	}
	
	public function add_contact()
	{
		$user_id = request::get_int('user_id');
		
		$index = request::get_int('index');
		
		$data = request::get_all();
		$data = $data['data'];
		
		$cond = array('user_id' => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		if( ! $item = user_voter_peer::instance()->get_item($list[0]))
			return false;
		
		$item['informator'][$index]['contacts'][] = array(
			'type' => $data['info_type'],
			'date_day' => $data['info_date_day'],
			'date_month' => $data['info_date_month'],
			'date_year' => $data['info_date_year'],
			'result' => $data['info_result']
		);
		
		$data = array(
			'id' => $list[0],
			'informator' => $item['informator']
		);
		
		$this->json['data'] = $data;
		
		user_voter_peer::instance()->update($data);
		
		return true;
	}
	
	public function add_info()
	{
		$user_id = request::get_int('user_id');
		$data = request::get_all();
		$data = $data['data'];
		
		$cond = array('user_id' => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$informator = array();
		
		if($item = user_voter_peer::instance()->get_item($list[0]))
		{
			$informator = $item['informator'];
		}
		
		$this->json['informator'] = $informator;
		
		$informator[] = array(
			'first_name' => $data['first_name'],
			'middle_name' => $data['middle_name'],
			'last_name' => $data['last_name'],
			'name' => $data['first_name'].' '.$data['last_name'],
			'email' => $data['info_email'],
			'country' => $data['country'],
			'region' => $data['region'],
			'city' => $data['city'],
			'address' => $data['address'],
			'work_phone' => $data['work_phone'],
			'home_phone' => $data['home_phone'],
			'home_phone' => $data['home_phone'],
			'contacts' => array(array(
				'type' => $data['info_type'],
				'date_day' => $data['info_date_day'],
				'date_month' => $data['info_date_month'],
				'date_year' => $data['info_date_year'],
				'result' => $data['info_result']
			))
		);
		
		$data = array(
			'user_id' => $user_id,
			'informator' => $informator
		);
		
		if(count($list) > 0)
		{
			$data['id'] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data['id'] = user_voter_peer::instance()->insert($data);
		}
		
		return true;
	}
	
	public function get_item()
	{
		$user_id = request::get_int("user_id");
		
		$cond = array('user_id' => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		if( ! $item = user_voter_peer::instance()->get_item($list[0]))
			return true;
		
		$this->json['data'] = $item;
		
		return true;
	}
	
	public function save()
	{
		$data = request::get_all();
		
		$type = request::get_string('type');
		$user_id = request::get_int('user_id');
		$data = $data["data"];
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$data = array(
			'user_id' => $user_id,
			$type.'_data' => $data
		);
		
		$item = user_voter_peer::instance()->get_item($list[0]);
		if($type != 'admin' && ! count(array_diff($item['user_data'], $item['admin_data'])))
			$data['admin_data'] = $data['user_data'];
		
		if(count($list) > 0)
		{
			$data['id'] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data['id'] = user_voter_peer::instance()->insert($data);
		}
		
		$this->json['diff'] =  count(array_diff($item['user_data'], $item['admin_data']));
		
		return true;
	}
	
	public function save_task()
	{
		$data = request::get_all();
		
		$user_id = request::get_int('user_id');
		$data = $data["data"];
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$data = array(
			'user_id' => $user_id,
			'tasks_data' => $data
		);
		
		if(count($list) > 0)
		{
			$data['id'] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data['id'] = user_voter_peer::instance()->insert($data);
		}
		
		return true;
	}
	
	public function save_money_collection()
	{
		$data = request::get_all();
		
		$type = request::get_string("type");
		$user_id = request::get_int("user_id");
		$data = $data["data"];
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$money_collection = array();
		if($item = user_voter_peer::instance()->get_item($list[0]))
			$money_collection = $item["money_collection"];
		
		if(count($money_collection[$type]) < 1)
			$money_collection[$type] = array();
		
		$money_collection[$type][] = $data;
		
		$data = array(
			"user_id" => $user_id,
			"money_collection" => $money_collection
		);
		
		if(count($list) > 0)
		{
			$data['id'] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data['id'] = user_voter_peer::instance()->insert($data);
		}
		
		return true;
	}
	
	public function remove_money_collection()
	{
		$user_id = request::get_int("user_id");
		$type = request::get_string("type");
		$index = request::get_int("index");
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		if(count($cond) < 1)
			return false;
		
		$item = user_voter_peer::instance()->get_item($list[0]);
		
		unset($item["money_collection"][$type][$index]);
		
		$data = array(
			"id" => $item["id"],
			"user_id" => $user_id,
			"money_collection" => $item["money_collection"]
		);
		
		user_voter_peer::instance()->update($data);
		
		return true;
	}
	
	public function save_opinion_leaders()
	{
		$data = request::get_all();
		
		$user_id = request::get_int("user_id");
		$type = request::get_string("type");
		$data = $data["data"];
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$opinion_leaders = array();
		if($item = user_voter_peer::instance()->get_item($list[0]))
			$opinion_leaders = $item["opinion_leaders"];
		
		if(count($opinion_leaders[$type]) < 1)
			$opinion_leaders[$type] = array();
		
		$opinion_leaders[$type][] = $data;
		
		$data = array(
			"user_id" => $user_id,
			"opinion_leaders" => $opinion_leaders
		);
		
		if(count($list) > 0)
		{
			$data['id'] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data['id'] = user_voter_peer::instance()->insert($data);
		}
		
		return true;
	}
	
	public function remove_opinion_leaders()
	{
		$user_id = request::get_int("user_id");
		$type = request::get_string("type");
		$index = request::get_int("index");
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		if(count($cond) < 1)
			return false;
		
		$item = user_voter_peer::instance()->get_item($list[0]);
		
		unset($item["opinion_leaders"][$type][$index]);
		
		$data = array(
			"id" => $item["id"],
			"user_id" => $user_id,
			"opinion_leaders" => $item["opinion_leaders"]
		);
		
		user_voter_peer::instance()->update($data);
		
		return true;
	}
	
	public function save_agents_influence()
	{
		$data = request::get_all();
		
		$user_id = request::get_int("user_id");
		$type = request::get_string("type");
		$data = $data["data"];
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$agents_influence = array();
		if($item = user_voter_peer::instance()->get_item($list[0]))
			$agents_influence = $item["agents_influence"];
		
		if(count($agents_influence[$type]) < 1)
			$agents_influence[$type] = array();
		
		$agents_influence[$type][] = $data;
		
		$data = array(
			"user_id" => $user_id,
			"agents_influence" => $agents_influence
		);
		
		if(count($list) > 0)
		{
			$data['id'] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data['id'] = user_voter_peer::instance()->insert($data);
		}
		
		return true;
	}
	
	public function remove_agents_influence()
	{
		$user_id = request::get_int("user_id");
		$type = request::get_string("type");
		$index = request::get_int("index");
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		if(count($cond) < 1)
			return false;
		
		$item = user_voter_peer::instance()->get_item($list[0]);
		
		unset($item["agents_influence"][$type][$index]);
		
		$data = array(
			"id" => $item["id"],
			"user_id" => $user_id,
			"agents_influence" => $item["agents_influence"]
		);
		
		user_voter_peer::instance()->update($data);
		
		return true;
	}
	
	public function set_command_member()
	{
		$user_id = request::get_int("user_id");
		$state = request::get_int("state");
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$data = array(
			"command_member" => $state
		);
		
		if(count($list) > 0)
		{
			$data["id"] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data["id"] = user_voter_peer::instance()->insert($data);
		}
		
		$this->json["state"] = $state;
		
		return true;
	}
	
	public function save_command_member_tasks()
	{
		$data = request::get_all();
		
		$user_id = request::get_int("user_id");
		$data = $data["data"];
		
		$cond = array("user_id" => $user_id);
		$list = user_voter_peer::instance()->get_list($cond);
		
		$data = array(
			"command_member_tasks" => $data
		);
		
		if(count($list) > 0)
		{
			$data["id"] = $list[0];
			user_voter_peer::instance()->update($data);
		}
		else
		{
			$data["id"] = user_voter_peer::instance()->insert($data);
		}
		
		return true;
	}
}

?>
