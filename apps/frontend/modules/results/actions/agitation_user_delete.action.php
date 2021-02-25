<?

class results_agitation_user_delete_action extends frontend_controller
{
	protected $authorized_access = true;
        //protected $credentials = array('admin');

	public function execute()
	{
		$this->set_renderer('ajax');
                $this->json = array();

                load::model('user/user_agitmaterials');
                load::model('user/user_agitmaterials_log');

                $item = user_agitmaterials_log_peer::instance()->get_item(request::get_int('id'));
                user_agitmaterials_log_peer::instance()->delete_item(request::get_int('id'));

                $user1 = user_agitmaterials_log_peer::instance()->get_user($item['user_id'],$item['type']);

                db::exec('UPDATE '.user_agitmaterials_peer::instance()->get_table_name().'
                    SET receive=:receive, given=:given, presented=:presented
                    WHERE user_id=:user_id AND type=:type',array(
                        'user_id'=>$item['user_id'],
                        'type'=>$item['type'],
                        'receive'=>intval($user1['receive']),
                        'given'=>intval($user1['given']),
                        'presented'=>intval($user1['presented'])
                    ));

                if($item['profile'])
                {
                    db::exec('DELETE FROM '.user_agitmaterials_log_peer::instance()->get_table_name().'
                        WHERE user_id=:user_id AND receive=:receive AND date=:date AND type=:type',
                        array(
                            'user_id'=>$item['profile'],
                            'receive'=>$item['given'],
                            'date'=>$item['date'],
                            'type'=>$item['type']
                        ));

                    $user2 = user_agitmaterials_log_peer::instance()->get_user($item['profile'],$item['type']);

                    db::exec('UPDATE '.user_agitmaterials_peer::instance()->get_table_name().'
                        SET receive=:receive, given=:given, presented=:presented
                        WHERE user_id=:user_id AND type=:type',array(
                            'user_id'=>$item['profile'],
                            'type'=>$item['type'],
                            'receive'=>intval($user2['receive']),
                            'given'=>intval($user2['given']),
                            'presented'=>intval($user2['presented'])
                        ));
                }

	}
}