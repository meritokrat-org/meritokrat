<?php

class profile_contact_delete_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                
                load::model('user/user_desktop');
                load::model('user/user_contact');
                
                $this->is_regional_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id());
                $this->is_raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id());
                $this->is_predstavitel_vnz = db::get_scalar("SELECT user_id FROM user_desktop WHERE functions && '{14}' and user_id=:uid",array('uid'=>session::get_user_id()));
		
                $contact = user_contact_peer::instance()->get_item(request::get_int('id'));
                if (!$contact)
		{
                    throw new public_exception('Помилка');
                }
                
		if (!session::has_credential('admin'))
		{
                    if ((!$this->is_raion_coordinator && !$this->is_regional_coordinator) && (!$this->is_predstavitel_vnz) && ($contact['who']!=5 && $contact['who']!=6))
                    throw new public_exception('Помилка, недостатно прав');
                }
                    user_contact_peer::instance()->delete_item(request::get_int('id'));
                die('1');
        }
}

?>
