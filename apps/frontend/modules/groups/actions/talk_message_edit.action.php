<?
load::app('modules/groups/controller');
class groups_talk_message_edit_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
            $this->id=request::get_int('id');
            load::model('groups/topics_messages');
            $this->message=groups_topics_messages_peer::instance()->get_item($this->id);
            load::model('groups/topics');
            $this->topic=groups_topics_peer::instance()->get_item($this->message['topic_id']);
            if (groups_peer::instance()->is_moderator($this->id, session::get_user_id()) || $this->message['user_id']==session::get_user_id())
                {
                        if ($this->message)
                            {
                                if ($title=trim(request::get('topic')))
                                {
                                           groups_topics_peer::instance()->update(array(
                                                    'id' => $this->message['topic_id'],
                                                    'topic'=>$title
                                            ));
                                }
                                if ($text=trim(request::get('text')))
                                {
                                           groups_topics_messages_peer::instance()->update(array(
                                                    'id' => $this->id,
                                                    'text'=>$text
                                            ));
                                    $this->redirect('/groups/talk_topic?id=' . $this->message['topic_id']);
                                }
                        }
                }
           else $this->redirect('/groups/talk_topic?id=' . $this->message['topic_id']);
	}
}