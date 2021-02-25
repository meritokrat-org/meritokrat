<?
class profile_admin_file_delete_action extends frontend_controller
{
	public function execute()
	{
		load::model('library/files');
                if(!session::has_credential('admin'))$this->redirect ("/");
		if ( !$this->file = library_files_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Файл не найден');
		}
			library_files_peer::instance()->delete_item($this->file['id']);
                if($this->file['dir_id'])
		$this->redirect('/profile/admin_file?dir_id=' . $this->file['dir_id']);
                else $this->redirect('/profile/admin_file?id=' . $this->file['object_id']);
	}
}
