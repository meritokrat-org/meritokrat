<?php

class admin_updatedb_action extends basic_controller
{
	public function execute()
	{
	    $params = conf::get('databases');
	    $params = $params['master'];
	    $connect = pg_connect ("host='".$params['host']."' port='5432' dbname='".$params['dbname']."' user='".$params['user']."' password='".$params['password']."'");
	    $users_list = db::get_cols("SELECT user_id FROM email_lists_users WHERE list_id=306");
	    if(!empty($users_list)) pg_query($connect,"DELETE FROM email_users WHERE id IN (".implode(',', $users_list).")");
	    pg_query($connect,"DELETE FROM email_lists_users WHERE list_id=306");
	    $femails = db::get_rows("SELECT d.user_id, d.first_name, d.last_name, a.email FROM user_data d JOIN user_auth a ON d.user_id=a.id WHERE d.gender='f' AND a.offline=0 AND a.del=0");
	    
	    foreach ($femails as $key => $value) {
		$check = db::get_scalar("SELECT id FROM email_users WHERE email='".$value['email']."' ORDER BY id DESC LIMIT 1");
		if(!$check) {
		    pg_query($connect,"INSERT INTO email_users (email,first_name, last_name, blacklisted) VALUES('".$value['email']."','".$value['first_name']."','".$value['last_name']."',0)");
		    $check = db::get_scalar("SELECT id FROM email_users WHERE email='".$value['email']."' ORDER BY id DESC LIMIT 1");
		}
		pg_query($connect,"INSERT INTO email_lists_users (user_id,list_id) VALUES(".$check.",306)");
	    }
	    
	}
}

