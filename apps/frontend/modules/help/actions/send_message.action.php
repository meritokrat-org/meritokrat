<?
class help_send_message_action extends frontend_controller
{
	protected $authorized_access = true;
        
	public function execute()
	{
            load::view_helper('status');
            load::model('help/info');
            
            $status = request::get_int('ustat');

            if($status>=status_helper::MPU_MEMBER) $this->status=status_helper::MPU_MEMBER;
            elseif($status>=status_helper::MERITOCRAT) $this->status=status_helper::MERITOCRAT;
            else $this->status=0;
            
            if(!request::get('message'))
            {
                load::action_helper('page',false);
                $this->disable_layout();
                $this->users = array();
                $this->uid = request::get_int('uid');
            }
            else
            {
                $this->set_renderer('ajax');
                load::model('messages/messages');
                
                $this->json = array();
                
                $rec = request::get('uid');
                $sender_id=session::get_user_id();
                $message = trim(request::get_string('message'));
                $status = request::get('ustat');
                $status= (is_array($status)) ? $status[0] : $status;
                
                if ( count($rec)>0 && $message != '' && $status )
                {
                        if(!db_key::i()->exists(session::get_user_id()."_ask_".$status."_recommendation_".$rec)) { 
                            db_key::i()->set(session::get_user_id()."_ask_".$status."_recommendation_".$rec,'1');
                            //$keyArr = array(session::get_user_id()."_ask_".$status[0]."_recommendation_".$rec=>1);
                        }

                        $id = messages_peer::instance()->add(array(
                                                                        'sender_id' => $sender_id,
                                                                        'receiver_id' => $rec,
                                                                        'body' => trim($message).'<br/>Для того щоб рекомендувати цю людину перейдіть за посиланням https://meritokrat.org/profile-'.session::get_user_id().' і натисніть "Рекомендувати" ( під фото )'
                                                                    ),true,0);
                        //if($id) $this->json=array_merge (array('success'=>true),$keyArr);
                }
            }
        }
}