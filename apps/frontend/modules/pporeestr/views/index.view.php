<?
$to = request::get_int('to');
$order = request::get('order');
if(!$page = request::get_int('page'))$num=1;
else $num = ($page-1)*$limit+1;
for ($i = 1; $i <= 15; $i++) {
   $ppofield[$i] = db_key::i()->get('pporeestr_'.session::get_user_id().'_'.$i); 
}
?>

<style>
    #left{display:none}
    table.sortable{border:1px solid gray;border-collapse: collapse}
    th{text-align:center;vertical-align:middle;font-size:11px;padding:1px 10px;background:#913D3E;color:#fc6;border:1px solid gray;}
    th a{color:#fc6;}
    .sortable td{text-align:center;vertical-align:middle;font-size:11px;color:black;border:1px solid gray;padding:1px 5px;}
    th a{text-decoration:underline}
    .sub_menu a{text-decoration:none}
    .sub_menu a.clicked{color:grey}
    .sortable th b {background:url(/static/images/icons/sort.gif) 0 center no-repeat; cursor:pointer; padding-left:10px}
    .sortable .desc, .sortable .asc {background:#6f191C;}
    .sortable .desc b {background:url(/static/images/icons/desc.gif) 0 center no-repeat; cursor:pointer; padding-left:10px}
    .sortable .asc b {background:url(/static/images/icons/asc.gif) 0  center no-repeat; cursor:pointer; padding-left:10px}
    .sortable th a:hover {color:#EAEAEA}
    .sortable .evenrow td {}
    .sortable .oddrow td {}
    .sortable td.evenselected {background:#F2F2F2}
    .sortable td.oddselected {background:#EAEAEA}
</style>
<div class="sub_menu mt5 mb10" style="width:1000px">
    <a href="javascript:;" id="ppofield1" class="<?=($ppofield[1])?'clicked':''?>">Регiон/Назва ПО</a>
    <a href="javascript:;" id="ppofield2" class="<?=($ppofield[2])?'clicked':''?>"><?=t('МПО')?></a>
    <a href="javascript:;"  id="ppofield3" class="<?=($ppofield[3])?'clicked':''?>"><?=t('ППО')?></a>
    <a href="javascript:;"  id="ppofield4" class="<?=($ppofield[4])?'clicked':''?>">Голова</a>
    <a href="javascript:;" id="ppofield5"  class="<?=($ppofield[5])?'clicked':''?>">Секретар</a>
    <a href="javascript:;" id="ppofield6"  class="<?=($ppofield[6])?'clicked':''?>">Члени Ради</a>
    <a href="javascript:;" id="ppofield7"  class="<?=($ppofield[7])?'clicked':''?>">Ревізор</a>
    <a href="javascript:;" id="ppofield8"  class="<?=($ppofield[8])?'clicked':''?>">Кількість членів</a>
    <a href="javascript:;" id="ppofield9"  class="<?=($ppofield[9])?'clicked':''?>">Адреса</a>
    <a href="javascript:;" id="ppofield10"  class="<?=($ppofield[10])?'clicked':''?>">Уст.Збори</a>
    <a href="javascript:;" id="ppofield11"  class="<?=($ppofield[11])?'clicked':''?>">Затвердження</a>
    <a href="javascript:;" id="ppofield12"  class="<?=($ppofield[12])?'clicked':''?>">Легалізація</a>
    <a href="javascript:;" id="ppofield13"  class="<?=($ppofield[13])?'clicked':''?>">Оригінали</a>
    <a href="javascript:;" id="ppofield14"  class="<?=($ppofield[14])?'clicked':''?>">Свiдоцтво</a>
    <a href="javascript:;" id="ppofield15"  class="<?=($ppofield[15])?'clicked':''?>">Рiшення Голови про включ. в структуру</a>
    <a href="javascript:void(0)" onclick="doprint()" class="ml10 right icoprint"></a>
   </div>
<div class="mt10">

<div style="width:1000px">
<h1 class="column_head mb10" style="background: url(/static/images/common/bg-header.jpg) repeat-x 0 -75px;height:21px">
    <a href="/pporeestr"> <?=t('Реестр ПО')?></a>
    <? if(session::has_credential('admin')){ ?>
    <a class="right" href="/admin"><?=t('Админка')?></a>
    <? } ?>
</h1>
</div>



<? if(count($list)==0){ ?>

    <div class="screen_message acenter quiet">
        <?=t('Ничего не найдено')?>
        <br/>
        <a href="/pporeestr/"><?=t('Вернуться в реестр')?></a>
    </div>

<? }else{ ?>
    <div style="width:1000px"><div class="addit left">
    <a href="/ppo" class="mr15 <?=request::get_int('category')==1?'white':''?>"><?=t('Первичные')?> &nbsp;<?=(int)db::get_scalar("SELECT count(*) FROM ppo WHERE category=1 AND active=1")?></a>&nbsp; 
    <a href="/ppo/index?category=2" class="mr15<?=request::get_int('category')==2?'white':''?>"><?=t('Местные')?> &nbsp;<?=(int)db::get_scalar("SELECT count(*) FROM ppo WHERE category=2 AND active=1")?></a> &nbsp; 
    <a href="/ppo/index?category=3" class=" mr15<?=request::get_int('category')==3?'white':''?>"><?=t('Региональные')?> &nbsp;<?=(int)db::get_scalar("SELECT count(*) FROM ppo WHERE category=3 AND active=1")?></a>            
   
        </div>  <div class="right"> <a id="poreestrearch" href="javascript:{}"><?=t('Поиск')?></a></div></div>
    <div class="clear"></div>
    <div style="width:1000px" id="posearchform" class="mt5 mb10 form_bg <?=!request::get('submit')?'hidden':''?>">

    <form action="" method="GET">
        <table id="searchform">
            <tbody>
            <tr class="oddrow">
                <td width="300" class="aright fs11">Свiдоцтво (орiгiнал)</td>
                <td>
                    <select use_values="" name="dovidsdate" style="width:105px" value="0">
                        <option value="0">&mdash;</option>
                        <option <?=request::get_int('dovidsdate')==1?'selected=""':''?>  value="1">отримано</option>
                        <option <?=request::get_int('dovidsdate')==2?'selected=""':''?> value="2">неотримано</option>
                    </select>
                </td>
            </tr>
           <tr class="oddrow">
                <td width="300" class="aright fs11">Заява (орiгiнал)</td>
                <td>
                    <select use_values="" name="zayava" style="width:105px" value="0">
                        <option value="0">&mdash;</option>
                        <option <?=request::get_int('zayava')==1?'selected=""':''?>  value="1">виготов</option>
                        <option <?=request::get_int('zayava')==2?'selected=""':''?> value="2">не виготов</option>
                    </select>
                </td>
            </tr>
            <tr class="oddrow">
                <td width="300" class="aright fs11">Свiдоцтво(внутр)</td>
                <td>
                    <select use_values="" name="svidvig" style="width:105px" value="0">
                        <option value="0">&mdash;</option>
                        <option <?=request::get_int('svidvig')==1?'selected=""':''?>  value="1">виготовлено</option>
                        <option <?=request::get_int('svidvig')==2?'selected=""':''?> value="2">не виготовлено</option>
                    </select>
                    <select use_values="" name="svidvruch" style="width:105px" value="0">
                        <option value="0">&mdash;</option>
                        <option <?=request::get_int('svidvruch')==1?'selected=""':''?>  value="1">вручено</option>
                        <option <?=request::get_int('svidvruch')==2?'selected=""':''?> value="2">не вручено</option>
                    </select>
                </td>
            </tr>
            <tr class="oddrow">
                <td width="300" class="aright fs11">Рiшення Голови про включ. в структуру</td>
                <td>
                    <select use_values="" name="vkl" style="width:105px" value="0">
                        <option value="0">&mdash;</option>
                        <option <?=request::get_int('vkl')==1?'selected=""':''?>  value="1">виготов</option>
                        <option <?=request::get_int('vkl')==2?'selected=""':''?> value="2">не виготов</option>
                    </select>
                </td>
            </tr>
            <tr class="evenrow">
                <td width="300" class="aright fs11"></td>
                <td>
                        <input type="submit" style="margin-right:5px" name="submit" class="button" value="Пошук">
                </td>
            </tr>
                    </tbody></table>
        
    </form>

</div>
    <div class="clear"></div>
    <table style="width: 995px; margin: 0;">
        <tr>
            <td class="acenter">
                <img src="/static/images/icons/up.png" style="width: 50px; height: 25px;" alt="up" id="scroll_button_up" class="pointer" >
                <img src="/static/images/icons/down.png" style="width: 50px; height: 25px;" alt="down" id="scroll_button_down" class="pointer ml10">
            </td>
        </tr>
    </table>
    <div id="tableholder" style="width:995px;overflow:auto;min-height:500px">
<table class="sortable">
    
<tr>
    <th rowspan="2" class="ppofield1" width="300" class="name">Регiон/Назва ПО</th>
<th rowspan="2" class="ppofield2"  width="5"><?=t('МПО')?></th>
<th rowspan="2" class="ppofield3"  width="5"><?=t('ППО')?></th>
<th rowspan="2" class="ppofield4"  width="90">Голова</th>
<th rowspan="2" class="ppofield5"  width="90">Секретар</th>
<th rowspan="2" class="ppofield6"  width="150">Члени Ради</th>
<th rowspan="2" class="ppofield7"  width="90">Ревізор</th>
<th rowspan="2" class="ppofield8"  width="5">Кількість членів</th>
<th rowspan="2" class="ppofield9"  width="300">Адреса</th>
<th rowspan="2" class="ppofield10"  width="20">Уст.Збори</th>
<th width="100" class="ppofield11"  colspan="2">Затвердження</th>
<th width="100" class="ppofield12"  colspan="3">Легалізація</th>
<th width="100" class="ppofield13"  colspan="3">Оригінали</th>
<th width="100" class="ppofield14"  colspan="3">Свiдоцтво</th>
<th rowspan="2" class="ppofield15"  width="20">Рiшення Голови про включ. в структуру</th>
</tr>
<tr>
 <th  class="ppofield11"  width="10">№</th>   
 <th  class="ppofield11"  width="50">Дата</th>   
 <th class="ppofield12"  width="10">№</th>   
 <th class="ppofield12" width="50">Дата</th>   
  <th class="ppofield12" width="50">Копія свідоцтва видана</th>   
 <th class="ppofield13" width="50">Протокол</th>   
 <th class="ppofield13" width="50">Св-во / Довідка</th>  
 <th class="ppofield13" width="50">Заява</th>  
 <th class="ppofield14" width="50">№</th>  
 <th class="ppofield14" width="50">Виготовлення</th>  
 <th class="ppofield14" width="50">Вручення</th>  
</tr>
<? 
$row_id=0;
foreach($list as $id){
if(is_array($id))$ppo=$id;
else
$ppo = ppo_peer::instance()->get_item($id);
$glava_id = (int)ppo_members_peer::instance()->get_user_by_function(1,$ppo['id'],$ppo);
$secretar_id  = (int)ppo_members_peer::instance()->get_user_by_function(2,$ppo['id'],$ppo);
$revizor_id  = (int)ppo_members_peer::instance()->get_user_by_function(3,$ppo['id'],$ppo);
$members = ppo_members_peer::instance()->get_users_by_function(4,$ppo['id']);
if($ppo['category']==2){$ppo['ppocnt']=db::get_scalar("SELECT count(*) 
                          FROM ppo WHERE city_id=".$ppo['city_id']."
                      AND category=1 AND active=1");
$mpos[]=$ppo['city_id'];
}
$row_id++;
?>
<tr  id="row_<?=$row_id?>" 
 class="<?if($ppo['category']==1){?>rn<?=$ppo['city_id']?><?}?>
 <?if($ppo['category']==1){if(!in_array($ppo['city_id'],(array)$mpos)){?>reg<?=$ppo['region_id']?>
 <?}?><?=!request::get('submit')?'hidden':''?><?}else{?><?if($ppo['category']!=3){ echo 'reg'.$ppo['region_id']; if(!request::get('submit'))echo ' hidden';  }else echo 'rpotr';?><?}?>
 <?=$ppo['category']==2?'mpotr':''?>">
    <td <?if($ppo['category']==2){?>rel="<?=$ppo['city_id']?>"<?}else{?>rel="<?=$ppo['region_id']?>"<?}?>
        class="ppofield1 <?=$ppo['category']==2?'mpo':''?> 
        <?=$ppo['category']==3?'rpo':''?> aleft">   
        <div class="fs12 aleft left bold" style="width: 180px;"><?=$ppo['title']?></div>
            <?if($ppo['id']){?>
            <div class="right ppolnk"><a target="_blank" href="/ppo<?=$ppo['id']?>/<?=$ppo['number']?>" class="dib icoweblinkbullet">
            </a></div><?}?>
    </td>
    <td class="ppofield2" class="mpocnt">
        <?=$ppo['mpocnt']?>
    </td>
    <td class="ppofield3" class="ppocnt">
        <?=$ppo['ppocnt']?>
    </td>
        <td class="ppofield4">
        <?=$glava_id?user_helper::full_name($glava_id,true,array(),false,true,true):'-'?>
    </td>
        <td class="ppofield5">
        <?=$secretar_id?user_helper::full_name($secretar_id,true,array(),false,false):'-'?>
    </td>
    <td class="ppofield6">
      <?foreach ($members as $m):?>
          <?=user_helper::full_name($m, true, array(), false,false)?><br/>
        <?endforeach;?> 
    </td>
     <td class="ppofield7">
        <?=$revizor_id?user_helper::full_name($revizor_id,true,array(),false):'-'?>
    </td>
    <td class="ppofield8">
       <?if($ppo['id']){?>
        <a target="_blank" href="/ppo/members?id=<?=$ppo['id']?>">
            <?=count(ppo_members_peer::instance()->get_members($ppo['id'],false,$ppo))?>
        </a>
        <?}?>
    </td>
    <td class="ppofield9">
        <?=$ppo['adres']?$ppo['adres']:""?>
    </td>
    <td class="ppofield10">
    <?=$ppo['dzbori']?date("d-m-Y",$ppo['dzbori']):'-'?>
    </td>
    <td class="ppofield11">
        <?=$ppo['uhvalnum']?$ppo['uhvalnum']:"-"?>
    </td>
    <td class="ppofield11">
        <?=($ppo['duhval'])?date("d-m-Y",$ppo['duhval']):'-'?>
    </td>
    <td class="ppofield12">
        <?=$ppo['dovidnum']?$ppo['dovidnum']:"-"?>
    </td>
    <td class="ppofield12">
        <?=($ppo['doviddate'])?date("d-m-Y",$ppo['doviddate']):'-'?>
    </td>
    <td class="ppofield12">
        <?=$ppo['svidcopy']?'+':"-"?>
    </td>
     <td  class="ppofield13">
        <?=$ppo['protokolsdate']?'+':"-"?>
    </td>
     <td  class="ppofield13">
        <?=$ppo['dovidsdate']?'+':"-"?>
    </td>
     <td  class="ppofield13">
        <?=$ppo['zayava']?'+':"-"?>
    </td>
    <td  class="ppofield14">
        <?=$ppo['svidnum']?$ppo['svidnum']:""?>
    </td>
      <td  class="ppofield14">
        <?=$ppo['svidvig']?'+':"-"?>
    </td>
      <td  class="ppofield14">
        <?=$ppo['svidvruch']?'+':"-"?>
    </td>
    <td  class="ppofield15">
        <?if($ppo['vklnumber']!='' && $ppo['vkldate']>0) echo '+'; else echo "-";?>
    </td>
</tr>
<? $num++;} ?>

</table>
<? } ?>
</div>

</div>

<script type="text/javascript">
jQuery(document).ready(function($){
    _tablewidth();
    $('tr:even').addClass('evenrow');
    $('tr:odd').addClass('oddrow');

    var order = '<?=trim(addslashes(htmlspecialchars(request::get_string('order'))))?>';
    var to = parseInt('<?=request::get_int('to')?>');

    if(order!=''){
        var $obj = $('td.'+order);
        if($obj.length != 0){
            if(to){
                $('th.'+order).addClass('desc');
            }else{
                $('th.'+order).addClass('asc');
            }
            $('tr:even').find('td.'+order).addClass('evenselected');
            $('tr:odd').find('td.'+order).addClass('oddselected');
        }
    }

    $('.sub_menu:last a').click(function(){
        if(!$(this).hasClass('clicked')){
            $('#tableholder').find('.'+$(this).attr('id')).hide();
            $(this).addClass('clicked');
            var val = 1;
        }else{
            $('#tableholder').find('.'+$(this).attr('id')).show();
            $(this).removeClass('clicked');
            var val = 0;
        }
        _tablewidth();
    });

});
$('.rpo').click(function() { 
  if($('.reg'+$(this).attr('rel')).hasClass('hidden'))
      $('.reg'+$(this).attr('rel')).removeClass('hidden');
  else
      $('.reg'+$(this).attr('rel')).addClass('hidden');
  
//  $('.reg'+$(this).attr('rel')).slideToggle(0, function() {
//    // Animation complete.
//  });
});
$('.mpo').click(function() { 
    if($('.rn'+$(this).attr('rel')).hasClass('hidden'))
      $('.rn'+$(this).attr('rel')).removeClass('hidden');
    else
      $('.rn'+$(this).attr('rel')).addClass('hidden');
//  $('.rn'+$(this).attr('rel')).slideToggle(0, function() {
//    // Animation complete.
//  });
});
$("#poreestrearch").click(function() { 
  $('#posearchform').slideToggle(0, function() {
  return false;    
// Animation complete.
  });
});
function _tablewidth(){
    var width = 0;
    $('table.sortable tr:first').find('th:visible').each(function(){
        width += parseInt($(this).attr('width'));
    });
    if(width < 995)width = 995;
    $('table.sortable').css({'width':width});
}
    function doprint(){
        $('#additional, #footer, #header, #left, #comment_form, #comments, .column_head, #vote_pane, .actionpanel, .sub_menu, .addit, .ppolnk').remove();
        $('.root_container').css({width:'100%',margin:'none'});
        $('#tableholder').removeAttr("style");
        $('#leftcoll, div.addthis_toolbox').hide();
        $('.left').removeClass('left');
        window.print();
    }
    _hidden = [];
    
$('[id^="scroll_button_"]').click(function() {
        _direction = $(this).attr('id').replace('scroll_button_','');
        
        switch(_direction) {
            case 'up':
                $('[id^="row_"]:not(.hidden):first').each(function(){
                    _hidden.push($(this).attr('id').replace('row_',''));
                    $(this).addClass('hidden');
                    return;
                });
                break;
            case 'down':
                $('#row_'+_hidden.pop()).removeClass('hidden');
                break;

            }
});
        

</script>
<style>
    .rpo, .mpo{
        cursor: pointer;
    } 
   .mpotr{
           background-color: #CCFF99;
    }
    .rpotr{
        background:#EAEAEA;
    }
    .sub_menu a{
        margin-right: 7px
    }
</style>
