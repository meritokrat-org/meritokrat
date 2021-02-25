<?

load::app('modules/events/controller');
class events_delete_event_action extends events_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                $this->event = events_peer::instance()->get_item(request::get_int('id'));
                $allow = 0;
                 switch ($this->event['type']) {
            case 1:
            case 3:
                if (session::has_credential('admin') || groups_peer::instance()->is_moderator($this->event['content_id'], session::get_user_id()))
                    $allow = 1;
                break;

            case 2;
                /* TODO когда появится лидеская группа сделать проверку */
                break;
            case 0:
                if (session::has_credential('admin') || $this->event['user_id'] == session::get_user_id())
                    $allow = 1;
                }
		if ( $allow ==0 )
		{
			$this->redirect('/');
		}else{
                load::model('events/comments');
                events_peer::instance()->delete_item(request::get_int('event_id'));
                events_members_peer::instance()->delete_event(request::get_int('event_id'));
                events_comments_peer::instance()->delete_event(request::get_int('event_id'));
                if(db_key::i()->exists('group_mailer_id:'.request::get_int('event_id')))
                    db_key::i()->delete('group_mailer_id:'.request::get_int('event_id'));
                }
                $this->redirect('/events/');
        }
}