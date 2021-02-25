<?
load::app('modules/events/controller');
class events_arhive_action extends events_controller
{
	public function execute()
	{
            $per_page=15; 
            $this->set_template('index');
            $events = events_peer::instance()->get_list(request::get_int('page'), $per_page, true,
                                request::get_int('section'), request::get_int('type'), request::get_int('cat'), null, null,request::get_int('region'),request::get_int('level'));
            $this->events = $events['list'];
            $this->pager = pager_helper::get_pager($events['count'], request::get_int('page'), $per_page);

            load::model('user/user_desktop');
            if(user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id())
                    OR user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id())
                    )
            $this->coordinator = 1; 
	}
}