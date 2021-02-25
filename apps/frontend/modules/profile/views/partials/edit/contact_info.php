
<form id="contact_info_form" class="form mt10 hidden">
<? if ( session::has_credential('admin') ) { ?>
    <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
<? } ?>
<input type="hidden" name="type" value="contact_info"/>

<table width="100%" class="fs12">

<? if(count($user_contact)>0){ ?>
<? foreach($user_contact as $cont){ ?>
    <? $res = user_contact_peer::instance()->get_item($cont) ?>
        <tr class="itemi">
            <td class="aright"><?=t('Дата')?></td>
            <td>
                <input name="idate[]" rel="<?=t('Заполните род занятий')?>" class="text idate" type="text" value="<?=date("d/m/Y",$res['date'])?>"/>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"><?=t('Тип')?></td>
            <td>
                <?=tag_helper::select('types[]', user_helper::get_contact_types(), array('use_values' => false, 'value' => $res['type']))?>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"><?=t('Кто')?></td>
            <td>
                <?=tag_helper::select('who[]', user_novasys_data_peer::get_who_contacts(), array('use_values' => false, 'value' => $res['who']))?>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"><?=t('Содержание контакта')?></td>
            <td>
                <textarea name="description[]"><?=stripslashes(htmlspecialchars($res['description']))?></textarea>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"></td>
            <td>
                <input type="button" class="button_gray idel" value="<?=t('Удалить контакт')?>"/>
            </td>
        </tr>
    <? } ?>
<? }else{ ?>
        <tr class="itemi">
            <td class="aright"><?=t('Дата')?></td>
            <td>
                <input name="idate[]"  class="text idate" type="text" value="<?=date("d/m/Y")?>"/>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"><?=t('Тип')?></td>
            <td>
                <?=tag_helper::select('types[]', user_helper::get_contact_types(), array('use_values' => false, 'value' => 2))?>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"><?=t('Кто')?></td>
            <td>
                <? session::get_user_id()==4 ? $who_contacted=2 : $who_contacted=''; ?>
                <?=tag_helper::select('who[]', user_novasys_data_peer::get_who_contacts(), array('use_values' => false, 'value'=>$who_contacted))?>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"><?=t('Содержание контакта')?></td>
            <td>
                <textarea name="description[]"></textarea>
            </td>
        </tr>
        <tr class="itemi">
            <td class="aright"></td>
            <td>
                <input type="button" class="button_gray idel" value="<?=t('Удалить контакт')?>"/>
            </td>
        </tr>
<? } ?>
    <tr id="cmoreholder">
        <td class="aright"></td>
        <td>
            <input type="button" class="button" id="cmore" value="<?=t('Добавить контакт')?>"/>
        </td>
    </tr>
    <tr>
        <td class="aright"><?=t('Контакт')?></td>
        <td>
            <textarea name="all_contacts"><?=stripslashes(htmlspecialchars($user_novasys['all_contacts']))?></textarea>
        </td>
    </tr>
    <tr>
        <td class="aright"><?=t('Контактирует')?></td>
        <td>
            <? $who_contacts=user_novasys_data_peer::get_who_contacts();
            $who_contacts['']='&mdash;';
            ksort($who_contacts); ?>
            <?=tag_helper::select('contacted', $who_contacts, array('use_values' => false, 'value' => $user_novasys['contacted']))?>
            <input type="checkbox" name="sendcontact" class="sendcontact <?=in_array($user_novasys['contacted'],array(1,2,3,4,5))?'':'hide'?>" value="1" /><span class="sendcontact <?=in_array($user_novasys['contacted'],array(1,2,3,4,5))?'':'hide'?>"><?=t('Отправить сообщение')?></span>
            <br />
            <textarea name="contactedtext" id="contactedtext" class="hide"></textarea>
        </td>
    </tr>
    <tr>
        <td class="aright"><?=t('Статус контакта')?></td>
        <td>
            <?=tag_helper::select('contact_status', user_helper::get_statuses(), array('use_values' => false, 'value'=>$user_data['contact_status']))?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                <?=tag_helper::wait_panel('contact_info_wait') ?>
                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
        </td>
    </tr>
</table>
</form>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var num = 0;
    setTimeout(function(){
        $('input.idate').each(function(){
            $(this).attr('id','idates_'+num);
            $('#idates_'+num).datepicker();
            num += 1;
        });
    },100);
    _makeDel();
    $('#cmore').click(function(){
        if($('.itemi:visible').length==0){
            $('.itemi').show();
        }else{
            var obj = $('#cmoreholder').prev('tr')
            .add($('#cmoreholder').prev('tr').prev('tr'))
            .add($('#cmoreholder').prev('tr').prev('tr').prev('tr'))
            .add($('#cmoreholder').prev('tr').prev('tr').prev('tr').prev('tr'))
            .add($('#cmoreholder').prev('tr').prev('tr').prev('tr').prev('tr').prev('tr'))
            .clone(false);
            obj.find('input.idate, textarea,').val('').removeClass('hasDatepicker');
            obj.find('input.idate').attr('id','idates_'+num);
            obj.insertBefore($('#cmoreholder'));
            $('#idates_'+num).datepicker();
            $('.itemi').show();
            num += 1;
            _makeDel();
        }
    });
    function _makeDel(){
        $('input.idel').unbind('click').click(function(){
            var obj = $(this).parent('td').parent('tr');
            if(confirm("Ви впевненi?")){
                var bigobj = obj
                .add(obj.prev('tr'))
                .add(obj.prev('tr').prev('tr'))
                .add(obj.prev('tr').prev('tr').prev('tr'))
                .add(obj.prev('tr').prev('tr').prev('tr').prev('tr'));
                if($('.itemi').length==5){
                    bigobj.hide().find('input.idate, textarea, select').val('');
                }else{
                    bigobj.remove();
                }
            }else{
                return false;
            }
        });
    }
    $('select[name$="contacted"]').change(function(){
        if($(this).val()!='' && $(this).val()!=10){
            $('.sendcontact').show();
        }else{
            $('.sendcontact').attr('checked','').hide();
            $('#contactedtext').val('').hide();
        }
    });
    $('input.sendcontact').click(function(){
        if($(this).is(':checked'))
            $('#contactedtext').show();
        else
            $('#contactedtext').val('').hide();
    });
});
</script>