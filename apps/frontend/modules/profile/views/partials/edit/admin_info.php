<form id="admin_info_form" class="form mt10 hidden">
<? if ( session::has_credential('admin') ) { ?>
    <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
<? } ?>
<input type="hidden" name="type" value="admin_info"/>

<table width="100%" class="fs12">

<tr>
    <td class="aright"><?=t('Имя')?></td>
    <td>
        <input name="fname" rel="<?=t('Введите полное имя')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_info['fname']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Отчество')?></td>
    <td>
        <input name="fathername" rel="<?=t('Введите отчество')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_info['fathername']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Фамилия')?></td>
    <td>
        <input name="sname" rel="<?=t('Введите фамилию')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_info['sname']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Страна')?></td>
    <td>
        <? load::model('geo') ?>
        <? $сountries = geo_peer::instance()->get_countries() ?>
        <? ksort($сountries) ?>
        <?=tag_helper::select('country', $сountries, array('use_values' => false, 'value' => $user_info['country'], 'id'=>'country', 'rel'=>t('Выберите страну') ))?>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Регион')?></td>
    <td>
        <? if ($user_info['country']==1)  $regions = geo_peer::instance()->get_regions($user_info['country']);
        elseif($user_info['country']>1) $regions['9999']='закордон';
        else  $regions = array(); ?>
        <?=tag_helper::select('region', $regions, array('use_values' => false, 'value' => $user_info['region'], 'id'=>'region', 'rel'=>t('Выберите регион'), )); ?>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Город областного подчинения/Район области')?></td>
    <td>
        <? if ($user_info['region']>0 and $user_info['region']!=10000) $cities = geo_peer::instance()->get_cities($user_info['region']);
        elseif($user_info['country']>1) $cities['10000']='закордон';
        else $cities=array(); ?>
        <?=tag_helper::select('district', $cities , array('use_values' => false, 'value' => $user_info['district'], 'id'=>'city', 'rel'=>t('Выберите город/район'))); ?>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Место проживания')?></td>
    <td>
        <input name="location" rel="<?=t('город, поселок, село')?>" class="text" type="text" id="location" value="<?=stripslashes(htmlspecialchars($user_info['location']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Дата рождения')?></td>
    <td>
        <input name="age" rel="<?=t('Заполните дату рождения корректно')?>" class="text" type="text" id="age" style="width:153px;" value="<?=$user_info['age']?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Пол')?></td>
    <td>
        <input type="radio" name="sex" value="2" <?=$user_info['sex'] == 2 ? 'checked' : ''?> />
        <label for="sex_2"><?=t('Мужской')?></label>
        <input type="radio" name="sex" value="1" <?=$user_info['sex'] == 1 ? 'checked' : ''?> />
        <label for="sex_1"><?=t('Женский')?></label>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Род занятий')?></td>
    <td>
        <input name="sfera" rel="<?=t('Заполните род занятий')?>" class="text" type="text" id="sfera" value="<?=stripslashes(htmlspecialchars($user_info['sfera']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Сфера деятельности')?></td>
    <td>
        <? $sferas = user_auth_peer::instance()->get_segments();?>
        <? $sferas['']="&mdash;";?>
        <? ksort($sferas);?>
        <?=tag_helper::select('activity', $sferas, array('use_values' => false, 'value' => $user_info['activity']))?>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Другая')?></td>
    <td>
         <input name="activitya" class="text" type="text" id="activitya" value="<?=stripslashes(htmlspecialchars($user_info['activitya']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Дополнительная сфера деятельности')?></td>
    <td>
        <?=tag_helper::select('activity2', $sferas, array('use_values' => false, 'value' => $user_info['activity2']))?>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Другая')?></td>
    <td>
        <input name="activitya2" class="text" type="text" id="activitya2" value="<?=stripslashes(htmlspecialchars($user_info['activitya2']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Коротко о себе')?></td>
    <td>
        <textarea name="about" id="about" style="height: 75px; width: 400px;"><?=stripslashes(htmlspecialchars($user_info['about']))?></textarea>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Почему присоединились к сети "Меритократ"')?></td>
    <td>
        <textarea name="why_join" id="why_join" style="height: 75px; width: 400px;"><?=stripslashes(htmlspecialchars($user_data["why_join"]))?></textarea>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Чем Вы можете помочь Меритократическому движению')?></td>
    <td>
        <? $user_can_do = unserialize($user_data["can_do"]) ?>
        <? if(!is_array($user_can_do))$user_can_do=array() ?>
        <input type="checkbox" name="can_do[]" class="can_do" value="1" <?= in_array(1,$user_can_do) ? "checked" : "" ?> />
        <?=t('готов заниматься интернет агитацией')?><br>
        <input type="checkbox" name="can_do[]" class="can_do" value="2" <?= in_array(2,$user_can_do) ? "checked" : "" ?> />
        <?=t('готов заниматься уличной агитацией')?><br>
        <input type="checkbox" name="can_do[]" class="can_do" value="3" <?= in_array(3,$user_can_do) ? "checked" : "" ?> />
        <?=t('могу помогать финансово (каждая гривня имеет значение)')?><br>
        <input type="checkbox" name="can_do[]" class="can_do" value="4" <?= in_array(4,$user_can_do) ? "checked" : "" ?> />
        <?=t('другое')?><br>
        <input type="text" id="can_do_text" class="text <?=($user_data["can_do"]!=4)?'hide':''?>" name="can_do_text" value="<?=  stripslashes($user_data["can_do_text"])?>">
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Откуда Вы узнали про меритократию');?>?</td>
    <td>
        <? 
        load::model('user/user_shevchenko_data');
        $arr = user_shevchenko_data_peer::get_referals(); ?>
        <?=tag_helper::select('referer', $arr, array('use_values' => false, 'value' => $user_info['referer']))?>
    </td>
