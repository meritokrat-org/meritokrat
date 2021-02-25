<?
class user_work_peer extends db_peer_postgre
{
	protected $table_name = 'user_work';
	protected $primary_key = 'user_id';
        protected $lang_cols=array("work_country","work_city","work_name","position",
            "last_work_country","last_work_city","last_work_name","last_position","expert","specialty");
	/**
	 * @return user_data_peer
	 */
	public static function instance()
	{
                load::action_helper('lang',false);
		return parent::instance( 'user_work_peer' );
	}
/*
	public function search( $filters = array(), $limit = 20, $offset = 0 )
	{
		$where = array('1=1');
		$bind = array('limit' => $limit , 'offset' => $offset);

		if ( $filters ) foreach ( $filters as $name => $value )
		{
			if ( is_array($value) )
			{
				if ( $value[0] )
				{
					$where[] = "{$name} <= :{$name}_from";
					$bind[  $name . '_from'] = $value[0];
				}

				if ( $value[1] )
				{
					$where[] = "{$name} >= :{$name}_to";
					$bind[$name . '_to'] = $value[1];
				}
			}
			elseif($name=='segment')
			{
				$where[] = "({$name} = :{$name} OR additional_segment = :additional_segment)";
				$bind[$name] = $value;
                                $bind['additional_segment'] = $value;
			}
			else
			{
				$where[] = "{$name} = :{$name}";
				$bind[$name] = $value;
			}
		}
		$sql = 'SELECT ' . $this->primary_key . '
				FROM ' . $this->table_name . '
				WHERE ' . implode(' AND ', $where) . '
				LIMIT :limit OFFSET :offset;';
                echo $sql ;
		return db::get_cols($sql, $bind, $this->connection_name);
	}
*/

	public function update( $data, $keys = null )
	{
		parent::update(lang_helper::set_lang_cols($this->lang_cols,$data), $keys);
	}

	public function insert($data, $ignore_duplicate = false)
	{
		$id = parent::insert($data, $ignore_duplicate);
		return $id;
	}
        
        public function get_item($id,$session='language'){ 
            $data=parent::get_item($id); 
            return lang_helper::get_lang_cols($this->lang_cols,$data,$session);
        }
}
