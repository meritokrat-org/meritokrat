<?

abstract class events_controller extends frontend_controller
{
	protected $authorized_access = true;
	public function init()
	{
		parent::init();

		load::model('events/events');
                load::model('events/members');

		load::action_helper('pager', true);

		client_helper::set_title( t('События') . ' | ' . conf::get('project_name') );
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/events';
	}
}
