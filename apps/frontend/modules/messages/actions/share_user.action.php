<?
load::app('modules/messages/controller');
class messages_share_user_action extends messages_controller
{
	public function execute()
	{
		load::view_helper('tag', true);
                $this->page=request::get_int('page',1);
		$this->disable_layout();
                $bind['active'] = 1;
                $this->q=request::get('q');
                $group_id = intval(str_replace("http://".conf::get('server')."/group","",$_SERVER['HTTP_REFERER']));
                if ($group_id)
                {
                    $where = " AND id NOT IN (SELECT user_id FROM groups_members WHERE group_id=".$group_id.")";
                    //$bind['group_id'] = $group_id;
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$group_id.' AND type = 2 AND status = 0');
                }
                $event_id = intval(str_replace("http://".conf::get('server')."/event","",$_SERVER['HTTP_REFERER']));
                if ($event_id)
                {
                    $where = " AND id NOT IN (SELECT user_id FROM events2users WHERE event_id=".$event_id.")";
                    //$bind['event_id'] = $event_id;
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$event_id.' AND type = 1 AND status = 0');
                }
                $poll_id = intval(str_replace("http://".conf::get('server')."/poll","",$_SERVER['HTTP_REFERER']));
                if ($poll_id)
                {
                    $where = " AND id NOT IN (SELECT user_id FROM polls_votes WHERE poll_id=".$poll_id.")";
                    //$bind['poll_id'] = $poll_id;
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$poll_id.' AND type = 3 AND status = 0');
                }
                $ppo_id = intval(str_replace("http://".conf::get('server')."/ppo","",$_SERVER['HTTP_REFERER']));
                if ($ppo_id)
                { 
                    $where = " AND id NOT IN (SELECT user_id 
                                FROM ppo_members WHERE group_id IN(SELECT id FROM ppo WHERE category=1))
                        AND id IN (SELECT id FROM user_auth WHERE status=20 OR ban=20)";
                    $this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = '.$ppo_id.' AND type = 4 AND status = 0');
                }
                if (strpos($_SERVER['HTTP_REFERER'], 'reestr'))
                {
                    //$where = " AND id NOT IN (SELECT id FROM user_auth WHERE status = 20)";
                    $this->invited = db::get_cols('SELECT id FROM user_auth WHERE status = 20');
                    $this->reestr = 1;
                }
                if (session::get('list_id'))
                {
                    $where = " AND id NOT IN (SELECT user_id FROM lists2users WHERE list_id=".session::get('list_id').")";
                }
                if($key=request::get('q'))
                { 
                   if(strlen($key)>2) 
                       switch(request::get('type')) {
                            case 'recommendation':
                                $this->users = user_data_peer::instance()->search_by_status($key, array(request::get('param'),0));
                                break;
                            default:
                                if ($ppo_id)
                                    $this->users=user_data_peer::instance()->get_by_name_ppo($key,str_replace("AND id", "AND user_id", $where));
                                    else
                                $this->users=user_data_peer::instance()->get_by_name2($key,$where);
                                break;
                       }
                }
                else
                    $this->users = db::get_cols("SELECT id FROM user_auth WHERE active = TRUE ".$where);
		load::action_helper('pager', true);
                if(count($this->users)>0)
                {
                    $this->pager = pager_helper::get_pager($this->users, request::get_int('page'), 12);
                    $this->users = $this->pager->get_list();
                }else{
                    $this->users = array();
                }
	}
}