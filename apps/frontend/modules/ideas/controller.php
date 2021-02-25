<?

abstract class ideas_controller extends frontend_controller
{
	#protected $authorized_access = true;
	public function init()
	{
		parent::init();
		
		load::model('ideas/ideas');
		load::model('ideas/comments');
		load::model('ideas/ideas_tags');
		load::model('ideas/tags');

		load::action_helper('pager', true);

		client_helper::set_title( t('Идеи') . ' | ' . conf::get('project_name') );
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/ideas';

		$this->segments = ideas_peer::instance()->get_segments_cloud();

		$this->discussed = ideas_peer::instance()->get_discussed( 10 );

		load::model('user/user_data');
		load::view_helper('user');
	}
}
