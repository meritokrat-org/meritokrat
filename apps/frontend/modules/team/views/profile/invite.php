<?php

function invite($team)
{
    $zz = new stdClass();
    $zz->getTeamId = function () use ($team) {
        return $team['id'];
    };
    $zz->phrase = function ($phrase) {
        return t($phrase);
    };

    return <<< EOL
<a href="javascript:void(0);" onclick="Application.inviteItem('team', 5, {$zz->getTeamId->__invoke()})" class="share mb5 ml15">
    <span class="fs18">{$zz->phrase->__invoke('Пригласить')}</span>
</a>
EOL;
}