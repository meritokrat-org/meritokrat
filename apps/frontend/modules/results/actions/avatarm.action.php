<?

class results_avatarm_action extends frontend_controller
{
	protected $authorized_access = false;

	public function execute()
	{
       $data=db::get_rows("SELECT user_id,
                  avatarm
                  FROM user_desktop
                  WHERE
                  avatarm!='a:0:{}'");   
       
           foreach($data as $user){
               $avatarm=unserialize($user['avatarm']);
	       $user_data = user_data_peer::instance()->get_item($user['user_id']);
	       $this->users[$user['user_id']] = array('name'=>  $user_data['last_name'].' '.$user_data['first_name'], 'count'=>0);
               foreach($avatarm as $type=>$ng){
		   if($ng!=''){
		       $this->users[$user['user_id']]['count']++;
		       $this->avatarmcnt[$type]++;
		       $this->avatarmcntall++;
		   }
               }
	    }
	    
	    uasort($this->users, "results_avatarm_action::sort_users");
	    
        }
	
	public static function sort_users($a, $b) {
	    if($a['count']>$b['count']) 
		return -1;
	    elseif($a['count']<$b['count']) 
		return 1;
	    elseif($a['name']>$b['name'])
		return 1;
	    elseif($a['name']<$b['name'])
		return -1;
	    else 
		return 0;
	}
}