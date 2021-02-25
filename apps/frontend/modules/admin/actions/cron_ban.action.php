<?

class admin_cron_ban_action extends basic_controller
{
	public function execute()
	{
        load::model('ban/ban');
        load::model('user/user_auth');
        $this->disable_layout();
        $bans=ban_peer::instance()->get_active_bans();
        foreach($bans as $b){
            $this->user = user_auth_peer::instance()->get_item($b['user_id']);
            ban_peer::instance()->update(array("id"=>$b['id'],"active"=>0));
            user_auth_peer::instance()->update(array("id"=>$this->user['id'],"status"=>$this->user['ban'],"ban"=>0));
        }
        die();
        }
}
