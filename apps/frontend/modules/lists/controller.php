<?

abstract class lists_controller extends frontend_controller
{
	protected $authorized_access = true;

	public function init()
	{
		parent::init();
		load::model('lists/lists');
                load::model('lists/lists_users');
	}
}
