<?php

load::app('modules/api/controller');
load::model('ppo/ppo');

class api_ppo_action extends api_controller
{
    public function init()
    {
        parent::init();

        $this
            ->registerDirection('children')
            ->registerDirection('set_state');
    }

    public function set_state($id, $state)
    {
        $ppo = ppo_peer::instance()->get_item($id);
        if (!$ppo) {
            return false;
        }

        ppo_peer::instance()->update(['active' => (int) $state], ['id' => $id]);

        return true;
    }

    public function children($id)
    {
        $ppo = ppo_peer::i()->get_item($id);
        if (!$ppo) {
            return false;
        }


        $level  = (int) $ppo['category'] - 1;
        $region = $ppo['region_id'];

        return ppo_peer::instance()->findByLevelAndRegion($level, $region);
    }
}