<?
class help_info_action extends frontend_controller
{
        protected $authorized_access = true;
	public function execute()
	{
                $this->disable_layout();

                load::model('help/info');
                $this->info = help_info_peer::instance()->get_info(request::get_string('alias'));
	}
}