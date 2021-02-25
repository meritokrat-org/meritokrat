<?
if(!$page = request::get_int('page'))
    $num=1;
else
    $num = ($page-1)*30+1;
?>
<style>
    td{font-size:12px;color:black;}
</style>

<div class="mt10">

<h1 class="column_head"><?=t('Профили дубликаты')?></h1>
<table>
<tr>
    <th>№</th>
    <th style="text-align:left"><?=t('Возможный дубликат')?></th>
    <th style="text-align:left"><?=t('Исходный профиль')?></th>
</tr>
<? foreach($list as $item){ ?>

<? $first = user_data_peer::instance()->get_item($item['fst']) ?>
<? $firsta = user_auth_peer::instance()->get_item($item['fst']) ?>
<? $second = user_data_peer::instance()->get_item($item['scn']) ?>
<? $seconda = user_auth_peer::instance()->get_item($item['scn']) ?>

<tr>
    <td width="20"><?=$num?></td>
    
    <td>
        <a class="left mr15" href="<?=user_helper::profile_link($first['user_id'])?>">
            <?=tag_helper::image(user_helper::photo_path($first['user_id'], 't', 'user'), array(), context::get('image_server'));?>
        </a>
        <a href="<?=user_helper::profile_link($first['user_id'])?>">
            <?=$first['last_name'].' '.$first['first_name']?>
        </a><br/>
        <b>id<?=$first['user_id']?></b><br/>
        <b><?=t('Создан')?></b>: <?=date('d.m.Y',$firsta['created_ts'])?><br/>
        <?=($firsta['offline'])?'<b>'.t('Создатель').'</b>: '.user_helper::full_name($firsta['offline'],true,array(),false).'<br/>':''?>
        <a href="javascript:;" class="p5 button mr5" style="line-height:2.5" onclick="adminController.duplicateProfile('<?=$first['user_id']?>',2,this)"><?=t('Удалить')?></a>
        <a href="javascript:;" class="p5 button_gray" style="line-height:2.5" onclick="adminController.duplicateProfile('<?=$first['user_id']?>',1,this)"><?=t('Не дубль')?></a>
    </td>
    <td>
        <a class="left mr15" href="<?=user_helper::profile_link($second['user_id'])?>">
            <?=tag_helper::image(user_helper::photo_path($second['user_id'], 't', 'user'), array(), context::get('image_server'));?>
        </a>
        <a href="<?=user_helper::profile_link($second['user_id'])?>">
            <?=$second['last_name'].' '.$second['first_name']?>
        </a><br/>
        <b>id<?=$second['user_id']?></b><br/>
        <b><?=t('Создан')?></b>: <?=date('d.m.Y',$seconda['created_ts'])?><br/>
        <?=($seconda['offline'])?'<b>'.t('Создатель').'</b>: '.user_helper::full_name($seconda['offline'],true,array(),false).'<br/>':''?>
        <a href="javascript:;" class="p5 button mr5" style="line-height:2.5" onclick="adminController.duplicateProfile('<?=$second['user_id']?>',2,this)"><?=t('Удалить')?></a>
        <a href="javascript:;" class="p5 button_gray" style="line-height:2.5" onclick="adminController.duplicateProfile('<?=$second['user_id']?>',1,this)"><?=t('Не дубль')?></a>
    </td>
    
</tr>
<? $num++;} ?>
</table>

</div>
<div class="right pager"><?=pager_helper::get_full($pager)?></div>