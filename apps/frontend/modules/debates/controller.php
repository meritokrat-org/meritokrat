<?

abstract class debates_controller extends frontend_controller
{
	protected $authorized_access = true;
	public function init()
	{
		parent::init();

		load::model('debates/debates');
		load::model('debates/arguments');
		load::model('debates/tags');
		load::model('debates/debates_tags');

		load::action_helper('pager', true);

		client_helper::set_title( t('Дебаты') . ' | ' . conf::get('project_name') );
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/debates';
		$this->newest_arguments = debates_arguments_peer::instance()->get_newest();
		$this->top_tags = debates_tags_peer::instance()->get_top();

		load::model('user/user_data');
		load::view_helper('user');
	}
}
