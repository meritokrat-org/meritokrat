<?

class invites_group_invite_action extends frontend_controller
{
        protected $authorized_access = true;

        public function execute()
	{
                $this->disable_layout();

                $url = "http://".conf::get('server')."/profile-";
                if ($user_id=str_replace(array($url),array(""),$_SERVER['HTTP_REFERER']))
                {
                    load::model('groups/members');
                    $this->user_groups = groups_members_peer::instance()->get_groups($user_id);
                }
                else
                {
                    return;
                }

                load::view_helper('group');
                load::model('groups/groups');

                foreach ( groups_peer::get_categories() as $category => $title )
                {
                    $this->get_sorted($category);
                    $this->categories[$category] = $title;
                }

                $this->user_id = $user_id;
                $this->invites = db::get_cols('SELECT obj_id FROM invites WHERE to_id = '.$user_id.' AND type = 2 AND status = 0');
                $this->invites = array_unique($this->invites);
	}

        private function get_sorted($category)
        {
                $array = groups_peer::instance()->get_hot(null,null,null,$category);
                $array = array_diff($array, $this->user_groups);
                foreach($array as $group)
                {
                    $new[$group] = db::get_scalar('SELECT COUNT(*) FROM groups_members WHERE group_id = '.$group);
                }
                if(is_array($new))
                {
                    ksort($new);
                    foreach($new as $k => $v)
                    {
                        $this->groups[$category][] = $k;
                    }
                }
                else
                {
                    $this->groups[$category] = array();
                }
        }
}
