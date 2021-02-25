<?

class friends_peer extends db_peer_postgre
{
	protected $table_name = 'friends';

	/**
	 * @return friends_peer
	 */
	public static function instance()
	{
		return parent::instance( 'friends_peer' );
	}

	public function get_by_user( $user_id )
	{
		$sql = 'SELECT friend_id FROM ' . $this->table_name . ' WHERE user_id = :user_id AND friend_id NOT IN (SELECT id FROM user_auth WHERE del != 0)';
		return db::get_cols($sql, array('user_id' => $user_id), $this->connection_name);
	}

	public function delete( $user_id, $friend_id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . '
				WHERE (user_id = :user_id AND friend_id = :friend_id) OR
					  (user_id = :friend_id AND friend_id = :user_id)';
		db::exec($sql, array('user_id' => $user_id, 'friend_id' => $friend_id), $this->connection_name);
	}

	public function is_friend( $user_id, $friend_id )
	{
		$list = $this->get_by_user($user_id);
		return in_array($friend_id, $list);
	}
    
        public function shared_friends( $user_id, $friend_id )
	{
		$list1 = $this->get_by_user($user_id);
		$list2 = $this->get_by_user($friend_id);
        return array_intersect($list1,$list2);
	}

	public function delete_by_user( $user_id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . '
				WHERE user_id = :user_id OR friend_id = :user_id';
		
		db::exec($sql, array('user_id' => $user_id), $this->connection_name);
	}

        public function get_by_date( $user_id )
	{
            if(intval($user_id)!=0)
            {
                $day = date("d");
                $month = date("m");

                $result = db::get_cols("SELECT user_id FROM user_data WHERE date_part('month',birthday) = ".$month." AND date_part('day',birthday) = ".$day);
                $result = $this->check_active($result);

                if(count($result)>0)
                {
                    return $result;
                }
                else
                {
                    return $this->get_soon_birthdays($user_id);
                }
            }
        }

        private function check_active( $result )
        {
            if(count($result)==0)return array();
            $query = db::get_cols("SELECT id FROM user_auth WHERE active = TRUE AND id IN (".implode(',',$result).")");

            if(count($query)>0)
            {
                shuffle($query);
                return $query;
            }
        }

        public function get_soon_birthdays( $user_id )
	{
            if(intval($user_id)!=0)
            {
                $day = date("d");
                $month = date("m");
                $year = date("Y");

                if ( mem_cache::i()->exists('birthdays_'.$day.$month.$year) )
                {
                    $result = mem_cache::i()->get('birthdays_'.$day.$month.$year);
                    return $this->check_active($result);
                }
                else
                {
                    //$day_interval = date("d",time()+691200);
                    $month_interval = date("m",time()+691200);
                    for($i=time();$i<=(time()+691200);$i+=86400)
                    {
                        if(date("m",$i)==$month)
                            $day1[] = date("d",$i);
                        else
                            $day2[] = date("d",$i);
                    }
                    if(count($day2)!=0)$where = " OR (date_part('month',birthday) = ".$month_interval." AND date_part('day',birthday) IN (".implode(',',$day2)."))";
   
                    $sql = "SELECT user_id FROM user_data WHERE                           
                                (date_part('month',birthday) = ".$month." AND date_part('day',birthday) IN (".implode(',',$day1)."))".$where;
                    $result = db::get_cols($sql);

                    mem_cache::i()->set('birthdays_'.$day.$month.$year, $result);

                    return $this->check_active($result);
                }
            }
        }

        public function get_by_order( $user_id )
        {
            $day = date("d");
            $month = date("m");
            $year = date("Y");

            if ( mem_cache::i()->exists('birthdays_ord_'.$day.$month.$year) )
            {
                return mem_cache::i()->get('birthdays_ord_'.$day.$month.$year);
            }
            else
            {
                $result = $this->get_soon_birthdays($user_id);
                if(count($result)>0)
                {
                    $num = 0;
                    foreach( $result as $user_id )
                    {
                        $query = db::get_row("SELECT user_id,birthday FROM user_data WHERE user_id = ".$user_id);
                        if(count($query)>0)
                        {
                            $date = explode('-',$query['birthday']);
                            $cur = date("n");

                            if(intval($date[1])>$cur)
                            {
                                $time = mktime(0,0,$num,$date[1],$date[2],1);
                            }
                            else
                            {
                                $time = mktime(0,0,$num,$date[1],$date[2],0);
                            }

                            $arr[$time] = $query['user_id'];
                            $num++;
                        }
                    }
                    ksort($arr);
                    mem_cache::i()->set('birthdays_ord_'.$day.$month.$year, $arr);

                    return $arr;
                }
            }
        }
}