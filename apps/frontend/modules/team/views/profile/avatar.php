<?php

load::view_helper('group');
load::model('groups/groups');

function avatar($team)
{
    if ($team['photo_salt']) {
        return user_helper::team_photo(user_helper::team_photo_path($team['id'], 'p', $team['photo_salt']));
    }
    
    return group_helper::photo(0, 'p', false);
}

