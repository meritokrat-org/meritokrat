<?

load::app('modules/lists/controller');
class lists_add_lists_action extends lists_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');
                $lists = request::get('fr');
                $id = request::get_int('id');

                if(count($lists)>0)
                {
                    foreach ( $lists as $list_id )
                    {
                            $id = lists_users_peer::instance()->insert(array(
                                'list_id' => $list_id,
                                'user_id' => $id
                            ));
                    }
                }
	}
}