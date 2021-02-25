<?php

load::app('modules/team/controller');

class team_show_territory_action extends team_controller
{
    public function execute()
    {
        $this->disable_layout();
    }
}