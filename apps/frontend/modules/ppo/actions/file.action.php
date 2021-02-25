<?

load::app('modules/ppo/controller');
class ppo_file_action extends ppo_controller
{
	public function execute()
	{
            if ( !$this->group = ppo_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/ppo/');
		}
                load::model('ppo/files_dirs');
               $dir_name = trim(request::get_string('title') );
                if ( request::get('submit') && ($dir_name && ppo_peer::instance()->is_moderator($this->group['id'], session::get_user_id())))
		{
			load::model('ppo/files');
			load::system('storage/storage_simple');
                        $position = request::get_int('position');
                        db::exec("UPDATE ppo_files_dirs SET position=position+1 WHERE position>=:position AND group_id=:group_id", array('position'=>$position,'group_id'=>$this->group['id']));
			$dir_id = ppo_files_dirs_peer::instance()->insert(array(
					'title' => $dir_name,
                                        'group_id' =>  $this->group['id'],
                                        'parent_id' =>  request::get_int('parent_id',0),
                                        'position' =>$position
				));
                        
                        $this->redirect('/ppo/file?id='.$this->group['id'].'&dir_id='.$dir_id);
		}
                
		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) 
                        && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id())
                                && !session::has_credential('admin'))
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('ppo/files');
		load::model('ppo/files_dirs');

		$this->dirs_tree = $this->get_child_dirs(0);
                //$this->parents =  $this->get_child_dirs(0,false);
		$this->dirs = ppo_files_dirs_peer::instance()->get_list(array('group_id'=>$this->group['id']),array(),array('position ASC'));
                $this->dirs_lists = array(0 => t('немає'));
                if (count($this->dirs)>0) { 
                    foreach ( $this->dirs as $id )
                    {
                            $dir = ppo_files_dirs_peer::instance()->get_item($id);
                            $this->dirs_lists[$id] = $dir['title'];
                            $this->files[$id]=ppo_files_peer::instance()->get_list(array('dir_id'=>$id),array(),array('position ASC'));
                    }
                    $this->files[0] = ppo_files_peer::instance()->get_list(array('dir_id'=>0));
                    $this->dirs[]=0;
                }
                $this->count_dirs=db::get_scalar("SELECT count(*) FROM ppo_files_dirs  WHERE parent_id=0 AND group_id=".$this->group['id']);
	}
        public function get_child_dirs($dir_id,$recursion=true){
            $dirs = ppo_files_dirs_peer::instance()->get_list(array('parent_id'=>$dir_id,'group_id'=>$this->group['id']),array(),array('position ASC'));
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