<?
class photocompetition_comment_action extends frontend_controller
{
	public function execute()
	{
		$this->disable_layout();

		load::model('photo/photo_competition_comments');
		load::model('photo/photo_competition');

		if ( $text = trim(request::get('text')) )
		{
			load::action_helper('text', true);
			$text = text_helper::smart_trim($text, 4048);

			if ( !$photo = photo_competition_peer::instance()->get_item(request::get_int('photo_id')) )
			{
				return;
			}

			$data = array(
				'user_id' => session::get_user_id(),
				'photo_id' => $photo['id'],
				'text' => $text,
				'ts' => time(),
				'parent_id' => request::get_int('parent_id')
			);

			$this->id = photo_competition_comments_peer::instance()->insert($data);

			if ( $parent_id = request::get_int('parent_id') )
			{
				$this->child_id = $this->id;

				$comment = photo_competition_comments_peer::instance()->get_item($parent_id);
				$comment['childs'] .= $this->id . ',';
				photo_competition_comments_peer::instance()->update(array(
					'id' => $parent_id,
					'childs' => $comment['childs']
				));
			}
		}

		load::model('user/user_data');
		load::view_helper('user');
	}
}