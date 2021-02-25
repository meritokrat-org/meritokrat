<?
class profile_signatures_del_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if (!session::has_credential('admin') )
		{
			throw new public_exception('немає повноважень');
                    
                }
                if (request::get_int('id'))
                {                
                        load::model('user/user_desktop_signature_fact');                
                        user_desktop_signature_fact_peer::instance()->delete_item(request::get_int('id'));
                }
                die('1');
        }
}