<?php

function member($id)
{
    $user = user_auth_peer::instance()->get_item($id);
    $userStatus = user_auth_peer::get_status($user['status']);

    $lead = new stdClass();
    $lead->fullName = user_helper::full_name($id);
    $lead->avatar = user_helper::photo($id, 's', array('width' => '100%'), true, 'user', '', false, false);
    $lead->status = $userStatus > 0
        ? <<<EOF
<span class="cgray">$userStatus</span>
EOF
        : null;

    return $lead;
}

/**
 * @param $team
 * @return string|void
 */
function members($team)
{
    if($team['category'] != 1){
        return;
    }

    $members = team_members_peer::instance()->get_members($team['id'], false, $team);

    $list = [];
    foreach ($members as $uid) {
        $member = member($uid);
        $list[] = <<< EOL
<div class="pane_item team_item" style="width: 33%; float: left; padding: 5px">
    <div>
        <div class="left" style="width: 40px">{$member->avatar}</div>
        <div class="left ml10 fs11" style="width: 101px">
            {$member->fullName}
            {$member->status}
        </div>
        <div class="clear"></div>
    </div>
</div>
EOL;
    }

    $list = implode('', $list);
    $t = ['allMembers' => t('всі учасники')];

    return <<< EOL
<div class="fs11" style="background-color: #fafafa; padding: 7px 12px">
    <a href="/team/members?id={$team['id']}">{$t['allMembers']} &rarr;</a>
</div>
<div class="pcontent_pane team_content">
    {$list}
    <div class="clear"></div>
</div>
EOL;
}