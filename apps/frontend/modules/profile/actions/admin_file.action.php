<?
class profile_admin_file_action extends frontend_controller
{
	public function execute()
	{
                if(!session::has_credential('admin'))$this->redirect ("/");
                load::model('library/files_dirs');
                $this->user_id= request::get_int('id');
                if ( request::get('submit') && ($dir_name = trim(request::get('title') )))
		{
			load::model('library/files');
			load::system('storage/storage_simple');
                        $position = request::get_int('position');
                        db::exec("UPDATE files_dirs SET position=position+1 WHERE position>=:position", 
                                array('position'=>$position));
			$dir_id = library_files_dirs_peer::instance()->insert(array(
					'title' => $dir_name,
                                        'parent_id' =>  request::get_int('parent_id',0),
                                        'position' =>$position,
                                        'object_id'=>$this->user_id,
                                        "type"=>9
				));

                        $this->redirect('/profile/admin_file?id='.$this->user_id);
		}

		load::model('library/files');
		load::model('library/files_dirs');

		
                $this->dirs_tree = $this->get_child_dirs(0);
                $this->dirs_tree[0] = "";
                
		$this->dirs = library_files_dirs_peer::instance()->get_list(
                        array("object_id"=>$this->user_id,"type"=>9),array(),array('position ASC'));
		
                $this->dirs_lists = array(0 => t('немає'));
		foreach ( $this->dirs as $id )
		{
			$dir = library_files_dirs_peer::instance()->get_item($id);
			$this->dirs_lists[$id] = stripslashes($dir['title']);
                        $this->files[$id]=library_files_peer::instance()->get_list(
                                array('dir_id'=>$id,"object_id"=>$this->user_id,"type"=>9),array(),
                                array('position ASC'));
		}
                $this->files[0] = library_files_peer::instance()->get_list(
                        array('dir_id'=>0,"object_id"=>$this->user_id,"type"=>9),array(),array('position ASC'));
                $this->dirs[]=0; 
                $this->last_parent_dir=db::get_scalar("SELECT id FROM files_dirs  WHERE parent_id=0
                    AND type=9
                    AND object_id=".$this->user_id." ORDER by position DESC LIMIT 1");

	}
        public function get_child_dirs($dir_id,$recursion=true){
            $dirs = library_files_dirs_peer::instance()->get_list(
                    array('parent_id'=>$dir_id,"type"=>9,"object_id"=>$this->user_id),array(),
                    array('position ASC'));
            
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
