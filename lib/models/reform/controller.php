<?

abstract class ppo_controller extends frontend_controller
{
	public function init()
	{
		parent::init();

		load::model('ppo/ppo');
		load::model('ppo/members');
		load::model('ppo/news');
		load::model('ppo/topics');

		load::view_helper('group');

		load::action_helper('pager', true);
		$this->set_slot('context', 'partials/about');
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/ppo';
	}
}
