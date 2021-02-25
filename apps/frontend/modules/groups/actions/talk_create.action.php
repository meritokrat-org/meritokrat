<?

load::app('modules/groups/controller');
class groups_talk_create_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->group = groups_peer::instance()->get_item(request::get_int('id'));

		if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id())  &&  !groups_peer::instance()->is_moderator($this->group['id'], session::get_user_id()))
		{
			$this->redirect('/group' . $this->group['id']);
		}

		if ( ( $topic = trim(request::get('topic')) ) && ( $text = trim(request::get('text')) ) )
		{
			$id = groups_topics_peer::instance()->insert(array(
				'group_id' => $this->group['id'],
				'topic' => $topic,
				'created_ts' => time(),
				'messages_count' => 1,
				'last_user_id' => session::get_user_id(),
				'updated_ts' => time()
			));

			groups_topics_messages_peer::instance()->insert(array(
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

			load::model('groups/members');
			$readers = groups_members_peer::instance()->get_members($this->group['id']);
			$readers = array_diff($readers, array(session::get_user_id()));
			feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_GROUP_FORUM_POST,
				'section' => feed_peer::SECTION_PERSONAL,
			));
		}

		$this->redirect('/groups/talk_topic?id='.$id);
	}
}