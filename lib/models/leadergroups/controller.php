<?

abstract class leadergroups_controller extends frontend_controller
{
	public function init()
	{
		parent::init();

		load::model('leadergroups/leadergroups');
		load::model('leadergroups/members');
		load::model('leadergroups/news');
		load::model('leadergroups/topics');

		load::view_helper('leadergroup');

		load::action_helper('pager', true);
		$this->set_slot('context', 'partials/about');
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/leadergroups';
	}
}
