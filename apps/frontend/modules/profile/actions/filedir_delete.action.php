<?

class profile_filedir_delete_action extends frontend_controller
{
	public function execute()
	{
		load::model('library/files_dirs');
		load::model('library/files');
		if ( !$this->filedir = library_files_dirs_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Папка не найдена');
		}

		if ( $this->filedir['object_id']==session::get_user_id())
		{
                        library_files_dirs_peer::instance()->delete_item($this->filedir['id']);
		}

		$this->redirect('/profile/file?id='.session::get_user_id());
	}
}
