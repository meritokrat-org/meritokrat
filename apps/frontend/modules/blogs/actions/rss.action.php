<?

load::app('modules/blogs/controller');
class blogs_rss_action extends blogs_controller
{
	public function execute()
	{
		$this->set_renderer('rss');

		$channel = t('Meritokrat.org');

		if ( $this->tag = trim(request::get('tag')) )
		{
			$tag_id = blogs_tags_peer::instance()->get_by_name( $this->tag );
			$this->list = blogs_posts_peer::instance()->get_by_tag($tag_id);
			$channel = $this->tag . ' - ' . $channel;
		}
		else if ( $this->user_id = request::get_int('user') )
		{
			$this->list = blogs_posts_peer::instance()->get_by_user($this->user_id);
			$channel = user_helper::full_name($this->user_id, false) . ' - ' . $channel;
		}
		else if ( request::get('type') == 'news' )
		{
			$this->list = blogs_posts_peer::instance()->get_newest( blogs_posts_peer::TYPE_NEWS_POST );
			$channel = t('Новости');
		}
		else if ( request::get('type') == 'favorites' )
		{
			$this->list = blogs_posts_peer::instance()->get_favorites();
			$channel = t('Избранное');
		}
		else if ( request::get('type') == 'comments' )
		{
			$this->list = blogs_comments_peer::instance()->get_list(array(), array(), array('id DESC'), 15);
			$channel = t('Свежие комментарии');
		}
		else
		{
			$this->list = blogs_posts_peer::instance()->get_casted();
			$channel = t('Meritokrat.org');
		}

		$this->list = array_slice($this->list, 0, 15);
		$this->rss['channel'] = array(
			'url' => 'http://' . context::get('host') . '/blogs/rss',
			'title' => $channel,
			'description' => $channel
		);

		$this->rss['items'] = array();

		foreach ( $this->list as $id )
		{
			if ( request::get('type') == 'comments' )
			{
				$comment = blogs_comments_peer::instance()->get_item($id);
				$post = blogs_posts_peer::instance()->get_item($comment['post_id']);

				$this->rss['items'][] = array(
					'url' => 'http://' . context::get('host') . '/blogpost' . $post['id'],
					'title' => $post['title'],
					'text' => $comment['text'],
					'date' => date('r', $comment['created_ts'])
				);
			}
			else
			{
				$post = blogs_posts_peer::instance()->get_item($id);
				$post['body'] = strip_tags($post['body']);

				$this->rss['items'][] = array(
					'url' => 'http://' . context::get('host') . '/blogpost' . $id,
					'title' => stripslashes(htmlspecialchars($post['title'])),
					'text' => stripslashes(text_helper::smart_trim( $post['body'], 512 )),
					'date' => date('r', $post['created_ts'])
				);
			}
		}
	}
}
