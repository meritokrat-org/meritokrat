<?
load::app('modules/ppo/controller');
class ppo_proposal_message_edit_action extends ppo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
            $this->id=request::get_int('id');
            load::model('ppo/proposal_messages');
            $this->message=ppo_proposal_messages_peer::instance()->get_item($this->id);
            if (ppo_peer::instance()->is_moderator($this->id, session::get_user_id()) || $this->message['user_id']==session::get_user_id())
                {
                        if ($this->message)
                            {
                                if ($text=trim(request::get('text')))
                                {
                                           ppo_proposal_messages_peer::instance()->update(array(
                                                    'id' => $this->id,
                                                    'text'=>$text
                                            ));
                                    $this->redirect('/ppo/proposal_topic?id=' . $this->message['topic_id']);
                                }
                        }
                }
           else $this->redirect('/ppo/proposal_topic?id=' . $this->message['topic_id']);
	}
}