</tr>
<tr  id="another" class="<?=($user_info['referer']==6) ? ' ' : ' hide'?>aright">
    <td>
        <label for="referer_6"><?=t('Деталі')?></label><br/>
    </td>
    <td>
        <div class="text fl bold">
            <input type="text" name="ranother" class="text" value="<?=($user_info['referer']==6) ? $user_info['ranother'] : ''?>">
        </div>
    </td>
</tr>
<tr id="social_list" class="hide">
    <td class="aright"><?=t('Какой?')?></td>
    <td>
        <div>
                <div class="cb ">
                    <input type="radio" name="rsocial" class="rsocial" value="facebook" <?=($user_info['rsocial']=='facebook') ? ' checked' : ''?>>
                    <label for="rsocial_facebook"><?=t('Facebook')?></label>
                </div>
                <div class="cb ">
                    <input type="radio" name="rsocial" class="rsocial" value="vkontakte" <?=($user_info['rsocial']=='vkontakte') ? ' checked' : ''?>>
                    <label for="rsocial_vkontakte"><?=t('ВКонтакте')?></label></div>
                <div class="cb ">
                    <input type="radio" name="rsocial" class="rsocial" value="other" <?=(!in_array($user_info['rsocial'],array('vkontakte','facebook'))) ? ' checked' : ''?>>
                    <label for="rsocial_other"><?=t('Другой')?></label></div>
                <div id="social_another" class="<?=(!in_array($user_info['rsocial'],array('vkontakte','facebook'))) ? '' : ' hide'?>">
                        <input type="text" name="another_rsocial" class="text" value="<?=(!in_array($user_info['rsocial'],array('vkontakte','facebook'))) ? $user_info['rsocial'] : ''?>">
                </div>
        </div>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Кто повлиял на Ваше решение<br/> присоединиться к команде?')?></td>
    <td>
        <input name="influence" class="text" type="text" id="influence" value="<?=stripslashes(htmlspecialchars($user_info['influence']))?>"/>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('На каком языке хотели бы получать<br/> сообщения от Игоря Шевченко?')?></td>
    <td>
        <input <?=$user_info['email_lang']==2 ? 'checked' : ''?> type="radio" name="email_lang" value="2"/>
        <label for="email_lang_ua"><?=tag_helper::image('/icons/ua.png', array('alt'=>"ua"))?></label>
        <input <?=$user_info['email_lang']==1 ? 'checked' : ''?> type="radio" name="email_lang" value="1"/>
        <label for="email_lang_ru"><?=tag_helper::image('/icons/ru.png', array('alt'=>"ru"))?></label>
    </td>
</tr>

<tr>
    <td class="aright"><?=t('Публичное присоединение?')?></td>
    <td>
        <input <?=$user_info['is_public']==1 ? 'checked' : ''?> type="radio" name="is_public" value="1"/>
        <label for="is_public_y">Да</label>
        <input <?=$user_info['is_public']==0 ? 'checked' : ''?> type="radio" name="is_public" value="0"/>
        <label for="is_public_n">Нет</label>
    </td>
</tr>

<tr>
    <td class="aright">Статус</td>
    <td>
        <? $statuces = user_novasys_data_peer::get_statuces();?>
        <? $statuces['']="&mdash;";?>
        <? ksort($statuces);?>
        <?=tag_helper::select('status', $statuces, array('use_values' => false, 'value' => $user_novasys['status']))?>
    </td>
</tr>

<tr>
    <td class="aright">Примітки</td>
    <td>
         <textarea name="notates" id="about" style="height: 75px; width: 400px;"><?=stripslashes(htmlspecialchars($user_novasys['notates']))?></textarea>
    </td>
</tr>

<tr>
    <td></td>
    <td>
            <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
            <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
            <?=tag_helper::wait_panel('admin_info_wait') ?>
            <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
    </td>
</tr>

</table>
</form>
<script type="text/javascript">
    
    jQuery(document).ready(function($){
        //радіо звідки дізналися
        $('select[name="referer"]').change(function(){
            if($(this).val()==3)
            {
                $('#social_list').show();
                $('#another').hide();
                $('#another').val('');
            }
            else if($(this).val()==6)
            {
                $('#social_list').hide();
                $('#another').show();
            }
            else
            {
                $('#social_list').hide();
                $('#another').hide();
                $('#another').val('');
            }
        }).change();
        //соцмережи
        $('.rsocial').click(function(){
            if($(this).val()=='other')
                {
                    $('#social_another').show();
                }
            else
                {
                $('#social_another').hide();
                $('#social_another').val('');
                }
        });
        //чим може допомогти
        $('.can_do').change(function(){
            if($(this).val()==4 && $(this).is(':checked')){
                $('#can_do_text').show();
            }else if($(this).val()==4){
                $('#can_do_text').hide();
            }
        });
});
</script>