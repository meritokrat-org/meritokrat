<?
class photocompetition_change_limit_action extends frontend_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');
                session::is_authenticated() ? $user=session::get_user_id() : $user=$_SERVER["REMOTE_ADDR"];
                $value = request::get_int('value');
                db_key::i()->set('photocompetition_'.$user.'_limit',$value);
	}
}