<?

load::app('modules/reform/controller');

class reform_newsread_action extends reform_controller
{
	public function execute()
	{

		$this->item = reform_news_peer::instance()->get_item(request::get_int('id'));
		if (!$this->item) $this->redirect('/');
		//просмотров=кол-во юзеров открывших пост
		if ( ! db_key::i()->exists('projectsnews_viewed:' . $this->item['id'] . ':' . session::get_user_id())) {
			reform_news_peer::instance()->update(array('views' => $this->item['views'] + 1, 'id' => $this->item['id']));
			db_key::i()->set('projectsnews_viewed:' . $this->item['id'] . ':' . session::get_user_id(), true);
		}

		$this->group = reform_peer::instance()->get_item($this->item['group_id']);

	}
}