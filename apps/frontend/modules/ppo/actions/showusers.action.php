<?php

load::app('modules/ppo/controller');

class ppo_showusers_action extends ppo_controller
{
    public function execute()
    {
        load::action_helper('page', false);
        $this->disable_layout();
        $this->users = [];
    }
}