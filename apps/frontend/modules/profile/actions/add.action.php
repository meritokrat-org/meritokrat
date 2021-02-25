<?

class profile_add_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		/*if (!session::has_credential('admin') and !user_auth_peer::instance()->is_inviter(session::get_user_id()))
		{
			throw new public_exception( t('У вас недостаточно прав') );
		}*/
            if(user_auth_peer::get_rights(session::get_user_id(),10) || session::has_credential('admin')){
                $this->first_name = addslashes(trim(strip_tags(request::get('first_name'))));
                $this->last_name = addslashes(trim(strip_tags(request::get('last_name'))));
                $this->gender = request::get('gender');
                if ( request::get('submit'))
		{
                        if($this->first_name && $this->last_name && request::get_int('city') && request::get_int('region') && request::get_int('country') && request::get_int('segment'))
                        {
                                $arr = db::get_cols("SELECT id FROM user_auth WHERE offline = ".session::get_user_id());
                                if(count($arr)>0)
                                {
                                    $num = db::get_scalar("SELECT COUNT(*) FROM user_data WHERE first_name = '".$this->first_name."'
                                        AND last_name = '".$this->last_name."'
                                        AND user_id IN (".implode(',',$arr).")");
                                    if ($num)
                                    {
                                        throw new public_exception( t('Вы уже создавали пользователя с такими данными') );
                                    }
                                }

                                load::model('user/user_auth');
                                load::model('user/user_data');

                                $umail = 'offline_'.session::get_user_id().'_'.time();
                                $id = user_auth_peer::instance()->insert($umail,'offline',1,false,0,0,0,0,0,session::get_user_id());
                                $user = user_auth_peer::instance()->get_item($id);

                                user_data_peer::instance()->insert(array(
                                        'user_id' => $user['id'],
                                        'first_name' => $this->first_name,
                                        'last_name' => $this->last_name,
                                        'gender' => request::get('gender', 'm'),
                                        'city_id' => request::get_int('city'),
					'region_id' => request::get_int('region'),
					'country_id' => request::get_int('country'),
					'segment' => request::get_int('segment'),
					'creator' => session::get_user_id()
                                ));
                                if (request::get_string('birthday')!='')
                                    user_data_peer::instance()->update(array('user_id' => $user['id'],'birthday' => request::get_string('birthday')));

                                load::model('user/user_bio');
                                user_bio_peer::instance()->insert(array(
                                        'user_id' => $user['id']
                                ));
                                load::model('user/user_work');
                                user_work_peer::instance()->insert(array(
                                        'user_id' => $user['id']
                                ));
                                load::model('user/user_education');
                                user_education_peer::instance()->insert(array(
                                        'user_id' => $user['id']
                                ));
                                load::model('user/user_desktop');
                                user_desktop_peer::instance()->insert(array(
                                        'user_id' => $user['id']
                                ));

                                $this->redirect('/profile-'.$user['id']);
                        }
                }
                }else{
            throw new public_exception( t('У вас недостаточно прав') );
        }
	}
}