<?

load::app('modules/groups/controller');
class groups_linkdir_delete_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('groups/links_dirs');
		load::model('groups/links');
		if ( !$this->linkdir = groups_links_dirs_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Папка не найдена');
		}

		$this->group = groups_peer::instance()->get_item($this->linkdir['group_id']);

		if ( ($this->linkdir['user_id'] == session::get_user_id()) || groups_peer::instance()->is_moderator($this->linkdir['group_id'], session::get_user_id())  )
		{

			$links = groups_links_dirs_peer::instance()->get_by_group($this->group['id'], request::get_int('id'));
                        foreach($links as $value) groups_links_peer::instance()->delete_item($value);
                        groups_links_dirs_peer::instance()->delete_item($this->linkdir['id']);
		}

		$this->redirect('/groups/file?id=' . $this->group['id']);
	}
}
