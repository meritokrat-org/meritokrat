<?php

include_once 'profile/avatar.php';
include_once 'profile/leads.php';
include_once 'profile/members.php';
include_once 'profile/invite.php';

echo json_encode([
    'team' => $team,
    'title' => $team['title'],
    'avatar' => avatar($team),
    'invite' => invite($team),
    'leads' => leads($team),
    'members' => members($team),
]);