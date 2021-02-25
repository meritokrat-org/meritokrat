<?
load::app('modules/polls/controller');
class polls_delete_poll_action extends polls_controller
{
	protected $authorized_access = true;
	protected $credentials = array('moderator');

	public function execute()
	{
                $poll = polls_peer::instance()->get_item(request::get_int('id'));
		polls_peer::instance()->delete_item($poll['id']);
                mem_cache::i()->delete( 'polls_newest' );
                
                if ( session::has_credential('moderator') )
                {
                        load::model('admin_feed');
                        admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_POLL, $text, $poll, request::get_string('why'));
                }

		$this->redirect('/polls');
	}
}