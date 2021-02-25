<?
class library_index_action extends frontend_controller
{
        protected $authorized_access = true;
        
	public function execute()
	{
                load::model('library/files_dirs');

                if ( request::get('submit') && ($dir_name = trim(request::get('title') )))
		{
			load::model('library/files');
			load::system('storage/storage_simple');
                        $position = request::get_int('position');
                        db::exec("UPDATE files_dirs SET position=position+1 WHERE position>=:position", array('position'=>$position));
			$dir_id = library_files_dirs_peer::instance()->insert(array(
					'title' => $dir_name,
                                        'parent_id' =>  request::get_int('parent_id',0),
                                        'position' =>$position
				));

                        $this->redirect('/library/');
		}

		load::model('library/files');
		load::model('library/files_dirs');

		$this->dirs_tree = $this->get_child_dirs(0);
               // print_r($this->dirs_tree);
                //$this->parents =  $this->get_child_dirs(0,false);
		$this->dirs = library_files_dirs_peer::instance()->get_list(array("object_id"=>0),
                        array(),array('position ASC'));
		$this->dirs_lists = array(0 => t('немає'));
		foreach ( $this->dirs as $id )
		{
			$dir = library_files_dirs_peer::instance()->get_item($id);
			$this->dirs_lists[$id] = stripslashes($dir['title']);
                        $this->files[$id]=library_files_peer::instance()->get_list(array('dir_id'=>$id),array(),array('position ASC'));
		}
                $this->files[0] = library_files_peer::instance()->get_list(array('dir_id'=>0));
                $this->dirs[]=0;
                $this->last_parent_dir=db::get_scalar("SELECT id FROM files_dirs  WHERE parent_id=0 ORDER by position DESC LIMIT 1");

	}
        public function get_child_dirs($dir_id,$recursion=true){
            $dirs = library_files_dirs_peer::instance()->get_list(array('parent_id'=>$dir_id,"object_id"=>0),array(),array('position ASC'));
            if (!$recursion) return $dirs;
            if (!is_array($dirs)) return false;
            else {
                foreach($dirs as $dir)
                        {
                            $all_dirs[$dir] = $this->get_child_dirs($dir);
                        }
            }
            return $all_dirs;
        }
}