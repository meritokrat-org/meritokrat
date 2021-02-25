<?

load::app('modules/debates/controller');
class debates_hide_action extends debates_controller
{
	protected $authorized_access = true;
	protected $credentials = array('moderator');

	public function execute()
	{
		if ( request::get_int('id') )
		{
			$debate = debates_peer::instance()->get_item( request::get_int('id') );

			debates_peer::instance()->update(array(
				'id' => request::get_int('id'),
				'visible' => false
			));

			load::model('admin_feed');
			$text = htmlspecialchars($debate['text']) . '<br /><br /> Автор: ' .
					user_helper::full_name($debate['user_id']);

			admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_DEBATE, $text);

			$this->redirect('/debates');
		}
	}
}