<?
class user_payments_peer extends db_peer_postgre
{
	protected $table_name = 'user_payments';

	/**
	 * @return user_payments_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_payments_peer' );
	}

        public function get_user( $user_id,$type=false,$approve=false )
        {
            if($type)
                $sqladd .= ' AND type = '.$type;
            if($approve==1)
                $sqladd .= ' AND approve != 2';
            if($approve==2)
                $sqladd .= ' AND approve = 2';
            return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE del = 0 AND user_id = '.$user_id.' ' .$sqladd. ' ORDER BY date desc');
        }

        public function get_by_user( $user_id )
        {
            $query = db::get_rows('SELECT id,type,date FROM '.$this->table_name.' WHERE del = 0 AND user_id = '.$user_id.' ORDER BY approve desc, date');
            $array = array();
            foreach($query as $q)
            {
                $array[$q['type']][] = $q['id'];
            }
            return $array;
        }

        public function get_total( $user_id )
        {
            $query = db::get_rows('SELECT type, SUM(summ) AS sm  FROM '.$this->table_name.' WHERE del = 0 AND user_id = '.$user_id.' AND approve = 2 GROUP BY type');
            $array = array();
            foreach($query as $q)
            {
                $array[$q['type']] = $q['sm'];
            }
            return $array;
        }

        public function get_months( $user_id )
        {
            return db::get_cols('SELECT period FROM '.$this->table_name.' WHERE del = 0 AND user_id = '.$user_id.' AND type = 2 AND approve = 2');
        }

        public function del_user( $user_id,$unset=array() )
        {
            if(count($unset)>0)$sqladd = ' AND id NOT IN ('.implode(',',$unset).')';
            db::exec('DELETE FROM '.$this->table_name.' WHERE user_id = '.$user_id.' AND approve = 0'.$sqladd);
        }
}