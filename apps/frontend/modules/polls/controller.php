<?

abstract class polls_controller extends frontend_controller
{
	#protected $authorized_access = true;
	public function init()
	{
		parent::init();

		load::model('polls/polls');
		load::model('polls/answers');
		load::model('polls/votes');
		load::model('polls/custom');

		load::action_helper('pager', true);

		client_helper::set_title( t('Опросы') . ' | ' . conf::get('project_name') );
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/polls';
		$list = polls_peer::instance()->get_promoted();
		$this->promoted = array_slice($list, 0, 5);
	}
}
