<?

class library_filedir_delete_action extends frontend_controller
{
        protected $authorized_access = true;
        protected $credentials = array('admin');
        
	public function execute()
	{
		load::model('library/files_dirs');
		load::model('library/files');
		if ( !$this->filedir = library_files_dirs_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Папка не найдена');
		}

		if ( session::has_credential('admin'))
		{

			//$files = library_files_dirs_peer::instance()->get_by_group($request::get_int('id'));
                        //foreach($files as $value) library_files_peer::instance()->delete_item($value);
                        library_files_dirs_peer::instance()->delete_item($this->filedir['id']);
		}

		$this->redirect('/library');
	}
}
