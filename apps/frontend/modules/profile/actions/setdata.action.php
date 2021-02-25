<?

class profile_setdata_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{

            $this->set_renderer('ajax');
            $this->json = array();

            if(request::get_string('data') && request::get_string('key'))
            {
                db_key::i()->set(request::get_string('key').session::get_user_id(), request::get_string('data'));
            }
            elseif(request::get_string('key'))
            {
                db_key::i()->delete(request::get_string('key').session::get_user_id());
            }

        }

}
