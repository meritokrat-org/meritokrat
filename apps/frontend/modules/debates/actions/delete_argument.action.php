<?

load::app('modules/debates/controller');
class debates_delete_argument_action extends debates_controller
{
	protected $authorized_access = true;
	protected $credentials = array('moderator');

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( request::get_int('id') )
		{
			$argument = debates_arguments_peer::instance()->get_item(request::get_int('id'));

			load::model('admin_feed');
			$text = htmlspecialchars($argument['text']) . '<br /><br /> Автор: ' .
					user_helper::full_name($argument['user_id']) . '<br /><br />' .
					'<a href="/debate' . $argument['debate_id'] . '">Перейти к дебатам' . '</a>';

			admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_DEBATE_COMMENT, $text);

			debates_arguments_peer::instance()->delete_item( request::get_int('id') );
		}
	}
}