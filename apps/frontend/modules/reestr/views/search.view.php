<?
$to = request::get_int('to');
$order = request::get('order');
if(!$page = request::get_int('page'))
    $num=1;
else
    $num = ($page-1)*$limit+1;
?>
<style>
    #left{display:none}
    table#searchresults{border:1px solid gray;border-collapse: collapse}
    #searchresults th{text-align:center;vertical-align:middle;font-size:11px;padding:1px 10px;background:#913D3E;color:#fc6;border:1px solid gray;}
    #searchresults th a{color:#fc6;}
    #searchresults td{text-align:center;vertical-align:middle;font-size:11px;color:black;border:1px solid gray;padding:1px 5px;}
    #searchresults th a{text-decoration:underline}
    .sub_menu a{text-decoration:none}
    .sub_menu a.clicked{color:grey}
    .sortable th b {background:url(/static/images/icons/sort.gif) 0 center no-repeat; cursor:pointer; padding-left:10px}
    .sortable .desc, .sortable .asc {background:#6f191C;}
    .sortable .desc b {background:url(/static/images/icons/desc.gif) 0 center no-repeat; cursor:pointer; padding-left:18px}
    .sortable .asc b {background:url(/static/images/icons/asc.gif) 0  center no-repeat; cursor:pointer; padding-left:18px}
    .sortable th a:hover {color:#EAEAEA}
    .sortable .evenrow td {background:#F7F7F7}
    .sortable .oddrow td {background:#F2F2F2}
    .sortable td.evenselected {background:#F2F2F2}
    .sortable td.oddselected {background:#EAEAEA}
</style>

<div class="sub_menu mt10 mb5" style="width:1000px">
    <a style="text-decoration:underline" href="/zayava/list"><?=t('Заявления')?></a>
    <a style="text-decoration:underline" href="/reestr"><?=t('Реестр')?></a>
    <? if(in_array(session::get_user_id(), array(2,5,8,1360,2546,3949))){ ?>
    <a style="text-decoration:underline" href="/reestr/payments">*<?=t('Взносы')?></a>
    <? } ?>
    <b class="right fs12">*<?=t('Расширенный поиск')?></b>
    <? if((intval(db_key::i()->get('schanger'.session::get_user_id())) OR in_array(session::get_user_id(),array(2,4,5,29,1299,1360,3949)))){ ?>
        <a class="right" style="text-decoration:underline" href="javascript:;" onclick="Application.doMembership()">*<?=t('Решение о принятии')?></a>
    <? } ?>
</div>

<div style="width:1000px">
<h1 class="column_head mb10" style="background: url(/static/images/common/bg-header.jpg) repeat-x 0 -75px;height:21px">
    <?=t('Реестр членов МПУ')?> &rarr; <?=t('Расширенный поиск')?>
    <? if(session::has_credential('admin')){ ?>
    <a class="right" href="/admin">*<?=t('Админка')?></a>
    <? } ?>
</h1>
</div>

<div class="mt5 mb10 form_bg" style="width:1000px">

    <form method="GET" action="/reestr/search">
        <input type="hidden" name="status" value="<?=request::get_int('status',1)?>" />
        <table id="searchform">
            <tr>
                <td class="aright fs11" width="300"><?=t('Имя')?></td>
                <td>
                        <input name="first_name" style="width:194px;" class="text" type="text" value="<?=addslashes(htmlspecialchars(request::get_string('first_name')))?>" />
                </td>
            </tr>
            <tr>
                <td class="aright fs11" width="300"><?=t('Фамилия')?></td>
                <td>
                        <input name="last_name" style="width:194px;" class="text" type="text" value="<?=addslashes(htmlspecialchars(request::get_string('last_name')))?>" />
                </td>
            </tr>
            <tr id="region_row">
                    <td class="aright fs11"><?=t('Регион')?></td>
                    <td>
                    <? if (request::get_int('country',0)<2)
                        {
                        $regions = geo_peer::instance()->get_regions(1);
                            $regions['']='&mdash;';
                            ksort($regions);
                      }
                      else   $regions = array(""=>"&mdash;"); ?>
                    <?=tag_helper::select('region',$regions , array('use_values' => false,'style'=>'width:200px;', 'value' => request::get_int('region'), 'id'=>'region', 'rel'=>t('Выберите регион'), )); ?>
                    </td>
            </tr>
            <tr>
                    <td class="aright fs11"><?=t('Город/Район')?></td>
                    <td>
                    <? if (request::get_int('region')) $cities = geo_peer::instance()->get_cities(request::get_int('region'));
                       $cities['']='&mdash;';
                       ksort($cities);
                    ?>
                    <?=tag_helper::select('city', $cities , array('use_values' => false,'style'=>'width:200px;', 'value' => request::get_int('city'), 'id'=>'city', 'rel'=>t('Выберите город/район'))); ?>
                    </td>
            </tr>
            <tr>
                <td class="aright fs11" width="300"><?=t('Вступительный взнос')?></td>
                <td>
                        <?=tag_helper::select('vv',array('&mdash;',t('уплачен и подтвержден или отказ от уплаты'),t('не уплачен')),array('value'=>request::get_int('vv'),'style'=>'width:200px'))?>
                </td>
            </tr>
            <tr>
                <td class="aright fs11" width="300"><?=t('Решение о приеме')?></td>
                <td>
                        <input name="invnumber" style="width:194px;" class="text" type="text" value="<?=addslashes(htmlspecialchars(request::get_string('invnumber')))?>" />
                </td>
            </tr>
            <tr>
                <td class="aright fs11" width="300"><?=t('Партбилет')?></td>
                <td>
                        <?=tag_helper::select('kvm',array('&mdash;',t('изготовлен'),t('не изготовлен')),array('value'=>request::get_int('kvm'),'style'=>'width:105px'))?>
                        &nbsp;
                        <?=tag_helper::select('kvg',array('&mdash;',t('вручен'),t('не вручен')),array('value'=>request::get_int('kvg'),'style'=>'width:85px'))?>
                </td>
            </tr>
            <tr>
                <td class="aright fs11" width="300"><?=t('№ партбилета')?></td>
                <td>
                        <input name="kvnumber" style="width:194px;" class="text" type="text" value="<?=addslashes(htmlspecialchars(request::get_string('kvnumber')))?>" />
                </td>
            </tr>
            <tr>
                <td class="aright fs11"><?=t('Тип анкеты')?></td>
                <td>
                    <select name="offline">
                        <option value="0">&mdash;</option>
                        <option value="2" <?=(request::get_int('offline')==2)?'selected':''?>>Он-лайн</option>
                        <option value="1" <?=(request::get_int('offline')==1)?'selected':''?>>Офф-лайн</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="aright fs11"><?=t('Оригинал заявления')?></td>
                <td>
                    <select name="real_app">
                        <option value="0">&mdash;</option>
                        <option value="2" <?=(request::get_int('real_app')==2)?'selected':''?>><?=t('Да')?></option>
                        <option value="1" <?=(request::get_int('real_app')==1)?'selected':''?>><?=t('Нет')?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="aright fs11"><?=t('Фото')?></td>
                <td>
                    <select name="hasphoto">
                        <option value="0">&mdash;</option>
                        <option value="2" <?=(request::get_int('hasphoto')==2)?'selected':''?>><?=t('Да')?></option>
                        <option value="1" <?=(request::get_int('hasphoto')==1)?'selected':''?>><?=t('Нет')?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="aright fs11" width="300"></td>
                <td>
                        <input type="submit" value="Пошук" class="button" name="submit" style="margin-right:5px" />
                </td>
            </tr>
            <? if(request::get('submit')){ ?>
            <tr>
                <td class="aleft fs11" width="300">
                    
                </td>
                <td class="quiet fs12">
                    <? if(!(is_array($list) && count($list)>0)){ ?>
                        <?=t('Ничего не найдено')?>
                    <? }else{ ?>
                        <?=t('Найдено')?>: <?=$total?>        
                        &nbsp;(<a href="<?=$_SERVER['REQUEST_URI']?>&excel=1"><?=t('Экспорт в excel')?></a> / <a href="<?=$_SERVER['REQUEST_URI']?>&photo=1"><?=t('Экспорт фото')?></a>)
                        <a href="javascript:;" id="get_print" class="dib icoprint"></a>
                    <? } ?>
                </td>
            </tr>
            <? } ?>
        </table>
        
    </form>

</div>

<div class="mt10">

<div id="tableholder" style="width:995px;overflow:auto;min-height:400px;">


<? if(is_array($list) && count($list)>0){ ?>

<table id="searchresults" class="sortable">
<tr>
    <th rowspan="2">№</th>
    <th rowspan="2"><?=t('Ф.И.О.')?></th>
    <th rowspan="2"><?=t('Регион')?></th>
    <th rowspan="2" class="ppo"><?=t('Текущее ППО')?></th>
    <th rowspan="2" class="ppolog"><?=t('История переходов')?></th>
    <th rowspan="2" class="dolg"><?=t('Долг')?></th>

    <th colspan="3" class="payments"><?=t('Уплаченные взносы')?></th>

    <th rowspan="2" class="recomend"><?=t('Рекомендация')?></th>
    <th rowspan="2" class="number"><?=t('№ партбилета')?></th>

    <th colspan="2" class="rishenna"><?=t('Решение о приеме')?></th>

    <th colspan="2" class="status"><?=t('Присвоение статуса')?></th>

    <th colspan="3" class="zayava"><?=t('Заявление о вступлении')?></th>
</tr>
<tr>
    <th class="payments"><?=t('Вступ')?>.</th>
    <th class="payments"><?=t('Ежемес')?>.</th>
    <th class="payments"><?=t('Благ')?>.</th>
    <th class="rishenna"><?=t('Дата')?></th>
    <th class="rishenna"><?=t('Номер')?></th>
    <th class="status"><?=t('Кто')?></th>
    <th class="status"><?=t('Когда')?></th>
    <th class="zayava"><?=t('Заявление')?></th>
    <th class="zayava"><?=t('Дата подачи')?></th>
    <th class="zayava"><?=t('Оригинал заявления')?></th>
</tr>

<? foreach($list as $id){ ?>

<?
$udata = user_data_peer::instance()->get_item($id);
$membership = user_membership_peer::instance()->get_user($id);
$ppomember = ppo_members_peer::instance()->get_ppo($id);
$ppohistory = ppo_members_history_peer::instance()->get_member_history($id);
$curppo = ppo_peer::instance()->get_user_ppo($id);
$statuslog = user_status_log_peer::instance()->get_last($id,20);
$zayava = user_zayava_peer::instance()->get_user_zayava($id);
$recommend = user_recommend_peer::instance()->get_recommenders($id,20);
$payments = user_payments_peer::instance()->get_total($id);
?>

<tr>
    <td><?=$num?></td>
    <td><?=user_helper::full_name($id,true,array(),false)?></td>
    <td>
        <? if($udata['region_id']){ ?>
            <? $region = geo_peer::instance()->get_region($udata['region_id']) ?>
            <?=$region['short']?>
            <?//$region['name_' . translate::get_lang()]?>
        <? } ?>
    </td>
    <td class="ppo"><?=($curppo)?'<a target="_blank" href="http://'.conf::get('server').'/ppo'.$curppo['id'].'/">'.$curppo['title'].'</a>':'&mdash;'?></td>
    <td class="ppolog">
        <?
        if(count($ppohistory))
        {
            foreach($ppohistory as $hist)
            {
                $pop = ppo_peer::instance()->get_item($hist['group_id']);
								if($pop["category"] != 1)
									continue;
                ?>
                <?=date('d.m.Y',$hist['date_start'])?> - <?=t('Вступление')?> в <?=$pop['title']?> <?=($pop['reason'])?'('.$pop['reason'].')':''?>
                <br/>
                <?
            }
        }
        else
        {
            echo '&mdash;';
        }
        ?>
    </td>
    <td class="dolg"><?=$membership['debt']?></td>
    <td class="payments"><?=($payments[1])?$payments[1]:0?></td>
    <td class="payments"><?=($payments[2])?$payments[2]:0?></td>
    <td class="payments"><?=($payments[3])?$payments[3]:0?></td>
    <td class="recomend">
        <? if(count($recommend)){ ?>
        <? $arr = array() ?>
        <? foreach($recommend as $id){ ?>
            <? $arr[] = user_helper::full_name($id,true,array(),false) ?>
        <? } ?>
        <?=(count($arr)>0)?implode(', ',$arr):'&mdash;'?>
        <? unset($arr) ?>
        <? } ?>
    </td>
    <td class="number"><?=($membership['kvnumber'])?$membership['kvnumber']:'&mdash;'?></td>
    <td class="rishenna"><?=($membership['invdate'])?date('d.m.Y',$membership['invdate']):'&mdash;'?></td>
    <td class="rishenna"><?=($membership['invnumber'])?$membership['invnumber']:'&mdash;'?></td>
    <td class="status"><?=($statuslog['id'])?user_helper::full_name($statuslog['who'],true,array(),false):'&mdash;'?></td>
    <td class="status"><?=($statuslog['id'])?date('d.m.Y',$statuslog['date']):'&mdash;'?></td>
    <td class="zayava"><?=($zayava['id'])?'<a target="_blank" href="/zayava&id='.$zayava['id'].'">id'.$zayava['id'].'</a>':'&mdash;'?></td>
    <td class="zayava"><?=($zayava['id'])?date('d.m.Y',$zayava['date']):'&mdash;'?></td>
    <td class="zayava">
        <input type="checkbox" <?=(session::has_credential('admin')) ? "" : ' disabled '?>  name="real_app" <?=($zayava['real_app']) ? ' checked="1"' : ''?> value="real_app"  rel="<?=$zayava['user_id']?>"/>
    </td>
</tr>

<? unset($curppo) ?>
<? $num++;} ?>

</table>

<? } ?>

</div>

<? if($total>$limit){ ?>
<div style="width:1000px">
    <div class="left ml5 mt10 fs11 quiet">
        <?=t('Записей на странице')?>:
        <?=tag_helper::select('limit',array('10'=>10,'25'=>25,'50'=>50,'100'=>100),array('id'=>'limit','value'=>db_key::i()->get('reestr_'.session::get_user_id().'_limit')))?>
    </div>
    <div class="left mt10 fs11 quiet" style="margin-left:290px">
        <?=t('Участников')?>: <?=$total?> &nbsp;&nbsp;&nbsp; <?=t('Страниц')?>: <?=ceil($total/$limit)?>
    </div>
    <div class="right pager mr5 mt10"><?=pager_helper::get_long($pager)?></div>
</div>
<? } ?>

<div id="search_form_holder" class="hide">
    <table class="srch fs12">
    <tr>
        <td class="aleft"><input type="checkbox" name="fio" value="1" checked="checked"/><?=t('Ф.И.О.')?></td>
        <td class="aleft"><input type="checkbox" name="rec" value="1"/><?=t('Рекомендация')?></td>
    </tr>
    <tr>
        <td class="aleft"><input type="checkbox" name="reg" value="1"/><?=t('Регион')?></td>
        <td class="aleft"><input type="checkbox" name="num" value="1"/><?=t('№ партбилета')?></td>
    </tr>
    <tr>
        <td class="aleft"><input type="checkbox" name="ppo" value="1"/><?=t('Текущее ППО')?></td>
        <td class="aleft"><input type="checkbox" name="ris" value="1"/><?=t('Решение о принятии')?></td>
    </tr>
    <tr>
        <td class="aleft"><input type="checkbox" name="his" value="1"/><?=t('История переходов')?></td>
        <td class="aleft"><input type="checkbox" name="sta" value="1"/><?=t('Присвоение статуса')?></td>
    </tr>
    <tr>
        <td class="aleft"><input type="checkbox" name="dol" value="1"/><?=t('Долг')?></td>
        <td class="aleft"><input type="checkbox" name="zay" value="1"/><?=t('Заявление о вступлении')?></td>
    </tr>
    <tr>
        <td class="aleft"><input type="checkbox" name="vne" value="1"/><?=t('Уплаченные взносы')?></td>
        <td class="aleft"><input type="checkbox" name="all" value="1" checked="checked"/><b><?=t('Печать всех результатов')?></b></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="button" class="button" id="print_on" value="<?=t('Печатать')?>" onclick="printon();"/>
            <input type="button" class="button_gray" id="print_off" value="<?=t('Назад')?>" onclick="printoff();"/>
        </td>
    </tr>
    </table>
</div>

</div>
<?if(session::has_credential('admin')) {?>
    <script>
        $('input[name="real_app"]').change(function(){
            if($(this).attr('checked')) real_app=2;
            else real_app=1;
            $.ajax({
                type: 'post',
                url: '/zayava/index',
                data: {
                   has_real: real_app,
                   id: $(this).attr('rel')
                },
                success: function(data) {
                    resp = eval("("+data+")");
                    $('#resp'+resp.success).fadeIn(300,function(){$(this).fadeOut(1000)});
                }
            });
        });
    </script> 
<?}?>
<script type="text/javascript">
jQuery(document).ready(function($){

    $('.sub_menu:last a').click(function(){
        if(!$(this).hasClass('clicked')){
            $('#tableholder').find('.'+$(this).attr('id')).hide();
            $(this).addClass('clicked');
        }else{
            $('#tableholder').find('.'+$(this).attr('id')).show();
            $(this).removeClass('clicked');
        }
    });

    $('tr:even').addClass('evenrow');
    $('tr:odd').addClass('oddrow');

    $('#get_print').click(function(){
        Popup.show();
        Popup.setHtml($('#search_form_holder').html());
        $('#popup_box').css({'left':parseInt($('#popup_box').css('left'))-100+'px'});
    });

    $('#limit').change(function(){
        $.post('/reestr/settings',{'key':'limit','value':$(this).val()},function(){
            window.location = window.location.href+'&page=1';
        });
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
    window.location = "<?=$_SERVER['REQUEST_URI']?>"+str;
    }
</script>