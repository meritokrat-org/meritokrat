<?

load::app('modules/ideas/controller');
class ideas_edit_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->selected_menu = '/ideas';

		load::model('ideas/ideas');

		if ( request::get_int('id') )
		{
			$this->idea_data = ideas_peer::instance()->get_item( request::get_int('id') );
			if ( ( $this->idea_data['user_id'] != session::get_user_id() ) && !session::has_credential('moderator')  && !session::has_credential('admin') )
			{
				$this->redirect('/home-' . session::get_user_id());
			}
		}

		if ( request::get('submit') && trim(request::get('text')) && trim(request::get('title')) )
		{
			$tags = ideas_peer::instance()->string_to_array(request::get('tags'));
			$clean_text = ideas_peer::instance()->clean_text( request::get('text') );
                        load::action_helper('text', true);
                    
			$data = array(
				'title' => mb_substr(trim(request::get('title')), 0, 256),
				'title_ru' => mb_substr(trim(request::get('title_ru')), 0, 256),
				'text' => request::get('text'),
				'text_ru' => request::get('text_ru'),
                                'anounces'=>trim(request::get_string('anounces')),
                                'anounces_ru'=>trim(request::get_string('anounces_ru')),
//				'tags_text' => implode(', ', $tags),
				'segment' => request::get_int('segment')
			);
                                $idea_id = $data['id'] = $this->idea_data['id'];
				ideas_peer::instance()->update( $data );
			/*foreach ( $tags as $tag )
			{
				$tag = mb_substr($tag, 0, 48);

				$tag_id = ideas_tags_peer::instance()->obtain_id($tag);
				if (!in_array($idea_id,ideas_tags_peer::instance()->get_by_tag( $tag_id )))
                                        {
                                            ideas_to_tags_peer::instance()->insert(array(
                                                    'idea_id' => $idea_id,
                                                    'tag_id' => $tag_id
                                            ));
                                        }
			}*/


			//$this->set_renderer('ajax');
                        //$this->json = array();
                        $this->redirect('/idea' . $idea_id);
		}
	}
}