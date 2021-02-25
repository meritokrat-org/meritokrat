<?
class photocompetition_voters_action extends frontend_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');
        
        
	public function execute()
	{
            load::model('photo/photo_competition');
            $this->photo=photo_competition_peer::instance()->get_item(request::get_int('id'));
            
	}
}