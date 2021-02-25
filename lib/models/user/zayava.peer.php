<?
class user_zayava_peer extends db_peer_postgre
{
	protected $table_name = 'user_zayava';
	protected $show_list = null;

	/**
	 * @return user_zayava_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_zayava_peer' );
	}

        public function get_by_status($status=0, $request=false, $region=false, $city=false, $date=0)
        {
            if($region && is_array($region) && count($region)>0)
                $sqladd .= " AND user_auth.id IN (SELECT user_id FROM user_data WHERE region_id IN (".implode(',',$region).")) ";
            elseif($city && is_array($city) && count($city)>0)
                $sqladd .= " AND user_auth.id IN (SELECT user_id FROM user_data WHERE city_id IN (".implode(',',$city).")) ";
            if($request)
                $sqladd .= " AND user_auth.id IN (SELECT user_id FROM user_data WHERE last_name ILIKE '$request%') ";
            if($date>0)$sqladd .= " AND user_zayava.date>".$date;
						
						switch($this->show_list){
							case "deleted":
								$not_deleted = "AND user_zayava.deleted = 1";
								break;
							
							default:
								$not_deleted = "AND (user_zayava.deleted IS NULL OR user_zayava.deleted = 0)";
								break;
						}
						
            if($status < 20)
                return db::get_cols('SELECT user_zayava.id FROM '.$this->table_name.' LEFT JOIN user_auth ON (user_zayava.user_id=user_auth.id) WHERE user_auth.status != 20 '.$not_deleted.' '.$sqladd.' ORDER BY user_zayava.date DESC');
            else
                return db::get_cols('SELECT user_zayava.id FROM '.$this->table_name.' LEFT JOIN user_auth ON (user_zayava.user_id=user_auth.id) WHERE user_auth.status = 20 '.$not_deleted.' '.$sqladd.' ORDER BY user_zayava.date DESC');
        }
				
				public function show_deleted_items($status = 0, $request = false, $region = false, $city = false, $date = 0){
					$this->show_list = "deleted";
					return $this->get_by_status($status, $request, $region, $city, $date);
				}

        public function get_user($user_id)
        {
            return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE user_id = '.$user_id);
        }

        public function get_user_zayava($user_id)
        {
            return db::get_row('SELECT * FROM '.$this->table_name.' WHERE user_id = '.$user_id);
        }

        public function check_user($user_id)
        {
            return db::get_scalar('SELECT id FROM '.$this->table_name.' WHERE user_id = '.$user_id);
        }
				
				public function to_trash($data){
					return db::exec("UPDATE ".$this->table_name." SET deleted = 1, del_user_id = ".$data['user_id'].", del_date = '".date("Y/m/d H:i:s")."', del_reason='".$data['del_reason']."' WHERE id = ".$data['id']);
				}
				
				public function in_trash($id){
					$i = db::get_row("SELECT deleted FROM ".$this->table_name." WHERE id = ".$id);
					if($i["deleted"] > 0){
						return true;
					} else {
						return false;
					}
				}
				
				public function recover_item($id, $user_id){
					return db::exec("UPDATE ".$this->table_name." SET deleted = 0, rec_user_id = ".$user_id.", rec_date = '".date("Y/m/d H:i:s")."' WHERE id = ".$id);
				}
				
				public function get_zayava($id){
					return db::get_row("SELECT *, to_char(del_date, 'DD.MM.YYYY HH24:II') AS del_date, to_char(rec_date, 'DD.MM.YYYY HH24:II') AS rec_date FROM ".$this->table_name." WHERE id=".$id);
				}

}