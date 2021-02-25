<? $agitarray = user_helper::get_agimaterials() ?>

<div class="left" style="width: 35%;"><? include 'partials/agitleft.php' ?></div>
<style type="text/css">
table#ppstat {background-color:#cccccc;border-spacing:1px;font-size: 12px;}
#ppstat td {padding: 4px 2px 4px 2px;    background-color:#FFFFFF;padding: 2px;}
</style>
<div class="left ml10 mt10" style="width: 62%;">
	<? if ( !$list ) { ?>
		<div class="acenter fs11 quiet m10 p10"><?=t('Тут еще никого нет')?>...</div>
	<? } else { ?>
                <table class="acenter" id="ppstat"  cellspacing="1" cellpadding="0">

                <? if(!request::get('view') OR request::get('view')=='peoples'){ ?>
                    <tr>
                        <td><?=t('ФИО')?></td>
                        <? if(!request::get_int('type')){ ?>
                        <td><?=t('Тип')?></td>
                        <? } ?>
                        <td><?=t('Получил')?></td>
                        <td><?=t('Передал')?></td>
                        <td><?=t('Вручил')?></td>
                        <td><?=t('Остаток')?></td>
                    </tr>
                    <? if(!request::get_int('type')){ ?>
                    <tr>
                        <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
                    </tr>
                    <? } ?>
                    <? foreach ( $list as $id ) { ?>
                    <?
                    if(!request::get_int('period'))
                    {
                        if(!request::get_int('type'))
                            $user_stat = user_agitmaterials_peer::instance()->get_user_by_type($id);
                        else
                            $user_stat = user_agitmaterials_peer::instance()->get_user($id,request::get_int('type'));
                    }
                    else
                    {
                        if(!request::get_int('type'))
                            $user_stat = user_agitmaterials_log_peer::instance()->get_user_by_type($id,$sql);
                        else
                            $user_stat = user_agitmaterials_log_peer::instance()->get_user($id,request::get_int('type'),$sql);
                    }
                    $user_data = user_data_peer::instance()->get_item($id);
                    ?>
                        <? if(request::get_int('type')){ ?>
                        <tr>
                            <td class="fs11">
                                <a href="/profile/desktop_edit?id=<?=$id?>&tab=information">
                                    <?=user_helper::full_name($id,false,array('class'=>'bold'),false)?>
                                </a>
                                <br/>
                                <? if ( $user_data['country_id'] ) { ?>
                                    <? if ( $user_data['region_id'] ) { ?>
                                        <? $region = geo_peer::instance()->get_region($user_data['region_id']) ?>
                                        <?=$region['name_' . translate::get_lang()]?>
                                    <? } ?>
                                    <? if ( $user_data['city_id'] ) { ?>
                                        <? $city = geo_peer::instance()->get_city($user_data['city_id']) ?>
                                        /<?=$city['name_' . translate::get_lang()]?>
                                    <? } ?>
                                <? } ?>
                            </td>
                            <td><?=intval($user_stat['receive'])?></td>
                            <td><?=intval($user_stat['given'])?></td>
                            <td><?=intval($user_stat['presented'])?></td>
                            <td>
                                <? $left = intval($user_stat['receive']) - intval($user_stat['given']) - intval($user_stat['presented']) ?>
                                <?=($left>0)?$left:0?>
                            </td>
                        </tr>
                        <? }else{ ?>
                        <? $num = 0 ?>
                        <? foreach($user_stat as $stat){ ?>
                            <tr>
                                <? if(!$num){ ?>
                                <td class="fs11" style="vertical-align:middle" rowspan="<?=count($user_stat)?>">
                                    <a href="/profile/desktop_edit?id=<?=$id?>&tab=information">
                                        <?=user_helper::full_name($id,false,array('class'=>'bold'),false)?>
                                    </a>
                                    <br/>
                                    <? if ( $user_data['country_id'] ) { ?>
                                        <? if ( $user_data['region_id'] ) { ?>
                                            <? $region = geo_peer::instance()->get_region($user_data['region_id']) ?>
                                            <?=$region['name_' . translate::get_lang()]?>
                                        <? } ?>
                                        <? if ( $user_data['city_id'] ) { ?>
                                            <? $city = geo_peer::instance()->get_city($user_data['city_id']) ?>
                                            /<?=$city['name_' . translate::get_lang()]?>
                                        <? } ?>
                                    <? } ?>
                                </td>
                                <? } ?>
                                <td><?=$agitarray[$stat['type']]?></td>
                                <td><?=intval($stat['receive'])?></td>
                                <td><?=intval($stat['given'])?></td>
                                <td><?=intval($stat['presented'])?></td>
                                <td>
                                    <? $left = intval($stat['receive']) - intval($stat['given']) - intval($stat['presented']) ?>
                                    <?=($left>0)?$left:0?>
                                </td>
                            </tr>
                            <? $num++ ?>
                        <? } ?>
                        <tr>
                            <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
                        </tr>
                    <? }} ?>
                <? }elseif(request::get('view')=='regions'){ ?>
                    <tr>
                        <td><?=t('Регион')?></td>
                        <td><?=t('Тип')?></td>
                        <td><?=t('Получено')?></td>
                        <td><?=t('Передано')?></td>
                        <td><?=t('Вручено')?></td>
                        <td><?=t('Остаток')?></td>
                    </tr>
                    <tr>
                        <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
                    </tr>
                    <? foreach ( $list as $k=>$v ) { ?>
                        <? if($k){ ?>
                        <? $region = geo_peer::instance()->get_region($k) ?>
                        <? $num = 0 ?>
                        <? foreach($v as $key=>$val){ ?>
                            <tr>
                                <? if(!$num){ ?>
                                <td class="fs11" style="vertical-align:middle" rowspan="<?=count($v)?>"><?=$region['name_' . translate::get_lang()]?></td>
                                <? } ?>
                                <td><?=$agitarray[$key]?></td>
                                <td><?=intval($val['receive'])?></td>
                                <td><?=intval($val['given'])?></td>
                                <td><?=intval($val['presented'])?></td>
                                <td>
                                    <? $left = intval($val['receive']) - intval($val['given']) - intval($val['presented']) ?>
                                    <?=($left>0)?$left:0?>
                                </td>
                            </tr>
                            <? $num++ ?>
                        <? }} ?>
                        <tr>
                            <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
                        </tr>
                    <? } ?>
                <? }elseif(request::get('view')=='types'){ ?>
                    <tr>
                        <td><?=t('Тип')?></td>
                        <td><?=t('Получено')?></td>
                        <td><?=t('Передано')?></td>
                        <td><?=t('Вручено')?></td>
                        <td><?=t('Остаток')?></td>
                    </tr>
                    <? foreach ( $list as $k=>$v ) { ?>
                    <tr>
                        <td class="fs11"><?=$agitarray[$k]?></td>
                        <td><?=intval($v['receive'])?></td>
                        <td><?=intval($v['given'])?></td>
                        <td><?=intval($v['presented'])?></td>
                        <td>
                            <? $left = intval($v['receive']) - intval($v['given']) - intval($v['presented']) ?>
                            <?=($left>0)?$left:0?>
                        </td>
                    </tr>
                    <? } ?>
                <? } ?>
                </table>
		<div class="bottom_line_d mb10"></div>
                <div class="left">
                    <a href="javascript:;" id="get_print"><img src="http://shevchenko.ua/templates/images/icons/print.png" alt="print"></a>
                </div>
                <? if($pager){ ?>
                    <div class="right pager"><?=pager_helper::get_full($pager)?></div>
                <? } ?>
	<? } ?>

