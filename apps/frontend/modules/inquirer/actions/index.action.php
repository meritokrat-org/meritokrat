<?php

load::model("inquirer/inquirer");

class inquirer_index_action extends frontend_controller {
	
	public function execute(){
		$this->list = inquirer_peer::instance()->get_inquirers();
	}
	
}

?>
