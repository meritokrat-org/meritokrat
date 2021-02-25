<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<head>
	<title>Друк результатів пошуку</title>
	<style type="text/css">
	BODY
	{
		FONT-SIZE: 9pt;
		COLOR: black;
		FONT-FAMILY: Arial;
		BACKGROUND-COLOR: white
	}
	td
	{
		FONT-SIZE: 9pt;
		vertical-align:top;
	}
	</style>
</head>
<body>

    <div id="print"><a href="javascript:viod(0)" onclick="document.getElementById('print').style.display = 'none';print();">ДРУКУВАТИ</a></div>
    <? if(request::get_int('contacts')){ ?>
    <div>
        <? if(request::get('period_begin')){ ?><?=t('Период контакта')?>: <b><?=request::get('period_begin')?><?=(request::get('period_end'))?' - '.request::get('period_end'):''?></b>&nbsp;<? } ?>
        <? if(request::get_int('contact_who')){ ?><?=t('Кто')?>: <b><?=user_novasys_data_peer::get_who_contact(request::get_int('contact_who'))?></b>&nbsp;<? } ?>
        <? if(request::get_int('contact_type')){ ?><?=t('Тип контакта')?>: <b><?=user_helper::get_contact_type(request::get_int('contact_type'))?></b><? } ?>
    </div>
    <? } ?>
        <? if ( $found ) { ?>
                <? $num = 0 ?>
                <table width="800">
                <? foreach ( $users as $id ) { ?>
                
                <? $user_list_data=user_data_peer::instance()->get_item($id);
                if(!$user_list_data)
                                        continue;?>
                <? $num++ ?>
                <? $user_list_auth=user_auth_peer::instance()->get_item($id) ?>
                <? $user_list_sys=user_novasys_data_peer::instance()->get_item($id) ?>
                <tr>
                    
                    <td width="20"><?=$num?>.</td>

                    <? if($ft['ft']){ ?><td width="60"><?=user_helper::photo( $id, 'sm', array(), false )?></td><? } ?>
                    
                    <td>
                    <b>id<?=$id?></b>
                    <? if($ft['nm']){ ?><b><?=$user_list_data['last_name'].' '.$user_list_data['first_name']?><? if($ft['ot']){ ?><?=' '.$user_list_data['father_name']?><? } ?></b>, <? } ?>
                    <? $city = geo_peer::instance()->get_city($user_list_data['city_id'])?>
                    <? if($ft['ct']){ ?><?=$city['name_' . translate::get_lang()]?>, <? } ?>
                    <? $region = geo_peer::instance()->get_region($user_list_data['region_id'])?>
                    <? if($ft['rg']){ ?><?=$region['name_' . translate::get_lang()]?>, <? } ?>
                    <? if($ft['sg']){ ?><?=$user_list_data['location']?>, <? } ?>
                    <? if($ft['br']){ ?><?=$user_list_data['birthday']?>, <? } ?>
                    <? if($ft['em']){ ?><?=$user_list_data['email'] ? $user_list_data['email'] : $user_list_auth['email']?>, <? } ?>
                    <? if($ft['ph']){ ?><?=$user_list_data['mobile'] ? $user_list_data['mobile'] : ($user_list_data['phone'] ? $user_list_data['phone'] : ($user_list_data['work_phone'] ? $user_list_data['work_phone'] : ''))?>, <? } ?>
                    <? if($ft['fn']){ ?><?=user_helper::get_func($id)?>, <? } ?>
                    <? if($ft['st']){ ?><?=user_auth_peer::get_status($user_list_auth['status'])?>, <? } ?>
                    <? if($ft['hd']){ ?><?=user_auth_peer::get_hidden_type($user_list_auth['hidden_type'],$user_list_auth['type'])?>, <? } ?>
                    <? if($ft['at']){ ?><?=($user_list_auth['active'])?t('Активированный'):t('Неактивированный')?>, <? } ?>
                    <? if($ft['rt']){ ?><?=($user_list_auth['activated_ts'])?t('активный с').': '.date_helper::get_format_date($user_list_auth['activated_ts']):''?>, <? } ?>
                    <? if($ft['pc']){ ?><?=($user_list_auth['created_ts'])?t('cоздан').': '.date_helper::get_format_date($user_list_auth['created_ts']):''?>, <? } ?>
                    <? if($ft['rf']){ ?><?=user_auth_peer::get_from($user['from'])?><? } ?>
                    </td>

                </tr>
                <? if(request::get_int('contacts')){ ?>
                    <? 
                        if($ft['cd'])$contacts = user_contact_peer::instance()->get_user($user_list_data['user_id'],$begin,$ends);
                        else $contacts = user_contact_peer::instance()->get_user($user_list_data['user_id']);
                    ?>
                    <? foreach($contacts as $c){ ?>
                    <? $res = user_contact_peer::instance()->get_item($c) ?>
                    <tr>
                    <td width="20"></td>
                        <td <?=($ft['ft'])?'colspan="2"':''?>>
                        <?=date("d.m.Y",$res['date'])?>, <?=user_helper::get_contact_type($res['type'])?>, <?=user_novasys_data_peer::get_who_contact($res['who'])?>
                        <? if($ft['cn']){ ?><?='<br/>'.stripslashes(htmlspecialchars($res['description']))?><? } ?>
                        </td>
                    </tr>
                <? }} ?>
                <? } ?>
                </table>
	<? } else { ?>
		<div class="screen_message acenter quiet"><?=t('Ничего не найдено')?></div>
	<? } ?>
</body>
</html>