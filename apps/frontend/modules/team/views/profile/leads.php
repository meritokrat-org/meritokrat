<?php

load::model('user/user_auth');
load::model('team/members');
load::model('team/applicants');
load::model('team/news');

function lead($id)
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

function leads($team, $private = false)
{
    if ($private) {
        return '';
    }

    $types = ($team['category'] == 4
        ? [
            t('Начальник команды'),
            t('Руководитель оргмассового направления'),
            t('Руководитель агитационно-рекламного направления'),
            t('Руководитель информационно-аналитического направления'),
            t('Руководитель административного направления'),
            t('Главный юрисконсульт'),
            t('Бухгалтер'),
            t('Управляющий делами'),
            t('Начальник службы безопасности')
        ]
        : [
            t('Лидер'),
            t('Заместитель')
        ]);

    $team['glava_id'] = (int)team_members_peer::instance()->get_user_by_function(1, $team['id'], $team);
    $team['secretar_id'] = (int)team_members_peer::instance()->get_user_by_function(2, $team['id'], $team);

    $leads = [];
    for ($i = 1; $i <= ($team['category'] == 4 ? 9 : 2); $i++) {
        if (!($uid = (int)team_members_peer::instance()->get_user_by_function($i, $team['id'], $team)))
            continue;

        $lead = lead($uid);
        $leads[] = <<< EOF
<div class="fs11 p10 mb5 box_content">
    <div>
        <div class="left" style="width: 50px">
            {$lead->avatar}
        </div>
        <div class="left ml10" style="width: 147px">
            {$lead->fullName}<br/>
            {$types[$i - 1]}<br/>
            {$lead->status}
        </div>
        <div class="clear"></div>
    </div>
</div>
EOF;
    }

    if(!(count($leads) > 0)){
        return null;
    }

    $leads = implode('', $leads);

    $title = t('Руководство');

    return <<< EOF
<div class="column_head_small mt15">{$title}</div>
{$leads}
EOF;
}


