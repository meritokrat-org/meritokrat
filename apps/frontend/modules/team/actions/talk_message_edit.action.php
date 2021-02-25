<?
load::app('modules/ppo/controller');
class ppo_talk_message_edit_action extends ppo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
            $this->id=request::get_int('id');
            load::model('ppo/topics_messages');
            $this->message=ppo_topics_messages_peer::instance()->get_item($this->id);
            load::model('ppo/topics');
            $this->topic=ppo_topics_peer::instance()->get_item($this->message['topic_id']);
            if (ppo_peer::instance()->is_moderator($this->id, session::get_user_id()) || $this->message['user_id']==session::get_user_id())
                {
                        if ($this->message)
                            {
                                if ($title=trim(request::get('topic')))
                                {
                                           ppo_topics_peer::instance()->update(array(
                                                    'id' => $this->message['topic_id'],
                                                    'topic'=>$title
                                            ));
                                }
                                if ($text=trim(request::get('text')))
                                {
                                           ppo_topics_messages_peer::instance()->update(array(
                                                    'id' => $this->id,
                                                    'text'=>$text
                                            ));
                                    $this->redirect('/ppo/talk_topic?id=' . $this->message['topic_id']);
                                }
                        }
                }
           else $this->redirect('/ppo/talk_topic?id=' . $this->message['topic_id']);
	}
}