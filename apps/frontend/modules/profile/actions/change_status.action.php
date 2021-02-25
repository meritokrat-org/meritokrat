<?

class profile_change_status_action extends frontend_controller
{
	public function execute()
	{
                $this->user=user_auth_peer::instance()->get_item(session::get_user_id());
                $this->user_data=user_data_peer::instance()->get_item(session::get_user_id());
                
		if ($this->user['status']<5)
		{
                    if (!$this->user['offline'] && !$this->user_data['photo_salt']) $errors[]=t('не загружена фотография');
                    if (!$this->user_data['birthday']) $errors[]=t('не указан день рождения');
                    if (!$this->user_data['gender']) $errors[]=t('не указан пол');
                    if (!$this->user_data['segment']) $errors[]=t('не указано поле "сфера деятельности"');
                    if (!$this->user_data['location']) $errors[]=t('не указан населенный пункт');
                    if ($errors) {
                        print json_encode($errors);
                    }
                    else
                    {
                        user_auth_peer::instance()->update(array('status'=>'5'), array('id'=>session::get_user_id()));
                        $inviter = ($this->user['recomended_by']) ? $this->user['recomended_by'] : (($this->user['invited_by']) ? $this->user['invited_by'] : 0);
                        if($inviter) rating_helper::updateRating($inviter, 'status');
                        print json_encode(array('succes'=>1));
                    }
		}
                die();
	}
}
