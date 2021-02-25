<?

load::app('modules/ppo/controller');
class ppo_newsread_action extends ppo_controller
{
	public function execute()
	{
                
                $this->item = ppo_news_peer::instance()->get_item(request::get_int('id'));
		if ( !$this->item ) $this->redirect('/');
                //просмотров=кол-во юзеров открывших пост
                if (!db_key::i()->exists('pponews_viewed:'.$this->item['id'].':'.session::get_user_id()))
		{       
			ppo_news_peer::instance()->update(array('views' => $this->item['views']+1, 'id' => $this->item['id']));
                        db_key::i()->set('pponews_viewed:'.$this->item['id'].':'.session::get_user_id(), true);                           
		}
            
		$this->group = ppo_peer::instance()->get_item( $this->item['group_id'] );
                
	}
}