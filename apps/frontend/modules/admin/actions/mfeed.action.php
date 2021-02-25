<?

load::app('modules/admin/controller');
class admin_mfeed_action extends admin_controller
{
	public function execute()
	{
		$this->page = max((int)$_GET['page'], 1);

		load::model('admin_feed');
		$this->feed = admin_feed_peer::instance()->get($this->page);
                
		$this->types = array(
			admin_feed_peer::TYPE_BLOG_COMMENT => t('Комментарий к мысли'),
			admin_feed_peer::TYPE_BLOG_POST => t('Мысль'),
			admin_feed_peer::TYPE_DEBATE_COMMENT => t('Комментарий к дебатам'),
			admin_feed_peer::TYPE_DEBATE => t('Дебаты'),
                        admin_feed_peer::TYPE_POLL_COMMENT => t('Комментарий к опросу'),
                        admin_feed_peer::TYPE_IDEA_COMMENT => t('Комментарий к идее'),
                        admin_feed_peer::TYPE_EVENT_COMMENT => t('Комментарий к событию'),
                        admin_feed_peer::TYPE_POLL => t('Опрос'),
		);
	}
}
