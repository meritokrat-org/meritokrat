<?

class debates_vote_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('debates/debates');
		load::model('debates/arguments');
		$this->disable_layout();

		if ( $data = debates_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ( trim(request::get('text')) )
			{
				if ( !debates_peer::instance()->has_voted($data['id'], session::get_user_id()) && !request::get_int('parent_id') )
				{
					debates_peer::instance()->update(array(
						'id' => $data['id'],
						'for' => $data['for'] + ( request::get('agree') == 'y' ? 1 : 0 ),
						'against' => $data['against'] + ( request::get('agree') == 'n' ? 1 : 0 )
					));
				}
				
				load::action_helper('text', true);
				$text = text_helper::smart_trim(trim(request::get('text')), 4048);

				if ( request::get_int('parent_id') )
				{
					$parent = debates_arguments_peer::instance()->get_item( request::get_int('parent_id') );

					$data = array(
						'text' => $text,
						'user_id' => session::get_user_id(),
						'parent_id' => request::get_int('parent_id'),
						'debate_id' => $parent['debate_id'],
						'agree' => ( request::get('agree') == 'y' ),
						'created_ts' => time(),
					);
					
					$this->child_id = debates_arguments_peer::instance()->insert($data);
					debates_arguments_peer::instance()->rate($this->child_id, session::get_user_id());

					debates_arguments_peer::instance()->update(array(
						'id' => $parent['id'],
						'childs' => $parent['childs'] . $this->child_id . ','
					));
				}
				else if ( !debates_peer::instance()->has_voted($data['id'], session::get_user_id()) )
				{
					$data = array(
						'text' => $text,
						'user_id' => session::get_user_id(),
						'debate_id' => $data['id'],
						'agree' => ( request::get('agree') == 'y' ),
						'created_ts' => time(),
					);

					$this->id = debates_arguments_peer::instance()->insert($data);
					debates_arguments_peer::instance()->rate($this->id, session::get_user_id());

					debates_peer::instance()->vote($data['debate_id'], session::get_user_id());
				}
			}
		}

		load::model('user/user_data');
		load::view_helper('user');
	}
}
