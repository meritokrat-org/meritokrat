<?
load::app('modules/zayava/controller');
class zayava_del_action extends zayava_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');
                $this->json = array();
								
                if(request::get_int('id'))
                {
									if(user_zayava_peer::instance()->in_trash(request::get_int('id'))){
										user_zayava_peer::instance()->delete_item(request::get_int('id'));
									} else {
										$inp = array(
											'id' => request::get_int('id'),
											'user_id' => session::get_user_id(),
											'del_reason' => request::get('reason')
										);
										user_zayava_peer::instance()->to_trash($inp);
									}
                }
	}
}