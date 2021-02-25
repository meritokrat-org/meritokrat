<?php
class ajax_eventaddmember_action extends frontend_controller
{
        public function execute()
        {
                load::model('geo');
                load::model('events/members');

                $this->disable_layout();
                $this->set_template('getuser');

                $user_id = request::get_int('id');
                $event_id = request::get_int('event');
                $status = request::get_int('status');
								$confirm = request::get_int('confirm');
								/*if($confirm == 0){
									$confirm = $status;
								}*/
								

                if(!$user_id || !$event_id || !$status)die();
								
                if(!events_members_peer::instance()->is_member($event_id, $user_id))
                {
                    events_members_peer::instance()->insert(array(
                        'user_id'=>$user_id,
                        'event_id'=>$event_id,
                        'status'=>$status,
                        'leads'=>0,
												'confirm'=>$confirm,
                    ));
										
                    load::model('events/events');
                    load::model('user/user_desktop_meeting');
                    $event = events_peer::instance()->get_item($event_id);
										
                    user_desktop_meeting_peer::instance()->insert(array(
                        'user_id' => $user_id,
                        'title' => $event['name'],
                        'description' => $event['description'],
                        'meeting_date' => date("d/m/Y",$event['start']),
                        'part' => 1,
                        'confirm' => $confirm,
                        'event_id' => $event['id']
                    ));

                }

                $this->user['user_id'] = $user_id;
                $this->user_list_data=user_data_peer::instance()->get_item($user_id);
                $this->user_list_auth=user_auth_peer::instance()->get_item($user_id);
        }
}