</div>

<div id="search_form_holder" class="hide">
    <table class="srch fs12">
    <tr>
        <td style="padding:40px 10px 20px 10px"><input type="radio" name="all" value="0" checked="checked"/><?=t('Печатать текущую страницу')?></td>
        <td style="padding:40px 10px 20px 10px"><input type="radio" name="all" value="1"/><?=t('Печатать все страницы')?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="button" class="button" id="print_on" value="<?=t('Печатать')?>" onclick="printon();"/>
            <input type="button" class="button_gray" id="print_off" value="<?=t('Назад')?>" onclick="printoff();"/>
        </td>
    </tr>
    </table>
</div>

<script type="text/javascript">
$(document).ready(function($){
    $('#get_print').click(function(){
        Popup.show();
        Popup.setHtml($('#search_form_holder').html());
        $('#popup_box').css({'left':parseInt($('#popup_box').css('left'))-200+'px'});
    });
});
function printoff(){
    $('#popup_box').hide();
}
function printon(){
    var str = '&print=1';
    $('#popup_box').find('input[type="checkbox"]:checked').each(function(){
        str += '&'+$(this).attr('name')+'=1';
    });
    $('#popup_box').find('input[type="radio"]:checked').each(function(){
        if($(this).val()==1)
            str += '&'+$(this).attr('name')+'=1';
    });
    window.location = "<?=$_SERVER['REQUEST_URI']?>"+str;
}
</script>