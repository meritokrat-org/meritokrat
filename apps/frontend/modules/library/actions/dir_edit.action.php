<?

class library_dir_edit_action extends frontend_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');
        
	public function execute()
	{

		load::model('library/files_dirs');
		if ( request::get('submit') )
		{
			$dir_id = request::get_int('dir_id');
                                if ( strlen($_POST['title'])>2 )
                                    {
                                    $position = request::get_int('position');
               //                     db::exec("UPDATE files_dirs SET position=position+1 WHERE position>=:position", array('position'=>$position));
                                    library_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'title' => trim(request::get_string('title')),
                                                    'parent_id' => request::get_int('parent_id'),
                                                    'position' => $position
                                            ));
                                    }
			$this->redirect('/library/index?dir_id='.$dir_id);
		}
	}
}
