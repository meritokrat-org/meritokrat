<?
class team_members_history_peer extends db_peer_postgre
{
	protected $table_name = 'team_members_history';
	protected $primary_key = null;

	public static function instance()
	{
		return parent::instance( 'team_members_history_peer' );
	}

	public function add($group_id, $user_id, $function_id=0, $reason='')
	{
		$insert = array('group_id' => $group_id, 
                    'user_id' => $user_id, 
                    'function' => $function_id,
                    'date_start' => time(),
                    'reason' => strip_tags($reason));
                $this->insert($insert);
	}
        
        public function set_function($group_id,$user_id,$function_id){ 
            if(!db::get_scalar("SELECT user_id FROM ".$this->get_table_name()." 
                WHERE group_id=:group_id AND user_id=:user_id AND function=:function AND date_end ISNULL",array(
            'group_id' => $group_id,
            'user_id' => $user_id,
            'function' => $function_id)))
                    $this->add($group_id,$user_id,$function_id);
        }
        
        public function end_function($group_id,$user_id,$function_id){
                db::exec("UPDATE ".$this->get_table_name()." SET group_id=:group_id, user_id=:user_id, function=:function,
                  date_end=".time()." WHERE group_id=:group_id AND user_id=:user_id AND date_end ISNULL",array(
            'group_id' => $group_id,
            'user_id' => $user_id,
            'function' => $function_id));        
        }
        
        public function get_history($group_id,$function_id){
           return db::get_rows("SELECT * FROM ".$this->get_table_name()." 
                WHERE group_id=:group_id AND function=:function ORDER BY date_end DESC",array(
            'group_id' => $group_id,
            'function' => $function_id));
        }

        public function get_member_history($user_id){
           return db::get_rows("SELECT * FROM ".$this->get_table_name()." WHERE user_id=:user_id ORDER BY date_start",array('user_id' => $user_id));
        }
        
        public function set_member_history($group_id,$user_id,$start=false,$end=false,$reason=''){
           if($end)
           {
               db::exec("UPDATE ".$this->get_table_name()." SET date_end=:date_end, reason=:reason  WHERE group_id=:group_id AND user_id=:user_id",
                       array('group_id' => $group_id,'user_id' => $user_id,'date_end' => $end,'reason' => $reason));
           }
           else
           {
               db::exec("UPDATE ".$this->get_table_name()." SET date_end=:date_end, reason=:reason  WHERE user_id=:user_id",
                       array('user_id' => $user_id,'date_end' => time(),'reason' => strip_tags($reason)));
               $this->add($group_id, $user_id, 0, $reason);
           }
        }

}