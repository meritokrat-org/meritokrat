<?

class profile_delete_process_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                $user_data = user_data_peer::instance()->get_item(session::get('delete_id'));
                $user = user_auth_peer::instance()->get_item(session::get('delete_id'));
                load::action_helper('user_email',false);
                
		if ( 
                        session::get('delete_hash') == request::get('hash') and 
                        session::get('delete_id')>0 and 
                        (
                                session::get_user_id()==session::get('delete_id') or 
                                session::has_credential('admin') or 
                                (
                                        !$user['del'] &&
                                        !$user['active'] && 
                                        (
                                                $user['invited_by'] == session::get_user_id() ||
                                                $user['recomended_by'] == session::get_user_id()
                                        )
                                )
                        )
                    )
		{

                        
                        load::model('user/user_desktop');

                        if(!request::get_int('type'))
                        {
                            user_auth_peer::instance()->update(array(
                                'id' => session::get('delete_id'),
                                'email' => $user['email'].',',
                                'del' => session::get_user_id(),
                                'del_ts' => time(),
                                'why' => trim(strip_tags(request::get('why'))),
                                'active' => 'false',
                                ));
                                user_data_peer::instance()->update(array(
                                    'user_id' => session::get('delete_id'),
                                    'duplicate' => 2
                                ));
                                user_desktop_peer::instance()->update(array(
                                    'user_id'=>session::get('delete_id'),
                                    'functions'=>'{}'
                                ));
                                db::exec('DELETE FROM user_desktop_funct WHERE user_id = '.session::get('delete_id'));
                                mem_cache::i()->delete('user_duplicate');
                        }
                        else
                        {
                            user_auth_peer::instance()->delete_item(session::get('delete_id'));
                            user_data_peer::instance()->delete_item(session::get('delete_id'));
                            load::model('user/user_shevchenko_data');
                            user_shevchenko_data_peer::instance()->delete_item(session::get('delete_id'));
                            load::model('user/user_bio');
                            user_bio_peer::instance()->delete_item(session::get('delete_id'));
                            load::model('user/user_work');
                            user_work_peer::instance()->delete_item(session::get('delete_id'));
                            load::model('user/user_education');
                            user_education_peer::instance()->delete_item(session::get('delete_id'));
                            friends_peer::instance()->delete_by_user(session::get('delete_id'));
                            db::exec('DELETE FROM blogs_posts WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM blogs_comments WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM events_comments WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM events2users WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM groups_members WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM ideas WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM ideas_comments WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM invites WHERE to_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM messages WHERE sender_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM polls WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM polls_comments WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM friends WHERE friend_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM bookmarks WHERE type = 2 AND oid = '.session::get('delete_id'));
                            db::exec('DELETE FROM bookmarks WHERE type = 6 AND oid = '.session::get('delete_id'));
                            db::exec('DELETE FROM user_desktop_funct WHERE user_id = '.session::get('delete_id'));
                            user_desktop_peer::instance()->update(array(
                                    'user_id'=>session::get('delete_id'),
                                    'functions'=>'{}'
                                ));
                            db::exec('DELETE FROM ppo_members WHERE user_id = '.session::get('delete_id'));
                            db::exec('DELETE FROM user_zayava WHERE user_id = '.session::get('delete_id'));
                            mem_cache::i()->delete('user_duplicate');
                        }
                        
                        
                        foreach (user_auth_peer::get_admins() as $receiver)
                          {
                                load::action_helper('user_email',false);
                                $options = array(
                                    '%why%' => trim(strip_tags(request::get('why'))),
                                    '%name%' => $user_data['first_name'].' '.$user_data['last_name'],
                                    '%link%' => 'https://meritokrat.org/admin/users_create?submit=1&first_name='.$user_data['first_name'].'&last_name='.$user_data['last_name'].'&email='.$user['email'].'&gender='.$user_data['gender'],
                                    '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$receiver.'&tab=settings',
                                    '%profile%'=>'http://'. context::get('host') . '/profile-'.$user_data['user_id'],
                                    '%status%'=> user_auth_peer::get_status($user['status']),
                                    '%admin%'=> user_helper::full_name(session::get_user_id(), TRUE, array(), false)
                                    );
                                $template = (!session::has_credential('admin')) ? 'profile_delete_process' : 'admin_profile_delete';
                                user_email_helper::send_sys($template,$receiver,null,$options);
                          }
                        user_sessions_peer::instance()->set_offline(session::get('delete_id'));
                        if (session::get_user_id()==session::get('delete_id')) session::unset_user();
		}
		if(session::has_credential('admin'))
                        $this->redirect($_SERVER['HTTP_REFERER']);
		$this->redirect('/');
	}
}