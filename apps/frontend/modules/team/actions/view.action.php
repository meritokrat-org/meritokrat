<?

load::app('modules/team/controller');

class team_view_action extends team_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        $this->group = team_peer::instance()->get_item(request::get_int('id'));

        load::model('user/party_inventory');
        $this->inv_owners = team_members_peer::instance()->get_members($this->group['id'], false, $this->group);
        if ($this->inv_owners)
            $this->inventory_type = db::get_cols("SELECT inventory_type FROM party_inventory WHERE user_id IN (" . implode(",", $this->inv_owners) . ") GROUP BY inventory_type");

        $this->user = user_auth_peer::instance()->get_item(session::get_user_id());
        $this->user_data = user_data_peer::instance()->get_item(session::get_user_id());
        if (!$this->group || (!session::has_credential('admin') and $this->group['active'] != 1)) $this->redirect('/team');
        client_helper::set_title(stripslashes(htmlspecialchars($this->group['title'])) . ' | ' . conf::get('project_name'));

        $user_desktop = db::get_row("SELECT * FROM user_desktop WHERE user_id=:user_id", array('user_id' => $this->user['id']));
        $this->user_functions = (array)explode(',', str_replace(array('{', '}'), array('', ''), $user_desktop['functions']));

        $this->moderators = team_peer::instance()->get_moderators($this->group['id'], $this->group);

        $this->is_member = team_members_peer::instance()->is_member($this->group['id'], session::get_user_id());
        if (!$this->is_member) {
            load::model('invites/invites');
            $num = invites_peer::instance()->get_by_user(session::get_user_id(), 2, request::get_int('id'));
            if (count($num) > 0) {
                $this->has_invite = 1;
            }
        }
        if (($this->group['hidden'] == 1) && !$this->is_member && !$this->has_invite && !session::has_credential('admin')) {
            throw new public_exception(t('У вас недостаточно прав'));
            return;
        }
        if (($this->group['privacy'] == team_peer::PRIVACY_PRIVATE) && !$this->is_member && !session::has_credential('admin')) {
            load::model('team/applicants');
            $this->privacy_closed = true;
            return;
        }
        load::model('blogs/posts');
        load::model('blogs/comments');

        if ($posts = blogs_posts_peer::instance()->get_by_team($this->group['id'])) {
            $this->posts = array_slice($posts, 0, 10);
        }

        load::model('events/events');
        $this->events = events_peer::instance()->get_by_content_id($this->group['id'], 4);
        $this->events = array_slice($this->events, 0, 10);

        load::model('team/files');
        $this->files = team_files_peer::instance()->get_by_group($this->group['id']);
        $this->files = array_slice($this->files, 0, 10);

        load::view_helper('photo');
        load::model('photo/photo');
        $this->photos = photo_peer::instance()->get_by_obj($this->group['id'], 4);
        $this->photos = array_slice($this->photos, 0, 3);

        $this->news = team_news_peer::instance()->get_by_group($this->group['id']);

        $this->get_team_members($this->group);
        $this->get_sub_teams($this->group);
        $this->get_team_stat($this->group);

        load::model('eventreport/eventreport');
        $this->is_leader = team_members_peer::instance()->is_leader(session::get_user_id());
        if ($this->is_leader || session::has_credential('admin')) {
            $this->reports = eventreport_peer::instance()->get_all_by_team($this->group['id']);
        } else {
            $this->reports = eventreport_peer::instance()->get_by_team($this->group['id']);
        }
    }

    private function get_team_members($team)
    {
        $this->members = team_members_peer::instance()->get_members($team['id'], false, $team);
    }

    private function get_sub_teams($team)
    {
        $this->sub_teams = team_peer::instance()->get_sub_teams($team);
    }

    private function get_team_stat($team)
    {
        $stat = [];
        $category = $team['category'] - 2;

        while ($category > 0) {
            $stat[] = count(team_peer::instance()->get_sub_teams_by_category($team, $category));
            $category--;
        }

        $stat[] = count($this->members);

        $this->team_stat = $stat;
    }

}