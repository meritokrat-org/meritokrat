<?php

load::app("modules/zayava/controller");
load::model("user/user_auth");
load::model("user/user_data");
load::model('geo');
load::model('messages/messages');
load::model("user/user_payments"); // user_membership_peer
load::model("user/membership");

function nextmonth($data)
{
    $next = date('n',$data)+1;
    $year = date('Y',$data);
    if($next>12)
    {
        $next=1;
        $year+=1;
    }
    return mktime(0, 0, 0, $next, 1, $year);
}

class zayava_vipusk_action extends zayava_controller
{
	private function _getUserData($id)
	{
		if( ! ($item = user_data_peer::instance()->get_item(session::get_user_id())))
			return false;
		
		$geo = geo_peer::instance()->get_country($item['country_id']);
		
		$location = $geo["name_ua"];
		
		if($item['region_id'])
		{
			$geo = geo_peer::instance()->get_region($item['region_id']);
			$location .= " / ".$geo["name_ua"];
		}
		
		if($item["city_id"])
		{
			$geo = geo_peer::instance()->get_city($item['city_id']);
			$location .= " / ".$geo["name_ua"];
		}
		
		$item["location"] = $location;
		
		return $item;
	}
	
	private function _send_message()
	{
		$item = $this->_getUserData(session::get_user_id());
		
		$body = "<div style=\"font-weight: bold\">ЗАЯВА ПРО ВИПУСК НОВОГО ПАРТІЙНОГО КВИТКА</div>";
		$body .= "<div style=\"margin-top: 10px\">".$item["last_name"]." ".$item["first_name"]." ".$item["father_name"]."</div>";
		$body .= "<div>".$item["location"]."</div>";
		
		messages_peer::instance()->add(array(
			'sender_id' => session::get_user_id(),
			'receiver_id' => 31,
			'body' => $body
		), false, 0);
		
		$this->json["mtype"] = 0;
		
		$membership = user_membership_peer::instance()->get_user(session::get_user_id());
		$ppayments = user_payments_peer::instance()->get_user(session::get_user_id());
		
		$_pppa = array();
		foreach($ppayments as $pid){
			$ip = user_payments_peer::instance()->get_item($pid);
			if($ip["type"] != 2){ continue; }
			$_pppa[] = $ip;
		}
		
		// var_dump($_pppa)
		if(date('j', $membership['invdate']) <= 15){
			$date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']));
		} else {
			$date = nextmonth($membership['invdate']);
		}
			
		// $date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']));
		
		$curdate = mktime(0, 0, 0, date('n'), 1, date('Y'));
		
		$tt = 0;
		for($i = $date; $i <= $curdate; $i = nextmonth($i)){
			$approve = 0;
			foreach($_pppa as $_pppaa){
				if($_pppaa["period"] == $i)
				{
					$approve = $_pppaa["approve"];
				}
			}
			
			if($approve != 2)
			{
				$tt++;
			}
		}
		
		if($tt > 0)
		{
			$this->json["mtype"] = 1;
			$this->json["dolg"] = $tt;
		}
		
		db_key::i()->set("vipusk-id-".session::get_user_id(), true);
		
		return true;
	}
	
	public function execute()
	{
		$this->set_layout(NULL);
		
		$act = request::get_string("act");
		if($act != "" && in_array($act, array("send_message")))
		{
			$this->set_renderer('ajax');
			$method = "_".$act;
			return $this->json["success"] = $this->$method();
		}
		
		$this->data = array();
		
		if( ! session::is_authenticated() || ! ($item = $this->_getUserData(session::get_user_id())))
			$this->redirect("/");
		
		$this->data["user"] = $item;
	}
}

?>
