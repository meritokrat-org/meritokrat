<?

load::app('modules/ideas/controller');
load::model('ideas/ideas');
class ideas_create_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->user_data = user_data_peer::instance()->get_item( session::get_user_id() );
		$this->allow_create = ( $this->user_data['rate'] >= 5 ) || session::has_credential('editor');

		if ( request::get('submit') && trim(request::get('text')) && trim(request::get('title')) )
		{

			//$tags = ideas_peer::instance()->string_to_array(strtolower(request::get('tags')));
			//$clean_text = ideas_peer::instance()->clean_text( request::get('text') );
			$data = array(
				'created_ts' => time(),
				'user_id' => 31,
				'text' => request::get('text'),
                                'anounces'=>trim(request::get_string('anounces')),
//				'tags_text' => implode(', ', $tags),
				'title' => trim(request::get('title')),
				'text_ru' => request::get('text_ru'),
                                'anounces_ru'=>trim(request::get_string('anounces_ru')),
				'title_ru' => trim(request::get('title_ru')),
				'segment' => request::get_int('segment')
			);
			
			$idea_id = ideas_peer::instance()->insert($data);
/*
			foreach ( $tags as $tag )
			{
				$tag = mb_substr($tag, 0, 48);

				$tag_id = ideas_tags_peer::instance()->obtain_id($tag);
				ideas_to_tags_peer::instance()->insert(array(
					'idea_id' => $idea_id,
					'tag_id' => $tag_id
				));
			}
			load::model('feed/feed');
			load::view_helper('tag', true);

			ob_start();
			include dirname(__FILE__) . '/../../feed/views/partials/items/idea.php';
			$feed_html = ob_get_clean();

			$readers = friends_peer::instance()->get_by_user(session::get_user_id());
			feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_IDEA,
				'section' => feed_peer::SECTION_PERSONAL,
			));
*/
			//$this->set_renderer('ajax');
			//$this->json = array();
                        $this->redirect('/idea' . $idea_id);
		}
	}
}