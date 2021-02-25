<?
load::app('modules/photo/controller');
class photo_delete_action extends photo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		$this->set_renderer('ajax');
                $this->json = array();

                $this->album_id = request::get_int('album_id');

		if ($this->access = $this->get_access())
		{
			photo_peer::instance()->delete_item(request::get_int('photo_id'));
		}
	}
}
