<?
load::app('modules/ppo/controller');
class ppo_file_delete_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/files');
		if ( !$this->file = ppo_files_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Файл не найден');
		}

		$this->group = ppo_peer::instance()->get_item($this->file['group_id']);

		if (ppo_peer::instance()->is_moderator($this->file['group_id'], session::get_user_id()) || $this->file['user_id']==session::get_user_id())
		{
			ppo_files_peer::instance()->delete_item($this->file['id']);
		}

		$this->redirect('/ppo/file?id=' . $this->group['id'] . '&dir_id=' . $this->file['dir_id']);
	}
}
