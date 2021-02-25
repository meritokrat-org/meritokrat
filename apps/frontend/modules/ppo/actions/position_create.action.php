<?

load::app('modules/ppo/controller');
class ppo_position_create_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->group = ppo_peer::instance()->get_item(request::get_int('id'));

		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		if ( ( $topic = trim(request::get('topic')) ) && ( $text = trim(request::get('text')) ) )
		{
			$id = ppo_positions_peer::instance()->insert(array(
				'group_id' => $this->group['id'],
				'topic' => $topic,
				'created_ts' => time(),
				'messages_count' => 1,
				'last_user_id' => session::get_user_id(),
				'updated_ts' => time()
			));

			ppo_positions_messages_peer::instance()->insert(array(
				'topic_id' => $id,
				'user_id' => session::get_user_id(),
				'created_ts' => time(),
				'text' => $text
			));

			load::model('feed/feed');
			load::view_helper('tag', true);
			load::view_helper('group');

			$group = $this->group;
			ob_start();
			include dirname(__FILE__) . '/../../feed/views/partials/items/group_forum_post.php';
			$feed_html = ob_get_clean();

			load::model('ppo/members');
			$readers = ppo_members_peer::instance()->get_members($this->group['id'],false,$this->group);
			$readers = array_diff($readers, array(session::get_user_id()));
			feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_GROUP_FORUM_POST,
				'section' => feed_peer::SECTION_PERSONAL,
			));
		}

		$this->redirect('/ppo/position_topic?id='.$id);
	}
}