<?

class  profile_dir_edit_action extends frontend_controller
{
	public function execute()
	{
		load::model('library/files_dirs');
		if ( request::get('submit') )
		{
			$dir_id = request::get_int('dir_id');
                                if ( strlen($_POST['title'])>2 )
                                    {
                                    $position = request::get_int('position');
                                           library_files_dirs_peer::instance()->update(array(
                                                    'title' => trim(request::get_string('title')),
                                                    'parent_id' => request::get_int('parent_id'),
                                                    'position' => $position
                                            ),array('id' => $dir_id,"object_id"=>session::get_user_id(),"type"=>1));
                                    }
			$this->redirect('/profile/file?dir_id='.$dir_id);
		}
	}
}
