<?
class user_view_peer extends db_peer_postgre
{
	protected $table_name = 'user_view';
	protected $primary_key = 'id';

	/**
	 * @return user_view_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_view_peer' );
	}
        
        public function get_not_viewed($user_id=0)
        {
            if (!$user_id) $user_id=session::get_user_id();
            return db::get_cols("SELECT user_id FROM user_view WHERE not_viewed && '{".$user_id."}' ORDER BY id DESC");
            
        }
        
        public function get_new()
        {
                    if (!$last_view_time=db_key::i()->get('last_user_view_time:'.session::get_user_id())) $last_view_time=1313452800;
                    
                    $base_sql='SELECT user_auth.id FROM user_auth LEFT JOIN user_data on user_auth.id=user_data.user_id WHERE user_auth.activated_ts>'.$last_view_time;
                   
                    if (session::has_credential('admin'))
                    {
                        return db::get_cols($base_sql);
                    }
                    
                    $leader_ppo=db::get_row('SELECT id, category, region_id, city_id FROM ppo WHERE active=1 AND id 
                                IN(SELECT group_id 
                                FROM ppo_members 
                                WHERE user_id=:user_id AND function IN(1,2) 
                                ORDER BY function DESC) LIMIT 1', array('user_id' => session::get_user_id())); 
               
                    load::model('user/user_desktop');
                        
                    $regional_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id(),true);
                    $raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id(),true);
                    
                  
                    if($leader_ppo)
                    {
                        if ($leader_ppo['category']==3)
                        {
                            $sql=$base_sql.' AND user_data.region_id='.$leader_ppo['region_id'];
                        }
                        else 
                        {
                            $sql=$base_sql.' AND user_data.city_id='.$leader_ppo['city_id'];
                        }
                        
                        $leader_ppo_people=db::get_cols($sql);
                    }
                    
                    if($region_coordinator && count($region_coordinator)>0)
                    {
                        $sql=$base_sql.' AND user_data.region_id in ('.implode(',',$region_coordinator).')';
                        $raion_coordinator_people=db::get_cols($sql);
                    }
                    
                    if($raion_coordinator && count($raion_coordinator)>0)
                    {
                        $sql=$base_sql.' AND user_data.city_id in ('.implode(',',$raion_coordinator).')';   
                        $raion_coordinator_people=db::get_cols($sql);
                    }
                    
                    $new_users=array_unique(
                                    array_merge(
                                        (array)$leader_ppo_people,
                                        (array)$raion_coordinator_people,
                                        (array)$raion_coordinator_people
                                        )
                                );
                   krsort($new_users);
                   return $new_users;
        }
        
        public function delete_one_additional_info($user_id)
        {
            if ($view_info=db::get_row('SELECT * FROM user_view WHERE user_id='.$user_id))
            {
                    
                    $view_info['not_viewed']=str_replace(array('{','}'), array('',''), $view_info['not_viewed']);
                    $not_viewed=explode(',',$view_info['not_viewed']);
                    $not_viewed=array_diff($not_viewed,array(session::get_user_id()));
                    $view_info['not_viewed']='{'.implode(',',$not_viewed).'}';                    
                    
                    parent::update($view_info);
            }
            
        }
}