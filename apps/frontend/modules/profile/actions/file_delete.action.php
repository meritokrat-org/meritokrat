<?
class profile_file_delete_action extends frontend_controller
{
	public function execute()
	{
		load::model('library/files');
		if ( !$this->file = library_files_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Файл не найден');
		}

		if ( ($this->file['user_id'] == session::get_user_id()) || session::has_credential('admin'))
		{
			library_files_peer::instance()->delete_item($this->file['id']);
		}
                if($this->file['dir_id'])
		$this->redirect('/profile/file?dir_id=' . $this->file['dir_id']);
                else $this->redirect('/profile/file?id=' . session::get_user_id());
	}
}
