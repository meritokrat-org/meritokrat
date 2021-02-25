<?

abstract class messages_controller extends frontend_controller
{
	protected $authorized_access = true;

	public function init()
	{
		parent::init();
		load::model('messages/messages');
	}
}
