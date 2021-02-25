<?

load::app('modules/debates/controller');
class debates_create_action extends debates_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->user_data = user_data_peer::instance()->get_item( session::get_user_id() );
		$this->allow_create = ( $this->user_data['rate'] >= 35 ) || session::has_credential('editor');

		if ( $this->allow_create && request::get('submit') && trim(request::get('text')) )
		{
			$tags = debates_tags_peer::instance()->string_to_array(request::get('tags'));

			$data = array(
				'created_ts' => time(),
				'user_id' => session::get_user_id(),
				'text' => text_helper::smart_trim(trim(request::get('text')), 1024),
				'tags_text' => implode(', ', $tags)
			);
			
			$debate_id = debates_peer::instance()->insert($data);

			load::model('feed/feed');
			load::view_helper('tag', true);

			ob_start();
			include dirname(__FILE__) . '/../../feed/views/partials/items/debate.php';
			$feed_html = ob_get_clean();

			$readers = friends_peer::instance()->get_by_user(session::get_user_id());
			feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_DEBATE,
				'section' => feed_peer::SECTION_PERSONAL,
			));

			foreach ( $tags as $tag )
			{
				$tag_id = debates_tags_peer::instance()->obtain_id($tag);
				debates_debates_tags_peer::instance()->insert(array(
					'debate_id' => $debate_id,
					'tag_id' => $tag_id
				));
			}

			$this->set_renderer('ajax');
			$this->json = array();
		}
	}
}