<?php
load::app('modules/messages/controller');
load::model('messages/messages');
load::model('ppo/ppo');
load::model('groups/groups');
load::model('groups/members');

class messages_groups_mailer_action extends messages_controller
{
	protected $authorized_access = true;

	public function execute()
	{
            $this->disable_layout();
            load::model('groups/groups');
						
            $this->event_id = request::get_int('event_id');

            load::model('events/events');
            $this->event = events_peer::instance()->get_item($this->event_id);
            
            switch($this->event['type']){
							case 1:
								$this->type = t('Группе');
								$group = groups_peer::instance()->get_item($this->event['content_id']);
								if(request::get_bool('submit'))
									$this->recipients = groups_members_peer::instance()->get_members($this->event['content_id']);
								$this->name = $group['title'];
								break;
							
							case 4:
								$this->type = t('Членам');
								$ppo = ppo_peer::instance()->get_item($this->event['content_id']);
								if(request::get_bool('submit'))
									$this->recipients = ppo_members_peer::instance()->get_members($this->event['content_id']);
								$this->name = $ppo['title'];
								break;
						}
						
						if(request::get_bool('submit'))
							$this->submit();
						
        }
				
				public function submit(){
					$this->disable_layout();
					$this->set_renderer('ajax');
					$this->json = array();
					
					//var_dump($this->recipients);
					
					foreach($this->recipients as $user_id){
						messages_peer::instance()->add(
							array('sender_id' => session::get_user_id(),
								'receiver_id' => $user_id,
								'body' => request::get('body')
							),
							true,
							0
						);
					}
					
				}

}

?>
