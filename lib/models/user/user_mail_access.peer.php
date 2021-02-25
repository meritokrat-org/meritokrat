<?
class user_mail_access_peer extends db_peer_postgre
{
	protected $table_name = 'user_mail_access';
        protected $primary_key = 'user_id';
        /**
	 * @return user_mail_access_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_mail_access_peer' );
	}

        public function get_item($user_id)
        {
            $result = $this->get_user($user_id);
            if($result['user_id'])
            {
                return $result;
            }
            else
            {
                parent::insert(array('user_id'=>$user_id));
                return $this->get_user($user_id);
            }
        }

        public function check_access($user_id,$template)
        {
            $result = $this->get_item($user_id);
            
            $templates = array_keys(db::get_row("SELECT * FROM user_mail_access LIMIT 1"));
            unset($templates['user_id']);
            $add = array('messages_compose_sendgroup','messages_reply','messages_share');
            if(in_array($template,$templates) && $result[$template])
            {
                return true;
            }
            elseif(in_array($template,$add) && $result['messages_compose'])
            {
                return true;
            }
            else
            {
                if(!in_array($template,$templates) && !in_array($template,$add))
                    return true;
                else
                    return false;
            }
        }

        protected function get_user($user_id)
        {
            return db::get_row('SELECT * FROM ' . $this->table_name . ' WHERE user_id = :user_id LIMIT 1', array('user_id' => $user_id));
        }

}
