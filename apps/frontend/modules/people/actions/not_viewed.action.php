<?php
class people_not_viewed_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{   
                
                load::model('user/user_view');
                $additional_info = user_view_peer::instance()->get_not_viewed(session::get_user_id());
                
                $new=user_view_peer::instance()->get_new();
                
                if (request::get('mode')=='new')
                {
                    is_array($new) ? $this->users = $new : $this->users =array();
                }
                else 
                {
                    $this->users = $additional_info;
                }
                
                $this->additional_count = count($additional_info);
                $this->new_users_count = (is_array($new) ? count($new) : 0);
                    
                load::action_helper('pager');
                $this->pager = pager_helper::get_pager($this->users, request::get_int('page'), 25);
                $this->users = $this->pager->get_list();
                
                db_key::i()->set('last_user_view_time:'.session::get_user_id(),time());
        }
}