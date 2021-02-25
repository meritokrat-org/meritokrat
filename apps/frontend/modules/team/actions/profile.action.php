<?php

load::app('modules/team/controller');
load::model('team/team');

class team_profile_action extends team_controller
{
    public function execute()
    {
        parent::disable_layout();

        $id = request::get_int('id');
        $team = team_peer::instance()->get_item($id);

        $this->team = $team;
    }
}
