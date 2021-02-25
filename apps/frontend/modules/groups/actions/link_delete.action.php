<?

load::app('modules/groups/controller');
class groups_link_delete_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('groups/links');
		if ( !$this->link = groups_links_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Файл не найден');
		}

		$this->group = groups_peer::instance()->get_item($this->link['group_id']);

		if ( ($this->link['user_id'] == session::get_user_id()) || groups_peer::instance()->is_moderator($this->link['group_id'], session::get_user_id())  )
		{
			groups_links_peer::instance()->delete_item($this->link['id']);
		}

		$this->redirect('/groups/file?id=' . $this->group['id'] . '&dir_id=' . $this->link['dir_id']);
	}
}
