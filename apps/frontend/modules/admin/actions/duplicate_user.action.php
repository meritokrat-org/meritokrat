<?

load::app('modules/admin/controller');
class admin_duplicate_user_action extends admin_controller
{

	public function execute()
	{
                $this->set_renderer('ajax');
                $this->json = array();

                if(request::get_int('id') && request::get_int('type'))
                {
                    $id = request::get_int('id');
                    $type = request::get_int('type');

                    $user_data = user_data_peer::instance()->get_item($id);
                    $user = user_auth_peer::instance()->get_item($id);

                    if($type==2)
                    {
                        user_auth_peer::instance()->update(array(
                            'id' => $id,
                            'email' => $user['email'].',',
                            'del' => session::get_user_id(),
                            'del_ts' => time(),
                            'why' => t('дубль'),
                            'active' => 'false'
                        ));
                        user_data_peer::instance()->update(array(
                            'user_id' => $id,
                            'duplicate' => 2
                        ));
                        load::action_helper('user_email',false);
                        foreach (user_auth_peer::get_admins() as $receiver)
                        {
                            $options = array(
                                '%why%' => t('дубль'),
                                '%name%' => $user_data['first_name'].' '.$user_data['last_name'],
                                '%link%' => 'https://meritokrat.org/admin/users_create?submit=1&first_name='.$user_data['first_name'].'&last_name='.$user_data['last_name'].'&email='.$user['email'].'&gender='.$user_data['gender'],
                                '%settings%'=>'https://'. context::get('host') . '/profile/edit?id='.$receiver.'&tab=settings',
                                '%profile%'=>'https://'. context::get('host') . '/profile-'.$user_data['user_id'],
                                '%status%'=> user_auth_peer::get_status($user_data['user_id'])
                                );
                            user_email_helper::send_sys('profile_delete_process',$receiver,null,$options);
                        }
                    }
                    else
                    {
                        user_data_peer::instance()->update(array(
                            'user_id' => $id,
                            'duplicate' => 1
                        ));
                    }
                    mem_cache::i()->delete('user_duplicate');
                }

	}
}