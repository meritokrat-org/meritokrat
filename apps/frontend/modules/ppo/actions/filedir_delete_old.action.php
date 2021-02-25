<?

load::app('modules/ppo/controller');
class ppo_filedir_delete_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/files_dirs');
		load::model('ppo/files');
		if ( !$this->filedir = ppo_files_dirs_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Папка не найдена');
		}

		$this->group = ppo_peer::instance()->get_item($this->filedir['group_id']);

		if ( ($this->filedir['user_id'] == session::get_user_id()) || ppo_peer::instance()->is_moderator($this->filedir['group_id'], session::get_user_id())  )
		{

			$files = ppo_files_dirs_peer::instance()->get_by_group($this->group['id'], request::get_int('id'));
                        foreach($files as $value) ppo_files_peer::instance()->delete_item($value);
                        ppo_files_dirs_peer::instance()->delete_item($this->filedir['id']);
		}

		$this->redirect('/ppo/file?id=' . $this->group['id']);
	}
}
