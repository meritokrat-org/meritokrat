<? header("Content-Type: text/html; charset=utf-8"); ?>
<style>
.atab{color:black;font-weight:bold}
a.atab:hover,a.atab:focus,.asel{color:#660000;font-weight:bold;text-decoration:none}
</style>
<div class="clear"></div>
<div class="mt10">
    &nbsp;&nbsp;<a href="javascript:;" class="atab asel" id="ainfo">*Адмін.інфо</a>
    &nbsp;&nbsp;<a href="javascript:;" class="atab" id="acont">*Конт.інфо</a>
<? if(session::has_credential('admin')){ ?>
    &nbsp;&nbsp;<a href="javascript:;" class="atab" id="ahist">*Історія контакту</a>
<? } ?>
</div>
<script>
    $(document).ready(function(){
        $('.atab').click(function(){
            $('.atab').removeClass('asel');
            $(this).addClass('asel');
            var id = this.id;
            $('.atab').each(function(){
                if(this.id!=id)
                    $('#div'+this.id).hide();
            });
            $('#div'+id).show();
        });
    });
</script>

<div id="divainfo" class="">
<table class="fs12 mt10">
        <tr>
            <td width="35%;" class="bold p0"></td>
            <td class="aright p0">
                <a class="fs11 cgray" href="/profile/edit?id=<?=request::get_int('id')?>&tab=admin_info"><?=t('Редактировать')?></a>
            </td>
        </tr>
        <tr><td class="bold" width="35%;">Профіль створено:</td><td style="color:#333333"><?=date("Y-m-d H:i",$user_auth['created_ts'])?></td></tr>
        <tr><td class="bold" width="35%;">Активований:</td><td style="color:#333333"><?=$user_auth['activated_ts'] ? date("Y-m-d H:i",$user_auth['activated_ts']) : 'неактивований'?></td></tr>
    <? if ( $user_info['fname'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Имя')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['fname']))?></td></tr>
    <? } ?>
    <? if ( $user_info['fathername'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Отчество')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['fathername']))?></td></tr>
    <? } ?>
    <? if ( $user_info['sname'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Фамилия')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['sname']))?></td></tr>
    <? } ?>
    <? if ( $user_info['country'] ) { ?>
            <? $country = geo_peer::instance()->get_country($user_info['country']) ?>
            <tr><td class="bold" width="35%;"><?=t('Страна')?></td><td style="color:#333333"><?=$country['name_' . translate::get_lang()]?></td></tr>
    <? } ?>
    <? if ( $user_info['region'] ) { ?>
            <? $region = geo_peer::instance()->get_region($user_info['region']) ?>
            <tr><td class="bold" width="35%;"><?=t('Регион')?></td><td style="color:#333333"><?=$region['name_' . translate::get_lang()]?></td></tr>
    <? } ?>
    <? if ( $user_info['district'] ) { ?>
            <? $city = geo_peer::instance()->get_city($user_info['district']) ?>
            <tr><td class="bold" width="35%;"><?=t('Город областного подчинения/Район области')?></td><td style="color:#333333"><?=$city['name_' . translate::get_lang()]?></td></tr>
    <? } ?>
    <? if ( $user_info['location'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Место проживания')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['location']))?></td></tr>
    <? } ?>
    <? if ( $user_info['age'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Дата рождения')?></td><td style="color:#333333"><?=$user_info['age']?></td></tr>
    <? } ?>
    <? if ( $user_info['sex'] ) { ?>
            <tr>
                <td class="bold" width="35%;"><?=t('Пол')?></td>
                <td style="color:#333333"><?=($user_info['sex']==2)?t('Мужской'):t('Женский')?></td>
            </tr>
    <? } ?>
    <? if ( $user_info['sfera'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Род занятий')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['sfera']))?></td></tr>
    <? } ?>
    <? if ( $user_info['activity'] ) { ?>
            <? $sferas = user_auth_peer::instance()->get_segments();?>
            <? $sferas['']="&mdash;";?>
            <? ksort($sferas);?>
            <tr><td class="bold" width="35%;"><?=t('Сфера деятельности')?></td><td style="color:#333333"><?=user_auth_peer::get_segment($user_info['activity'])?></td></tr>
    <? } ?>
    <? if ( $user_info['activitya'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Другая')?></td><td style="color:#333333"><?=$user_info['activitya']?></td></tr>
    <? } ?>
    <? if ( $user_info['activity2'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Дополнительная сфера деятельности')?></td><td style="color:#333333"><?=user_auth_peer::get_segment($user_info['activity2'])?></td></tr>
    <? } ?>
    <? if ( $user_info['activitya2'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Другая')?></td><td style="color:#333333"><?=$user_info['activitya2']?></td></tr>
    <? } ?>
    <? if ( $user_info['email'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Е-пошта')?></td><td style="color:#333333"><?=$user_info['email']?></td></tr>
    <? } ?>        
    <? if ( $user_info['phone'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('телефон')?></td><td style="color:#333333"><?=$user_info['phone']?></td></tr>
    <? } ?>        
    <? if ( $user_info['site'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Сайт')?></td><td style="color:#333333"><?=$user_info['site']?></td></tr>
    <? } ?>        
    <? if ( $user_info['about'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Коротко о себе')?></td><td style="color:#333333"><?=$user_info['about']?></td></tr>
    <? } ?>
    <? if ( $user_info['referer'] ) { ?>
            <? $arr = array('','Из общественной деятельности','Из юридической профессии и бизнеса','Из социальной сети','Из СМИ (газеты, журналы и т.д.)','От родственников/друзей/колег','Другое') ?>
            <tr>
                <td class="bold" width="35%;"><?=t('Откуда Вы узнали про Игоря Шевченко')?></td>
                <td style="color:#333333"><?=$arr[$user_info['referer']]?></td>
            </tr>
    <? } ?>
    <? if ( $user_info['rsocial'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Какой?')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['rsocial']))?></td></tr>
    <? } ?>
    <? if ( $user_info['influence'] ) { ?>
            <tr><td class="bold" width="35%;"><?=t('Кто повлиял на Ваше решение присоединиться к команде?')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_info['influence']))?></td></tr>
    <? } ?>
    <? if ( $user_info['email_lang'] ) { ?>
            <tr>
                <td class="bold" width="35%;"><?=t('На каком языке хотели бы получать сообщения от Игоря Шевченко?')?></td>
                <td style="color:#333333"><?=($user_info['email_lang']==1)?tag_helper::image('/icons/ru.png', array('alt'=>"ru")):tag_helper::image('/icons/ua.png', array('alt'=>"ua"))?></td>
            </tr>
    <? } ?>
    <? if ( $user_info['is_public'] ) { ?>
            <tr>
                <td class="bold" width="35%;"><?=t('Публичное присоединение?')?></td>
                <td style="color:#333333"><?=($user_info['is_public'])?t('Да'):t('Нет')?></td>
            </tr>
    <? } ?>
    <? if ( $user_novasys['notates'] ) { ?>
            <tr>
                <td class="bold" width="35%;">Примітки</td>
                <td style="color:#333333"><?=$user_novasys['notates']?></td>
            </tr>
    <? } ?>
    <? if ( $user_novasys['status']>0 ) { ?>
            <tr>
                <td class="bold" width="35%;"><?=t('Статус')?></td>
                <td style="color:#333333"><?=user_novasys_data_peer::get_status($user_novasys['status']);?></td>
            </tr>
    <? } ?>
</table>
</div>

<!-- DIV CONT -->

<div id="divacont" class="<?=session::get_user_id()==4 ? '' : 'hide'?>">
    <table class="fs12 mt10">
                <tr>
            <td width="35%;" class="bold aright p0"></td>
            <td class="aright p0">
                <a class="fs11 cgray" href="/profile/edit?id=<?=request::get_int('id')?>&tab=admin_contact"><?=t('Редактировать')?></a>
            </td>
        </tr>
        <!-- приоритетные -->
        <? if ( $user_novasys['phone'] || $user_novasys['email0'] || $user_novasys['site'] || $user_info['site'] || $user_info['email'] || $user_info['phone'] || $user_data['mobile']) { ?>
        <tr>
            <td class="bold aright" width="35%;"></td>
            <td style="color:#333333;font-weight:bold;"><?=t('Приоритетные')?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['phone'] || $user_info['phone'] || $user_data['mobile']) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Телефон')?></td>
            <td style="color:#333333"><?=$user_novasys['phone'] ? stripslashes(htmlspecialchars($user_novasys['phone'])) : ($user_data['mobile'] ? $user_data['mobile'] : stripslashes(htmlspecialchars($user_info['phone'])))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['email0'] || $user_info['email'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Электронная почта')?></td>
            <td style="color:#333333"><?=$user_novasys['email0'] ? stripslashes(htmlspecialchars($user_novasys['email0'])) : stripslashes(htmlspecialchars($user_info['email']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['site'] || $user_info['site'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Веб-сайт')?></td>
            <td style="color:#333333"><?=$user_novasys['site'] ? stripslashes(htmlspecialchars($user_novasys['site'])) : stripslashes(htmlspecialchars($user_info['site']))?></td>
        </tr>
        <? } ?>

        <!-- личные -->
        <? if ( $user_novasys['site'] || $user_novasys['mphone'] || $user_novasys['mphone1a'] || $user_novasys['fax1'] || $user_novasys['email1'] 
                || $user_novasys['email1a'] || $user_novasys['site1'] || $user_novasys['skype1'] || $user_novasys['icq1']) { ?>
        <tr>
            <td class="bold aright" width="35%;"></td>
            <td style="color:#333333;font-weight:bold;"><?=t('Личные')?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['mphone'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Мобильный телефон')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['mphone']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['mphone1a'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Альтернативный моб.тел.')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['mphone1a']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['fax1'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Факс')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['fax1']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['email1'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Электронная почта')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['email1']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['email1a'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Альтернативная эл.почта')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['email1a']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['site1'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Веб-сайт')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['site1']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['skype1'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;">Skype</td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['skype1']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['icq1'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;">ICQ</td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['icq1']))?></td>
        </tr>
        <? } ?>

        <!-- рабочие -->
        <? if ( $user_novasys['phone2'] || $user_novasys['fax2']  || $user_novasys['email2'] || $user_novasys['site2'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"></td>
            <td style="color:#333333;font-weight:bold;"><?=t('Рабочие')?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['phone2'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Рабочий телефон')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['phone2']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['fax2'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Рабочий факс')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['fax2']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['email2'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Рабочая эл.почта')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['email2']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['site2'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Веб-сайт организации')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['site2']))?></td>
        </tr>
        <? } ?>

        <!-- контакты помощника -->
        <? if ( $user_novasys['name3'] || $user_novasys['lname3']  || $user_novasys['mname3']  || $user_novasys['phone3']  || $user_novasys['hphone3']
                 || $user_novasys['mphone3']  || $user_novasys['email3']  || $user_novasys['skype3']  || $user_novasys['icq3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"></td>
            <td style="color:#333333;font-weight:bold;"><?=t('Контакты помощника')?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['name3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Имя')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['name3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['lname3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Фамилия')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['lname3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['mname3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Отчество')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['mname3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['phone3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Рабочий телефон')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['phone3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['hphone3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Домашний телефон')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['hphone3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['mphone3'] ) { ?>
        <tr>
            <td class="aright" width="35%;"><?=t('Мобильный телефон')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['mphone3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['email3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Электронная почта')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['email3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['skype3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;">Skype</td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['skype3']))?></td>
        </tr>
        <? } ?>
        <? if ( $user_novasys['icq3'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;">ICQ</td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['icq3']))?></td>
        </tr>
        <? } ?>

</table>
</div>

<? if(session::has_credential('admin')){ ?>
<!-- DIV HIST -->

<div id="divahist" class="hide">
<table class="fs12 mt10">

        <tr>
            <td width="35%;" class="bold aright p0"></td>
            <td class="aright p0">
                <a class="fs11 cgray" href="/profile/edit?id=<?=request::get_int('id')?>&tab=contact_info"><?=t('Редактировать')?></a>
            </td>
        </tr>
    <? foreach($user_contact as $cont){ ?>
    <? $res = user_contact_peer::instance()->get_item($cont) ?>
        <tr>
            
            <td class="bold aright" width="35%;"><?=user_helper::com_date($res['date'])?>:</td>
            <td style="color:#333333" colspan="2"><?=user_helper::get_contact_type($res['type'])?>, <?=user_novasys_data_peer::get_who_contact($res['who'])?><br>
            <?=stripslashes(htmlspecialchars($res['description']))?></td>
        </tr>
    <? } ?>

    <? if ( $user_novasys['all_contacts'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Контакт')?></td>
            <td style="color:#333333"><?=stripslashes(htmlspecialchars($user_novasys['all_contacts']))?></td>
        </tr>
    <? } ?>
    <? if ( $user_novasys['contacted'] ) { ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Контактирует')?></td>
            <td style="color:#333333"><?=user_novasys_data_peer::get_who_contact($user_novasys['contacted'])?></td>
        </tr>
    <? } ?>
        <tr>
            <td class="bold aright" width="35%;"><?=t('Статус контакта')?></td>
            <td style="color:#333333"><?=user_helper::get_status($user_data['contact_status'])?></td>
        </tr>

</table>
    
        <? if ( session::has_credential('admin') ) { ?>
	<div class="fs11 box p10 mt10"><form>
                <input type="hidden" name="user_id" id="user_id" value="<?=request::get_int('id')?>">
                Контактує:
                <? $who_contacts = user_novasys_data_peer::get_who_contacts();
                $who_contacts['']='&mdash;';
                ksort($who_contacts);
                ?>
                <?=tag_helper::select('contacted', $who_contacts, array('use_values' => false, 'value' => $user_novasys['contacted'],'id'=>'contacted','style'=>'width:200px;'))?>
                <input type="checkbox" id="sendcontact" name="sendcontact" class="sendcontact <?=in_array($user_novasys['contacted'],array(1,2,3,4,5))?'':'hide'?>" value="1" /><span class="sendcontact <?=in_array($user_novasys['contacted'],array(1,2,3,4,5))?'':'hide'?>"><?=t('Отправить сообщение')?></span>
                <br />
                <textarea name="contactedtext" id="contactedtext" class="hide"></textarea>
                <br />
		<input type="button" class="button" id="form_submit" value="Зберегти" />
                <div id="loading" class="hide"><img src="https://s1.meritokrat.org/common/loading.gif"/></div>
	</form></div>
        <? } ?>
    
</div>
<? } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#form_submit').click(function(){
           $('#loading').show(1);
           $.get('/admin/save_user', { user_id: $("#user_id").val(), ajx: 1, interesting: $("#interesting").val(), contacted: $("#contacted").val(), contactedtext: $("#contactedtext").val(), sendcontact: $("#sendcontact").val()}, function(data){
               $('#loading').hide();
                });
        });
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