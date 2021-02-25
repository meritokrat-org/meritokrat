<?

class blogs_targets_peer extends db_peer_postgre
{
	protected $table_name = 'blogs_targets';

	/**
	 * @return blogs_programs_peer
	 */
        
	public static function instance()
	{
		return parent::instance( 'blogs_targets_peer' );
	}

	public function get_targets( $blog_id )
        {
                return db::get_cols('SELECT target_id FROM '.$this->table_name.' WHERE blog_id = '.$blog_id);
        }

        public function save_targets( $blog_id )
        {
                $targets = request::get('target_groups');
                db::exec('DELETE FROM '.$this->table_name.' WHERE blog_id = '.$blog_id);
                if(is_array($targets) && count($targets)>0)
                {
                    foreach($targets as $t)
                    {
                        db::exec('INSERT INTO '.$this->table_name.' (blog_id,target_id) VALUES ('.intval($blog_id).','.intval($t).')');
                    }
                }
        }

        public function get_blogs( $target_id )
        {
                return db::get_cols('SELECT blog_id FROM '.$this->table_name.' WHERE target_id = '.$target_id);
        }

        public function target_list( $public='' )
        {
                $array = db::get_rows('SELECT p.target_id, COUNT(*) as summ FROM '.$this->table_name.' p, blogs_posts b WHERE p.blog_id=b.id AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public.' GROUP BY target_id');
                foreach($array as $a)
                {
                    $arr[$a['target_id']] = $a['summ'];
                }
                foreach(user_helper::get_target_groups() as $k=>$v)
                {
                    if(session::has_credential('admin') || intval($arr[$k])!=0)
                    {
                        $targets[$k] = array(
                            'title' => $v,
                            'summ' => intval($arr[$k])
                            );
                    }
                }
                return $targets;
        }
}
