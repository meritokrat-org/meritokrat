<?php

load::model("inquirer/inquirer");
load::model("user/user_data");

class inquirer_stat_action extends frontend_controller {
	
	public function execute(){
		
		$id = request::get_int("id");
		
		$this->inquirer = inquirer_peer::instance()->get($id);
		
		if(!$this->inquirer){
			
		}
		
		$summa = array();
		$stat = array();
		$users = array();
		foreach($this->inquirer["answers"] as $user_id => $answers){
			$user_data = user_data_peer::instance()->get_item($user_id);
			
			foreach($answers as $question => $answer){
				$summa[$question] += 1;
				if(is_array($answer)){
					foreach($answer as $value){
						$stat[$question][$value] += 1;
						$users[$question][$value][] = "<a href=\"/profile-".$user_id."\">".$user_data["first_name"]." ".$user_data["last_name"]."</a>";
					}
				} else {
					$stat[$question][$answer] += 1;
					$users[$question][$answer][] = "<a href=\"/profile-".$user_id."\">".$user_data["first_name"]." ".$user_data["last_name"]."</a>";
				}
			}
		}
		
		$procent = array();
		foreach($stat as $question => $answers){
			foreach($answers as $answer => $val){
				$procent[$question][$answer] = $val * 100 / $summa[$question];
			}
		}
		
		$this->stat = $stat;
		$this->procent = $procent;
		$this->users = $users;
		
	}
	
}

?>
