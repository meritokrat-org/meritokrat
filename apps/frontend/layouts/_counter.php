<?php

load::model('user/user_sessions');
load::model('user/user_visits_log');

if(session::get_user_id())
{
	user_sessions_peer::instance()->update(session::get_user_id());
	
	$id = db::get_scalar("SELECT id FROM user_visits_log WHERE user_id = :user_id ORDER BY id DESC LIMIT 1", array("user_id" => session::get_user_id()));
	if($id > 0)
		user_visits_log_peer::instance()->update(array(
			"id" => $id,
			"time_out" => date("Y-m-d H:i:s")
		));
	else
		user_visits_log_peer::instance()->insert(array(
			"user_id" => session::get_user_id(),
			"time_out" => date("Y-m-d H:i:s")
		));
}

?>