<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<title>Друк статистики</title>
<head>
<style type="text/css">
BODY{FONT-SIZE: 9pt;COLOR: black;FONT-FAMILY: Arial;BACKGROUND-COLOR: white}
table#ppstat {font-size: 12px;border:1px solid black;border-collapse: collapse;}
#ppstat td {padding: 4px 2px 4px 2px;background-color:#FFFFFF;padding: 2px;text-align:center;vertical-align:middle;border:1px solid black;border-collapse: collapse;}
</style>
<script type="text/javascript" src="/static/javascript/jquery/jquery-1.4.2.js"></script>
</head>
<body>

<div id="print"><a href="javascript:void(0)" onclick="$('.tddel, .tdwho').hide();$('#print').hide();print();">ДРУКУВАТИ</a></div>

<table class="acenter" id="ppstat"  cellspacing="1" cellpadding="1">
<? foreach(user_helper::get_agimaterials() as $k=>$v){ ?>
    <? if(is_array($list[$k])){ ?>
        <tr>
            <td colspan="5"><?=$v?></td>
        </tr>
        <tr>
            <td><?=t('Дата')?></td>
            <td><?=t('Получил')?></td>
            <td><?=t('Передал')?></td>
            <td><?=t('Вручил')?></td>
            <td><?=t('Кому')?></td>
            <? if(session::get_user_id()==2546 || session::get_user_id()==1360){ ?><td class="tddel" width="20"></td><? } ?>
            <? if(session::has_credential('admin')){ ?><td class="tdwho"><?=t('Кто')?></td><? } ?>
        </tr>
        <? foreach($list[$k] as $val){ ?>
            <? $item = user_agitmaterials_log_peer::instance()->get_item($val) ?>
            <tr>
                <td><?=date("d.m.Y",$item['date'])?></td>
                <td><?=$item['receive']?><? $rec+=$item['receive'] ?></td>
                <td><?=$item['given']?><? $giv+=$item['given'] ?></td>
                <td><?=$item['presented']?><? $pre+=$item['presented'] ?></td>
                <td>
                    <?
                    if($item['profile'] && $item['profile']!=9999999)
                    {
                        echo '<a target="_blank" href="/profile/desktop_edit?id='.$item['profile'].'&tab=information">'.user_helper::full_name($item['profile'],false).'</a>';
                    }
                    elseif($item['profile']==9999999)
                    {
                        echo t('Расход офиса');
                    }
                    ?>
                </td>
                <? if(session::get_user_id()==2546 || session::get_user_id()==1360){ ?>
                    <td class="tddel">
                        <a href="javascript:;" rel="<?=$val?>">x</a>
                    </td>
                <? } ?>
                <? if(session::has_credential('admin')){ ?>
                    <td class="tdwho">
                    <? if($item['who'] && $item['who']!=$item['user_id']){ ?>
                        <a target="_blank" href="/profile-<?=$item['who']?>"><?=user_helper::full_name($item['who'],false)?></a>
                    <? } ?>
                    </td>
                <? } ?>
            </tr>
        <? } ?>
        <tr>
            <td><b><?=t('Всего')?></b></td>
            <td><b><?=$rec?></b></td>
            <td><b><?=$giv?></b></td>
            <td><b><?=$pre?></b></td>
            <td><b><?=t('Остаток')?>: <?=$rec-$giv-$pre?></b></td>
            <? if(session::get_user_id()==2546 || session::get_user_id()==1360){ ?><td class="tddel"></td><? } ?>
        </tr>
        <? unset($rec,$giv,$pre) ?>
    <? } ?>
<? } ?>
</table>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('.tddel a').click(function(){
        $.post('/results/agitation_user_delete',{'id':$(this).attr('rel')});
        $(this).parent().parent().remove();
    });
});
</script>

</body>
</html>