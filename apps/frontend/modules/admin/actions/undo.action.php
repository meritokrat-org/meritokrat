<?

load::app('modules/admin/controller');

class admin_undo_action extends admin_controller
{
	public function execute()
	{
		$id = request::get_int('id');
		if (id) {
			load::model('admin_feed');
			$feed = admin_feed_peer::instance()->get_item($id);
			admin_feed_peer::instance()->delete_item($id);

			$data = unserialize($feed['data']);

			switch ($feed['type']) {
				case 1:
					load::model('blogs/comments');
					if ($feed['action'])
						blogs_comments_peer::instance()->delete_item($data['id']);
					blogs_comments_peer::instance()->insert($data);
					break;

				case 2:
					load::model('blogs/posts');
					load::model('blogs/posts_tags');
					if ($feed['action']) {
						unset($data['for'], $data['against'], $data['views']);
						blogs_posts_peer::instance()->update($data);
					} else {
						blogs_posts_peer::instance()->insert($data);
					}
					break;

				case 5:
					load::model('polls/comments');
					if ($feed['action'])
						polls_comments_peer::instance()->delete_item($data['id']);
					polls_comments_peer::instance()->insert($data);
					break;

				case 6:
					load::model('ideas/comments');
					if ($feed['action'])
						ideas_comments_peer::instance()->delete_item($data['id']);
					ideas_comments_peer::instance()->insert($data);
					break;

				case 7:
					load::model('events/comments');
					if ($feed['action'])
						events_comments_peer::instance()->delete_item($data['id']);
					events_comments_peer::instance()->insert($data);
					break;

				case 8:
					load::model('polls/polls');
					if ($feed['action'])
						polls_peer::instance()->delete_item($data['id']);
					polls_peer::instance()->insert($data);
					mem_cache::i()->delete('polls_newest');
					break;
			}
		}
		$this->redirect('/admin/mfeed');
	}
}
