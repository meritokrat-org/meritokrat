<?

abstract class groups_controller extends frontend_controller
{
	public function init()
	{
		parent::init();

		load::model('groups/groups');
		load::model('groups/members');
		load::model('groups/news');
		load::model('groups/topics');

		load::view_helper('group');

		load::action_helper('pager', true);
		$this->set_slot('context', 'partials/about');
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/groups';
	}
}
