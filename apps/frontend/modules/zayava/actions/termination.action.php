<?php
/**
 * Description of zayava_termination_action
 *
 * @author Morozov Artem
 */

load::model("user/zayava_termination");
load::model('user/membership');
load::action_helper('membership', false);

class zayava_termination_action extends frontend_controller 
{
	
	public function execute()
	{
		$user_id = session::get_user_id();
		
		$this->membership = user_membership_peer::instance()->get_user($user_id);
		
//		if(!(session::is_authenticated() && session::has_credential("admin")) && !$this->membership)
//		{
//			$this->redirect("/home");
//			var_dump("Redirect");
//		}
		
		
		if(request::get_int("id")){
			$this->statement_data = user_zayava_termination_peer::instance()->get_statement(request::get_int("id"), array("visible" => "TRUE"));
		} else {
			$user_id = session::get_user_id();
			if(request::get_int("user_id")){
				$user_id = request::get_int("user_id");
			}
			$this->statement_data = user_zayava_termination_peer::instance()->get_statement_by_user_id($user_id, array("visible" => "TRUE"));
		}
		
		if(request::get("act"))
		{
			$act = request::get("act");
			
			$this->set_renderer("ajax");
			$this->json = array("success" => true);
			
			switch($act)
			{
				case "print":
					$this->set_renderer("html");
					$this->set_layout('');
					$this->set_template("termination_print");
					if(!($this->statement_data))
					{
						$this->redirect("/zayava/termination");
					}
					break;
				
				case "confirm_statement":
					if($id = request::get_int("id"))
					{
						$time = user_helper::dateval('statement-date-confirmation');
						user_zayava_termination_peer::instance()->confirm_statement($id, $time);
						$this->json["statement"] = user_zayava_termination_peer::instance()->get_statement($id);
						
						$mid = user_membership_peer::instance()->get_list(array("user_id" => $this->json["statement"]["user_id"]));
						$mid = $mid[0];
						
						$user_membership = array(
							'id' => $mid,
							'user_id' => $this->json["statement"]["user_id"],
							'removedate' => user_helper::dateval('statement-date-confirmation'),
							'remove_type' => 1, //off_type = Добровільно
							'remove_from' => session::get_user_id()
						);
						user_membership_peer::instance()->update($user_membership);
						
						membership_helper::change_status($this->json["statement"]["user_id"], 5); // Прихильник
					}
					else {
						$this->json = array("success" => false);
					}
					break;
				
				case "remove_statement":
					if($id = request::get_int("id"))
					{
						user_zayava_termination_peer::instance()->remove_statement($id);
					}
					else
					{
						$this->json = array("success" => false);
					}
					break;
				
				case "send_statement":
					if(!$this->statement_data || !$this->statement_data["visible"])
					{
						$statement_data = array(
								"user_id" => request::get_int("user_id"),
						);
						$this->json["statement_id"] = user_zayava_termination_peer::instance()->add_statement($statement_data);
					}
					break;
			}
		}
	}
	
}

?>
