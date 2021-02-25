<?
class library_file_delete_action extends frontend_controller
{
        protected $authorized_access = true;
        protected $credentials = array('admin');
        
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

		$this->redirect('/library/?dir_id=' . $this->file['dir_id']);
	}
}
