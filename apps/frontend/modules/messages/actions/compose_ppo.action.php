<?

load::app('modules/messages/controller');
class messages_compose_ppo_action extends messages_controller
{
	public function execute()
	{
            
            $count_messages=db::get_scalar('SELECT count(*) FROM messages WHERE created_ts> '.(time()-24*60*60).' and sender_id='.session::get_user_id().' and owner!='.session::get_user_id());
            $uauth=user_auth_peer::instance()->get_item(session::get_user_id());

                $group_id = request::get_int('ppo');
                load::model('ppo/ppo');
                load::model('ppo/members');
                $group = ppo_peer::instance()->get_item($group_id);
                if(!ppo_members_peer::instance()->allow_edit(session::get_user_id(),$group) && !session::has_credential('admin'))$this->redirect ('/');
                $this->group_title=$group['title'];
            
            if ( request::get('submit') )
            {
                //чистим от мусора
                load::model('blogs/posts');
                $clean_body = blogs_posts_peer::instance()->clean_text(stripslashes(trim(request::get('body'))));

                $this->sendppo(request::get_int('ppo'));
                
                $this->set_renderer('ajax');
                $this->json = array();
            }
	}
        private function sendppo($group_id)
        {
            load::model('internal_mailing');

            $sender_id=session::get_user_id();

            $insert_data = array(
                                    'sender_id'=>$sender_id,
                                    'filters'=>'ppo:'.$group_id,
                                    'body'=>  trim(request::get('body')),
                                    'active'=>0,
                                    'count'=>0,
                                    'sended'=>0
                                );
            $id = internal_mailing_peer::instance()->insert($insert_data);
        }
}