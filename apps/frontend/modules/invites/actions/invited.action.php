<?

class invites_invited_action extends frontend_controller
{
	public function execute()
	{
                $this->disable_layout();

		load::view_helper('tag', true);
                load::action_helper('page',false);
                load::view_helper('user');
                load::model('invites/invites');

                $this->page=request::get_int('page',1);
                $this->q=request::get('q');

                $group_id = intval(str_replace("http://".conf::get('server')."/group","",$_SERVER['HTTP_REFERER']));
                if ($group_id)
                {
                    $this->id = $group_id;
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$group_id.' AND type = 2 AND status = 0');
                }
                $event_id = intval(str_replace("http://".conf::get('server')."/event","",$_SERVER['HTTP_REFERER']));
                if ($event_id)
                {
                    $this->id = $event_id;
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$event_id.' AND type = 1 AND status = 0');
                }
                $poll_id = intval(str_replace("http://".conf::get('server')."/poll","",$_SERVER['HTTP_REFERER']));
                if ($poll_id)
                {
                    $this->id = $poll_id;
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$poll_id.' AND type = 3 AND status = 0');
                }

                if(count($this->invited)>0)
                {
                    $this->invited = array_unique($this->invited);
                    $this->invpager = page_helper::get_pager($this->invited, request::get_int('page'), 10);
                    $this->invited = $this->invpager->get_list();
                }
                else
                    $this->invited = array();
	}
}