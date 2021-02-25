<?

class blogs_programs_peer extends db_peer_postgre
{
	protected $table_name = 'blogs_programs';

	/**
	 * @return blogs_programs_peer
	 */
        
	public static function instance()
	{
		return parent::instance( 'blogs_programs_peer' );
	}

	public function get_programs( $blog_id )
        {
                return db::get_cols('SELECT program_id FROM '.$this->table_name.' WHERE blog_id = '.$blog_id);
        }

        public function save_programs( $blog_id,$programs=false )
        {
                if(!is_array($programs))
                {
                    $programs = request::get('programs');
                }
                db::exec('DELETE FROM '.$this->table_name.' WHERE blog_id = '.$blog_id);
                if(is_array($programs) && count($programs)>0)
                {
                    foreach($programs as $p)
                    {
                        db::exec('INSERT INTO '.$this->table_name.' (blog_id,program_id) VALUES ('.intval($blog_id).','.intval($p).')');
                    }
                }
        }

        public function get_blogs( $program_id )
        {
                return db::get_cols('SELECT blog_id FROM '.$this->table_name.' WHERE program_id = '.$program_id);
        }

        public function theme_list( $public='' )
        {
                $array = db::get_rows('SELECT p.program_id, COUNT(*) as summ FROM '.$this->table_name.' p, blogs_posts b WHERE p.blog_id=b.id AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public.' GROUP BY program_id');
                foreach($array as $a)
                {
                    $arr[$a['program_id']] = $a['summ'];
                }
                foreach(user_helper::get_program_types() as $k=>$v)
                {
                    if(session::has_credential('admin') || intval($arr[$k])!=0)
                    {
                        $themes[$k] = array(
                            'title' => $v,
                            'summ' => intval($arr[$k])
                            );
                    }
                }
                return $themes;
        }
}
