<?

abstract class reestr_controller extends frontend_controller {

	protected $authorized_access = true;

	public function init() {
		parent::init();
		//if (!session::has_credential('admin')) $this->redirect('/home');
		load::model('geo');
		load::model('ppo/ppo');
		load::model('ppo/members');
		load::model('user/user_data');
		
		$id = isset($_GET["id"]) ? $_GET["id"] : session::get_user_id();
		
		$this->user = user_auth_peer::instance()->get_item($id);
                $rpo_leader=db::get_cols("SELECT group_id FROM ppo_members 
     WHERE user_id=:user_id AND function IN(1,2) 
     AND group_id IN(SELECT id FROM ppo WHERE category IN(3) AND active=1)",array("user_id"=>session::get_user_id()));
                $mpo_leader=db::get_cols("SELECT group_id FROM ppo_members 
     WHERE user_id=:user_id AND function IN(1,2) 
     AND group_id IN(SELECT id FROM ppo WHERE category IN(2) AND active=1)",array("user_id"=>session::get_user_id()));
                
                $user_po=$rpo_leader?$rpo_leader[0]:$mpo_leader[0];
		if( (!session::has_credential('admin') && $this->user["status"] != "20") || (!session::has_credential('admin') && !$user_po))
			$this->redirect('/');
		
		
		$this->user_data = user_data_peer::instance()->get_item($this->user['id']);
		
		$this->region_id = null;
		$this->allList = true;
		
		if(isset($_GET["id"]) || ($this->user["status"] == "20" && !session::has_credential('admin'))){
                    $po_info = ppo_peer::instance()->get_item($user_po);
			$this->region_id = $po_info['region_id'];
                        if($po_info['category']==2)$this->city_id = $po_info['city_id'];
			$this->allList = false;
		}
		
		
	}

}
