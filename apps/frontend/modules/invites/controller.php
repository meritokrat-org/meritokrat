<?

abstract class invites_controller extends frontend_controller
{
	protected $authorized_access = true;
	public function init()
	{
		parent::init();
                load::action_helper('pager', true);
		load::model('invites/invites');
	}
}
