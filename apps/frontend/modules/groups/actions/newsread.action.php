<?

load::app('modules/groups/controller');
class groups_newsread_action extends groups_controller
{
	public function execute()
	{
                
                $this->item = groups_news_peer::instance()->get_item(request::get_int('id'));
		if ( !$this->item ) $this->redirect('/');
                //просмотров=кол-во юзеров открывших пост
                if (!db_key::i()->exists('groupnews_viewed:'.$this->item['id'].':'.session::get_user_id()))
		{       
			groups_news_peer::instance()->update(array('views' => $this->item['views']+1, 'id' => $this->item['id']));
                        db_key::i()->set('groupnews_viewed:'.$this->item['id'].':'.session::get_user_id(), true);                           
		}
            
		$this->group = groups_peer::instance()->get_item( $this->item['group_id'] );
                
	}
}