<?

abstract class groups_controller extends frontend_controller
{
	#protected $authorized_access = true;
	public function init()
	{
		parent::init();

		load::model('groups/groups');
		load::model('groups/members');
		load::model('groups/news');
		load::model('groups/topics');
		load::model('groups/topics_messages');
		load::model('groups/positions');
		load::model('groups/positions_messages');
		load::model('groups/proposal');
		load::model('groups/proposal_messages');

		load::view_helper('group');

		load::action_helper('pager', true);
		$this->set_slot('context', 'partials/about');

		client_helper::set_title( t('Сообщества') . ' | ' . conf::get('project_name') );
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/groups';
	}
}
