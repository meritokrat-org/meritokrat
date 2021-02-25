<?php

load::app('modules/ppo/controller');

class ppo_members_action extends ppo_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        if ($this->group = ppo_peer::instance()->get_item(request::get_int('id'))) {
            if (($this->group['privacy'] === ppo_peer::PRIVACY_PRIVATE) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin')) {
                $this->redirect('/ppo' . $this->group['id'] . '/' . $this->group['number']);
            }

            $this->status = request::get_int('status');
            $sql          = <<<'PGSQL'
select pm.user_id
from ppo_members pm
         join user_auth ua on pm.user_id = ua.id
where pm.group_id = :groupId
  and ua.status = :status
PGSQL;
            $sqlBind      = [
                'groupId' => $this->group['id'],
                'status'  => $this->status,
            ];

            if (!session::has_credential('admin')) {
                $sql                  .= ' and ua.invited_by = :invitedBy';
                $sqlBind['invitedBy'] = session::get_user_id();
            }

            $this->list = db::get_cols($sql, $sqlBind);
            //$this->list  = ppo_members_peer::instance()->get_members($this->group['id'], false, $this->group);
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 16);
            $this->list  = $this->pager->get_list();
        }
    }
}