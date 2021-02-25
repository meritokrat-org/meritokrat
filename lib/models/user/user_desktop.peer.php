<?
class user_desktop_peer extends db_peer_postgre
{
	protected $table_name = 'user_desktop';
	protected $primary_key = 'user_id';

	/**
	 * @return user_desktop_peer
	 */
	public static function instance()
	{	
            return parent::instance( 'user_desktop_peer' );
	} 


	public static function get_publication_types()
	{
		return array(
			1 => t('Интервью'),
			2 => t('Статья'),
			3 => t('Комментарий'),
			4 => t('Новость'),
			5 => t('Обьявление'),
			16 => t('Другое')
		);
	}

	public static function get_publication_type($id)
	{
		$types=self::get_publication_types();
                return $types[$id];
	}

        public function is_regional_coordinator($id,$ret_array=false)
        {
                //if (!session::is_authenticated()) return false;
                $query = db::get_row("SELECT user_id,regions FROM ".$this->table_name." WHERE  user_id = :uid AND  (functions && '{5}' OR functions && '{113}' OR functions && '{123}')",array('uid'=>$id));
                $ar = $this->get_user_functions('region',$id,5);
                if(count($ar)==0 && $ret_array)return array(); 
                if($query['user_id']) return $ar;
                else return FALSE;
        }

        public function is_raion_coordinator($id,$ret_array=false)
        {
                //if (!session::is_authenticated()) return false;
                $query = db::get_row("SELECT user_id,regions FROM ".$this->table_name." WHERE  user_id = :uid AND  (functions && '{6}' OR functions && '{111}' OR functions && '{112}' OR functions && '{121}' OR functions && '{122}')",array('uid'=>$id));
                $ar = $this->get_user_functions('city',$id,6);
                if(count($ar)==0 && $ret_array)return array(); 
                if($query['user_id']) return $ar;
                else return FALSE;
        }

	public function get_functions($id)
	{
		return db::get_row("SELECT user_id, functions FROM ".$this->table_name." WHERE  user_id = :uid AND  (functions && '{6}' OR functions && '{111}' OR functions && '{112}' OR functions && '{121}' OR functions && '{122}')",array('uid'=>$id));
	}

        public function is_logistic_coordinator($id,$col='region',$ret_array=false)
        {
                //if (!session::is_authenticated()) return false;
                $query = db::get_row("SELECT user_id,regions FROM ".$this->table_name." WHERE user_id = :uid AND functions && '{18}' ",array('uid'=>$id));
                if($col=='region')$add=" AND city_id=0";
                $ar = $this->get_user_functions($col,$id,18,$add);
                if(count($ar)==0 && $ret_array)return array(); 
                if($query['user_id']) return $ar;
                else return FALSE;
        }

        public function get_regional_coordinators($region_id)
        {
                //if (!session::is_authenticated()) return false;
                $arr = array();
                $query = db::get_rows('SELECT user_id FROM user_desktop_funct WHERE function_id=5 AND region_id='.$region_id);
                foreach($query as $k => $v)
                {
                    $arr[] = $v['user_id'];
                }
                return $arr;
        }

        public function get_raion_coordinators($raion_id)
        {
               $arr = array(); 
                $query = db::get_rows('SELECT user_id FROM user_desktop_funct WHERE function_id=6 AND city_id='.$raion_id);
                foreach($query as $k => $v)
                {
                    $arr[] = $v['user_id'];
                }
                return $arr;
        }
        
        public function get_logistic_coordinators($region_id=0,$raion_id=0)
        {
               $arr = array(); 
               if($raion_id>0)$add=' AND city_id='.intval($raion_id);
                $query = db::get_rows('SELECT user_id FROM user_desktop_funct WHERE function_id=18 AND region_id='.intval($region_id).$add);
                foreach($query as $k => $v)
                {
                    $arr[] = $v['user_id'];
                } 
                return $arr;
        }
        
        public function get_user_functions($col='region',$user_id,$fid,$add=""){
            if(!filter_var($user_id,FILTER_VALIDATE_INT)) $user_id = 0;
            $data = db::get_rows('SELECT '.$col.'_id FROM user_desktop_funct 
                    WHERE user_id = '.$user_id.' AND function_id='.$fid.$add);
            
            foreach ($data as $v){
                $ar[]=$v[$col.'_id'];
            } 
            return $ar;
        }

        public function get_contacts($user_id=0)
        {
                return db::get_rows('SELECT * FROM user_contact WHERE contacter_id='.$user_id.' ORDER by date DESC');
        }
}