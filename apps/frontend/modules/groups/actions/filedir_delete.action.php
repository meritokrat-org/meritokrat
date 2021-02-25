<?

load::app('modules/groups/controller');
class groups_filedir_delete_action extends groups_controller
{
	public function execute()
	{
		load::model('groups/files_dirs');
		load::model('groups/files');
		if ( !$this->filedir = groups_files_dirs_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Папка не найдена');
		}

		$this->group = groups_peer::instance()->get_item($this->filedir['group_id']);

		if (groups_peer::instance()->is_moderator($this->filedir['group_id'], session::get_user_id())  )
		{

			$files = groups_files_dirs_peer::instance()->get_by_group($this->group['id'], request::get_int('id'));
                        foreach($files as $value) groups_files_peer::instance()->delete_item($value);
                        groups_files_dirs_peer::instance()->delete_item($this->filedir['id']);
		}

		$this->redirect('/groups/file?id=' . $this->group['id']);
	}
}
