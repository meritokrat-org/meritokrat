<?
class profile_signatures_add_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if (!session::has_credential('admin') )
		{
			throw new public_exception('немає повноважень');
                    
                }
                if (request::get_int('user_id') && request::get_int('region_id'))
                {
                    load::model('user/user_desktop_signature_fact');                
                    user_desktop_signature_fact_peer::instance()->insert(
                            array(
                            'user_id'=>request::get_int('user_id'),
                            'region_id'=>request::get_int('region_id'),
                            'city_id'=>request::get_int('city_id'),
                            'fact'=>request::get_int('fact'),
                            'admin_id'=>session::get_user_id(),
                            ));               
                }
                
               $this->redirect('/profile/desktop?tab=tasks&id='.request::get_int('user_id'));
        }
}