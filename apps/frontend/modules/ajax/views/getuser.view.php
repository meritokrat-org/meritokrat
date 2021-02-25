<table cellspacing="0" cellpadding="0" style="width:750px">
    <tr>
        <td style="width:90px"><?=user_helper::photo($user['user_id'], 'r', array(),false)?></td>
        <td><b style="font-size:20px"><?=user_helper::full_name($user['user_id'],false)?></b>, <?=user_helper::geo( $user['user_id'] )?><?=$user_list_data['location'] ? ' ,'.$user_list_data['location'] : ''?></td>
        <td style="width:35%">
            <?=user_auth_peer::get_status($user_list_auth['status'])?>
            <br/>
            <?=$user_list_data['mobile'] ? $user_list_data['mobile'] : ($user_list_data['home_phone'] ? $user_list_data['home_phone'] : ($user_list_data['work_phone'] ? $user_list_data['work_phone'] : ($user_list_data['phone'] ? $user_list_data['phone'] : '')))?>, <?=$user_list_auth['email']?>
        </td>
        <td style="width:10px;border:none" class="del"><a href="javascript:;" onclick="$(this).parent().parent().parent().remove()">x</a></td>
    </tr>
</table>
