<?

class friends_news_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{   
        load::model('friends/news');
        $this->user_id = session::get_user_id();
        $this->friends=friends_news_peer::instance()->get_by_user($this->user_id); 
        load::action_helper('pager');
        $this->pager = pager_helper::get_pager($this->friends, request::get_int('page'), 10);
        $this->friends_news = $this->pager->get_list();
            $this->set_template('index');
	}
}