<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of verifier
 *
 * @author till20052
 */
load::system("storage/storage_simple");

class admin_verifier_action extends frontend_controller {
	
	public function execute(){
		
		$storage = new storage_simple();
		die($storage->get_path("mp/photocompetition/44f683a8.jpg"));
		
		if( ! ($limit = request::get_int("limit")))
			$limit = 100;
						
		$users = db::get_cols("SELECT id FROM user_auth WHERE status = 20 ORDER BY id LIMIT {$limit};");
		
		load::model("blogs/posts");
		$this->diffs = array();
		foreach($users as $user){
			$this->from_redis[$user] = blogs_posts_peer::get_not_viewed_by_user($user, array(5, 31));
			$this->from_db[$user] = blogs_posts_peer::get_not_viewed_by_user($user, array(5, 31), false);
			$this->diffs[$user] = array_diff($this->from_redis[$user], $this->from_db[$user]);
		}
		
	}
	
}

?>
