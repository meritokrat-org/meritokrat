<?

abstract class zayava_controller extends frontend_controller
{
	protected $authorized_access = true;
        public $access;
        protected $region;
        protected $city;

        public function init()
	{
		parent::init();
                load::model('geo');
                load::model('user/zayava');
                load::model('user/user_recommend');
                load::model('ppo/ppo');
                load::model('ppo/members');
                load::model('user/user_desktop');

                $region_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id());
                $raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id());
                $ppo_leader = ppo_members_peer::instance()->ppo_info(session::get_user_id());

                if(session::has_credential('admin'))
                {
                    $this->access = 'all';
                }
                elseif($region_coordinator && count($region_coordinator)>0)
                {
                    $this->access = 'region';
                    $this->region = $region_coordinator;
                }
                elseif($raion_coordinator && count($raion_coordinator)>0)
                {
                    $this->access = 'city';
                    $this->city = $raion_coordinator;
                }
                elseif($ppo_leader && count($ppo_leader)>0)
                {
                    if($ppo_leader[0])
                    {
                        $this->access = 'region';
                        $this->region = array($ppo_leader[0]);
                    }
                    elseif($ppo_leader[1])
                    {
                        $this->access = 'city';
                        $this->city = array($ppo_leader[1]);
                    }
                }

        }
}
