<?
load::app('modules/groups/controller');
class groups_file_delete_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('groups/files');
		if ( !$this->file = groups_files_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Файл не найден');
		}

		$this->group = groups_peer::instance()->get_item($this->file['group_id']);

		if (groups_peer::instance()->is_moderator($this->file['group_id'], session::get_user_id()) || $this->file['user_id']==session::get_user_id())
		{
			groups_files_peer::instance()->delete_item($this->file['id']);
		}

		$this->redirect('/groups/file?id=' . $this->group['id'] . '&dir_id=' . $this->file['dir_id']);
	}
}
