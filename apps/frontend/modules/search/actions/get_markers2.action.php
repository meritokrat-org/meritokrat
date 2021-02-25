<?
class search_get_markers2_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
            $this->set_renderer('ajax'); 
            $u=user_data_peer::instance()->get_item(request::get_int('user_id')); 
            if($u['user_id']>0) 
            user_data_peer::instance()->update(array("user_id"=>$u['user_id'],
                "locationlat"=> mb_substr($u['locationlat'],0,mb_strlen($u['locationlat'])-2).rand(0,9).rand(0,9),
                "locationlng"=> mb_substr($u['locationlng'],0,mb_strlen($u['locationlng'])-2).rand(0,9).rand(0,9)));
            $this->json=$data;
        }
}
