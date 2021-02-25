<? if(count($zayava)==0 || (session::has_credential('admin') && request::get_int('new')) || (session::has_credential('admin') && request::get_int('id'))){ ?>
<style>
    #zayava{border-collapse:separate !important;border:1px solid white;border-spacing: 2px;color:black}
    #zayava td{padding:5px}
    #zayava span{cursor:pointer}
    .midcenter{vertical-align:middle;text-align:center}
</style>
<div style="width:760px" class="left">

    <div class="mt10 mb10">
	<h1 class="column_head">Заява про вступ до мерiтократичної партiї України</h1>
    </div>

    <div style="width:750px">
        <form method="post" action="/profile/zayava" id="zayavaform">
        <? if($item){ ?>
        <input type="hidden" id="zayava_id" name="zayava_id" value="<?=$item['id']?>"/>
        <? } ?>
        <table id="zayava" cellspacing="2">
        <tr>
            <td colspan="3" class="p5" style="text-align:center">
                <p align="center">
                        Перш нiж заповнити заяву, ознайомтесь iз <a target="_blank" href="/help/index?polozhennya_pro_chlenstvo">вимогами до членiв МПУ</a>.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
                <p class="fs12 aright bold">
                    Голові Політичної партії<br/>&laquo;Мерітократична партія України&raquo;<br/>Шевченку І.А.
                </p>
                <p class="fs16 acenter bold">
                    ЗАЯВА ПРО ВСТУП ДО МЕРІТОКРАТИЧНОЇ ПАРТІЇ УКРАЇНИ
                </p>
                <p align="JUSTIFY">
                        Прошу прийняти мене в члени політичної партії
                    &laquo;Мерітократична партія України&raquo;. Зі <a href="/download/library/14/4258542.doc">Статутом
                    Мерітократичної партії України</a>, <a target="_blank" href="/help/index?polozhennya_pro_chlenstvo">Положенням про членство</a> та <a target="_blank" href="/help/index?polozhennya_pro_vnesky">Положенням
                    про членські та інші внески в МПУ</a>, а також своїми правами та
                    обов'язками ознайомлений і зобов'язуюсь їх виконувати, у тому числі
                    брати активну участь у діяльності МПУ, а також регулярно сплачувати
                    членські внески.
                </p>
                <p class="fs12 aleft bold">
                    <em>УВАГА! УСІ ПОЛЯ Є ОБОВ'ЯЗКОВИМИ ДЛЯ ЗАПОВНЕННЯ!</em>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14" style="background:gray;color:white;padding-left:15px">
                Особисті дані:
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
            </td>
        </tr>
        <tr>
            <td colspan="3" width="648" height="99" bgcolor="#ffffff">
                <p>
                    <strong>1. Прiзвище:</strong>
                    <input rel="<?=t('Вкажiть прiзвище')?>" type="text" style="width:155px" id="lastname" name="lastname" value="<?=($item)?$item['lastname']:$user['last_name']?>"/>
                    <strong> Iм'я:</strong>
                    <input rel="<?=t("Вкажiть iм'я")?>" type="text" style="width:155px" id="firstname" name="firstname" value="<?=($item)?$item['firstname']:$user['first_name']?>"/>
                    <strong> По батьковi:</strong>
                    <input rel="<?=t('Вкажiть по батьковi')?>" type="text" style="width:158px" id="fathername" name="fathername" value="<?=($item)?$item['fathername']:$user['father_name']?>"/>

                </p>
                <p>
                    <strong>2. Дата народження: </strong>
                    <input type="text" rel="<?=t('Вкажiть день народження')?>" name="birthday" id="birthday" style="width:100px" value="<?=($item)?$item['birthday']:$user['birthday']?>"/>
                    <strong> 3. Громадянство: </strong>
                    <input type="checkbox" <?=($item['citizenship'])?'':'checked="checked"'?> id="cua" /> Украiна <input type="checkbox" <?=(!$item['citizenship'])?'':'checked="checked"'?> id="cin" /> Інше <input type="text" <?=($item['citizenship'])?'':'class="hide"'?> id="cval" name="citizenship" value="<?=($item)?$item['citizenship']:''?>" style="width:195px"/>
                </p>
                <p>
                    <?
                        $phone = array();
                        foreach(array('mobile','work_phone','home_phone','phone') as $p)
                        {
                            if($user[$p])$phone[] = stripslashes(htmlspecialchars($user[$p]));
                        }
                    ?>
                    <strong>4. Контактний   телефон: </strong> 
                    <input type="text" id="phone" rel="<?=t('Вкажiть телефон')?>" style="width:190px" name="phone" value="<?=($item)?$item['phone']:implode(', ',$phone)?>"/>
                    <strong>5. Контактний  e-mail: </strong> 
                    <input type="text" id="email" rel="<?=t('Вкажiть електронну пошту')?>" style="width:190px" name="email" value="<?=($item)?$item['email']:stripslashes(htmlspecialchars($user['email']))?>"/>
                </p>
                <p>
                    <? $сountries = geo_peer::instance()->get_countries();ksort($сountries); ?>
                    <input name="country_id" type="hidden" value="<?=$user['country_id']?>" />
                    <strong>6. Країна: </strong> <?=tag_helper::select('country', $сountries, array('use_values' => false, 'value' => $user['country_id'], 'style'=>'width:110px', 'id'=>'country', 'rel'=>t('Выберите страну'), /*'onchange'=>"getRegions(this,'info','region','/ua/ajax/action/regions/')"*/ ))?>
                    <? if ($user['country_id']==1)  $regions = geo_peer::instance()->get_regions($user['country_id']);
                        elseif($user['country_id']>1) $regions['9999']='закордон';
                        else  $regions = array(); ?>
                    <? $userreg = ($item)?$item['region']:$user['region_id'] ?>
                    <input name="region_id" type="hidden" value="<?=$userreg?>" />
                    <strong> Область: </strong> <?=tag_helper::select('region',$regions , array('use_values' => false, 'value' => $userreg, 'style'=>'width:110px', 'id'=>'region', 'rel'=>t('Выберите регион'), )); ?>
                    <? if ($userreg>0 and $userreg!=10000 and $userreg!=9999) $cities = geo_peer::instance()->get_cities($userreg);
                        elseif($user['country_id']>1) $cities['10000']='закордон';
                        else $cities['']='&mdash;'; ?>
                    <? $usercity = ($item)?$item['city']:$user['city_id'] ?>
                    <input name="city_id" type="hidden" value="<?=$usercity?>" />
                    <strong> Район: </strong> <?=tag_helper::select('city', $cities , array('use_values' => false, 'value' => $usercity, 'style'=>'width:110px', 'id'=>'city', 'rel'=>t('Выберите город/район'))); ?>
                    <strong> Нас.пункт: </strong> <input type="text" id="location" rel="<?=t('Вкажiть населений пункт')?>" style="width:100px" name="location" value="<?=($item)?$item['location']:$user['location']?>"/>
                </p>
                <p>
                    <strong>7. Поштовий індекс: </strong> <input type="text" id="postindex" rel="<?=t('Вкажiть поштовий iндекс')?>" name="postindex" value="<?=($item)?$item['postindex']:''?>" style="width:60px"/>
                    <strong> Вулиця: </strong> <input type="text" id="street" style="width:110px" rel="<?=t('Вкажiть вулицю')?>" name="street" value="<?=($item)?$item['street']:$user['street']?>"/>
                    <strong> Номер будинку: </strong> <input type="text" id="house" style="width:30px" rel="<?=t('Вкажiть номер будинку')?>" name="building" value="<?=($item)?$item['building']:$user['house']?>"/>
                    <strong> Номер квартири: </strong> <input type="text" id="flat" name="flat" rel="<?=t('Вкажiть номер квартири')?>" value="<?=($item)?$item['flat']:''?>" style="width:30px"/>
                </p>
                <p class="fs12">Увага! Ці дані будуть внесені до Вашого профілю.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14" style="background:gray;color:white;padding-left:15px">
                Первинна партiйна органiзацiя:
            </td>
        </tr>
        <tr>
            <td><input type="radio" name="ppotype" value="1" <?=(!$item['ppotype'] || $item['ppotype']==1)?'checked="checked"':''?>/></td>
            <td colspan="2">
                <? $ppo = ppo_peer::instance()->get_by_region($userreg) ?>
                <p align="LEFT" style="margin:0">Прошу взяти мене на облiк у <span id="ppoholder"><?=(count($ppo)>0)?tag_helper::select('ppoval',$ppo,array('id'=>'ppoval','value'=>$item['ppo'])):'ППО мого регiону/району'?></span></p>
            </td>
        </tr>
        <tr>
            <td><input type="radio" name="ppotype" value="2" <?=($item['ppotype']==2)?'checked="checked"':''?>></td>
            <td colspan="2">
                <p align="LEFT" style="margin:0">Прошу Секретарiат партії самостiйно визначити ППО, у якiй я маю перебувати на облiку</p>
            </td>
        </tr>
        <tr>
            <td><input type="radio" name="ppotype" value="3" <?=($item['ppotype']==3)?'checked="checked"':''?>></td>
            <td colspan="2">
                <p align="LEFT" style="margin:0">Планую створити нову ППО</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14" style="background:gray;color:white;padding-left:15px">
                Членські внески:
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p align="LEFT">Члени МПУ сплачують вступні та регулярні членські
                внески, за рахунок яких МПУ здійснює свою діяльність. Підрахуйте
                розмір Ваших внесків виходячи з Ваших фінансових можливостей. У
                відповідних місцях зробіть позначку і вкажіть відповідні суми.</p>
            </td>
        </tr>
        <tr>
            <td width="20" class="p5 fs14 midcenter" style="background:gray;color:white;">
                А.
            </td>
            <td class="p5 fs14" style="background:gray;color:white;padding-left:15px;">
                Вступний членський внесок
            </td>
            <td width="80" class="acenter" style="padding:0;line-height:1;border:2px solid gray">
                До сплати, грн.
            </td>
        </tr>
        
        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                <input type="checkbox" class="vstupvnesok" <?=($item && !$item['kvitok'])?'':'checked="checked"'?> /><input type="hidden" id="vstupvnesok" name="vstupvnesok" value="30" />
            </td>
            <td style="border:2px solid gray">
                Членський квиток та значок
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                30.00
            </td>
        </tr>

        <tr>
            <td colspan="3" class="p5 fs14">
            </td>
        </tr>
        
        <tr>
            <td width="20" class="p5 fs14 midcenter" style="background:gray;color:white;">
                Б.
            </td>
            <td class="p5 fs14" style="background:gray;color:white;padding-left:15px;">
                Щомісячний членський внесок
            </td>
            <td width="80" class="acenter" style="padding:0;line-height:1;border:2px solid gray">
                До сплати, грн.
            </td>
        </tr>
        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                <input type="checkbox" class="monthvnesok" name="avnesok" value="1" <?=($item && $item['avnesok']!=1)?'':'checked="checked"'?> />
            </td>
            <td style="border:2px solid gray">
                (40, 30, 20, 10 або 5 грн.) - визначте і впишіть самостійно
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <input type="text" style="width:30px" rel="<?=t('Вкажiть сумму щомiсячного членского внеску')?>" name="monthvnesok" value="<?=($item)?$item['vnesok']:''?>" id="monthvnesok1" <?=(!$item || $item['avnesok']==1)?'':'disabled="disabled"'?> />
            </td>
        </tr>
        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                <input type="checkbox" class="monthvnesok" name="avnesok" value="2" <?=($item['avnesok']==2)?'checked="checked"':''?> />
            </td>
            <td style="border:2px solid gray">
                Інший розмір - визначте і впишіть самостійно (<em>рекомендовано: 1% від середньомісячного доходу у
                розмірі від 5&nbsp;000 до 10&nbsp;000 грн., 2% - при доході від 10&nbsp;001 до 30&nbsp;000
                грн., - при доході від 30&nbsp;001 і більше)</em>
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <input type="text" style="width:30px" rel="<?=t('Вкажiть сумму щомiсячного членского внеску')?>" name="monthvnesok" value="<?=($item)?$item['avnesok']:''?>" id="monthvnesok2" <?=($item['avnesok']==2)?'':'disabled="disabled"'?> />
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
                <p align="JUSTIFY">Оберіть та відмітьте варіант розміру щомісячного
                членського внеску, що найбільше відповідає Вашим фінансовим
                можливостям на даний момент. <strong>УВАГА!</strong> Член МПУ може в
                будь-який момент змінювати (збільшувати або зменшувати) розмір свого
                щомісячного членського внеску в залежності від фінансових можливостей
                на відповідний момент, проте внесок не може бути меншим за 5 грн. на
                місяць.</p>
                Ви також можете зробити добровільний благодійний
                внесок у будь-якому розмірі.
            </td>
        </tr>
        
        <tr>
            <td width="20" class="p5 fs14 midcenter" style="background:gray;color:white;">
                В.
            </td>
            <td class="p5 fs14" style="background:gray;color:white;padding-left:15px;">
                Добровільний благодійний внесок
            </td>
            <td width="80" class="acenter" style="padding:0;line-height:1;border:2px solid gray">
                До сплати, грн.
            </td>
        </tr>
        <tr>
            <td width="20">
            </td>
            <td class="aright bold">
                (В) СУМА БЛАГОДІЙНОГО ВНЕСКУ
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <input type="text" style="width:30px" name="dobrovnesok" rel="<?=t('Вкажiть сумму благодiйного внеску')?>" id="dobrovnesok" value="<?=($item)?$item['bvnesok']:''?>" />
            </td>
        </tr>

        <tr>
            <td colspan="3" class="p5 fs14">
            </td>
        </tr>

        <tr>
            <td width="20" class="p5 fs14 midcenter" style="background:gray;color:white;">
                !
            </td>
            <td class="p5 fs14" style="background:gray;color:white;padding-left:15px;">
                ПОВНА СУМА ДО СПЛАТИ (сума внесків А + Б + В)
            </td>
            <td width="80" class="acenter midcenter" style="padding:0;line-height:1;border:2px solid gray">
                <span id="totalvnesok"><?=($item)?(intval($item['kvitok'])+intval($item['vnesok'])+intval($item['bvnesok'])):'30.00'?></span>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
            </td>
        </tr>
        <tr>
            <td colspan="3" height="25" class="p5 fs14" style="padding:0;border:1px solid black">
            </td>
        </tr>

        <tr>
            <td colspan="3" class="p5 fs14">
                <p><strong>Я підтверджую, що інформація, надана у цій заяві, є
                достовірною, і надаю згоду МПУ зберігати та
                використовувати цю інформацію відповідно до мети та завдань,
                викладених у її Статуті. Також підтверджую, що на момент підписання
                цієї заяви я не є членом іншої політичної партії та не обіймаю посаду,
                що згідно чинного законодавства є несумісною із членством у політичній
                партії. </strong></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
                <input type="submit" name="submit" value="go" class="hide"/>
                <input type="button" name="submitbutton" class="button mr15" value=" <?=(request::get_int('id'))?t('Сохранить'):t('Отправить')?> " id="submit"/>
                <input type="button" class="button_gray mr15" value=" <?=t('Очистить')?> " id="clear"/>
                <? if(session::has_credential('admin') && request::get_int('id')){ ?>
                <input type="button" class="button mr15" value=" <?=t('Создать оффлайн профиль')?> " id="offline"/>
                <input type="button" class="button mr15" value=" <?=t('Добавить информацию в профиль')?> " id="update"/>
                <? } ?>
            </td>
        </tr>

    </table>
    </form>
    </div>

</div>

<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.mouse.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $.datepicker.setDefaults($.extend(
            $.datepicker.regional["uk"])
        );
        $('#birthday').datepicker({
                changeMonth: true,
                changeYear: true,
                 autoSize: true,
                showOptions: {direction: 'left' },
                dateFormat: 'yy-mm-dd',
                shortYearCutoff: 90,
                yearRange: '1940:2010',
                firstDay: true,
                minDate: new Date(1940, 1 - 1, 1)
        });
    });
</script>

<script type="text/javascript">
    <? if(session::has_credential('admin')){ ?>
        var admin = 1;
    <? } ?>
    jQuery(document).ready(function($){
        $('#region').change(function(){
            var $obj;
            $.post('/ppo/getppo_byregion',{'region_id':$(this).val()},function(response){
                if(response.regions.length>0){
                    $obj = '<select id="ppoval" name="ppoval">';
                    for(var i=0; i<response.regions.length; i++){
                        $obj += '<option value="'+response.regions[i].id+'">'+response.regions[i].title+'</option>';
                    }
                    $obj += '</select>';
                }else{
                    $obj = 'ППО мого регiону/району';
                }
                $('#ppoholder').html($obj);
            },'json');
        });
        $('#clear').click(function(){
            $('#zayavaform').find('input[type$="text"]').each(function(){
                $(this).val('');
                _dosum();
            });
            return false;
        });
        $('#submit').click(function(){
            var error = '';
            $('#zayavaform').find('input[type$="text"]').each(function(){
                if(!$(this).attr('disabled') && $(this).attr('name') && $(this).is(':visible') && !$(this).val()){
                    if($(this).attr('name')!='dobrovnesok' && !($(this).attr('name')=='location' && ($('#city').val()>=700 || $('#region').val()==13)) && !(admin && $(this).attr('name')=='email')){
                        alert($(this).attr('rel'));
                        error = 1;
                        return false;
                    }
                }
            });
            if(!error)
                $(this).prev().click();
        });
        $('#cua').click(function(){
            if(!$(this).is(':checked')){
                $('#cval').show();
                $('#cin').attr('checked',true);
            }else{
                $('#cval').hide();
                $(this).attr('checked',true);
                $('#cin').removeAttr('checked');
            }
        });
        $('#cin').click(function(){
            if(!$(this).is(':checked')){
                $('#cval').hide();
                $('#cua').attr('checked',true);
            }else{
                $('#cval').show();
                $(this).attr('checked',true);
                $('#cua').removeAttr('checked');
            }
        });
        $('.monthvnesok').click(function(){
            if(!$(this).is(':checked')){
                $('.monthvnesok').attr('checked',true);
                $(this).removeAttr('checked');
                $('#monthvnesok1, #monthvnesok2').removeAttr('disabled');
                $('#monthvnesok'+$(this).val()).attr('disabled',true);
            }else{
                $('.monthvnesok').removeAttr('checked');
                $(this).attr('checked',true);
                $('#monthvnesok1, #monthvnesok2').attr('disabled',true);
                $('#monthvnesok'+$(this).val()).removeAttr('disabled');
            }
            _dosum();
        });
        $('.vstupvnesok').click(function(){
            if(!$(this).is(':checked')){
                $('#vstupvnesok').removeAttr('name');
            }else{
                $('#vstupvnesok').attr('name','vstupvnesok');
            }
            _dosum();
        });
        $('#vstupvnesok, #monthvnesok1, #monthvnesok2, #dobrovnesok').change(function(){
            _dosum();
        });

        $('#offline').click(function(){
            if(!confirm('<?=t('Вы уверены?')?>'))
                return false;
            $.post('/admin/users_create_offline',{
                'zayava_id':$('input#zayava_id').val(),
                'firstname':$('input#firstname').val(),
                'lastname':$('input#lastname').val(),
                'fathername':$('input#fathername').val(),
                'email':$('input#email').val(),
                'phone':$('input#phone').val(),
                'country':$('select#country').val(),
                'region':$('select#region').val(),
                'city':$('select#city').val(),
                'citizenship':$('input#cval').val(),
                'location':$('input#location').val(),
                'postindex':$('input#postindex').val(),
                'street':$('input#street').val(),
                'house':$('input#house').val(),
                'flat':$('input#flat').val()
                },function(response){
                    if(response.user){
                        window.location = 'http://<?=conf::get('server')?>/profile-'+response.user;
                    }else{
                        if(response.error)
                            alert(response.error);
                        else
                            alert('Помилка, не заповненi необхiднi поля');
                    }
                },'json'
            );
        });
        $('#update').click(function(){
            if(!confirm('<?=t('Вы уверены? Данные из заявления будут добавлены в профиль')?> <?=$user['user_id']?>'))
                return false;
            $.post('/admin/user_fill_profile',{
                'user_id':'<?=$user['user_id']?>',
                'firstname':$('input#firstname').val(),
                'lastname':$('input#lastname').val(),
                'fathername':$('input#fathername').val(),
                'email':$('input#email').val(),
                'phone':$('input#phone').val(),
                'country':$('select#country').val(),
                'region':$('select#region').val(),
                'city':$('select#city').val(),
                'citizenship':$('input#cval').val(),
                'location':$('input#location').val(),
                'postindex':$('input#postindex').val(),
                'street':$('input#street').val(),
                'house':$('input#house').val(),
                'flat':$('input#flat').val()
                },function(response){
                    if(response.user){
                        window.location = 'http://<?=conf::get('server')?>/profile-'+response.user;
                    }else{
                        if(response.error)
                            alert(response.error);
                        else
                            alert('Помилка, не заповненi необхiднi поля');
                    }
                },'json'
            );
        });

        function _dosum()
        {
            var summ = 0;
            $('#vstupvnesok, #monthvnesok1, #monthvnesok2, #dobrovnesok').each(function(){
                if(!$(this).attr('disabled') && $(this).attr('name')){
                    summ = (summ + _intv($(this).val()));
                }
            });
            $('#totalvnesok').text(summ);
        }
        function _intv(val)
        {
            var val = parseInt(val);
            if(isNaN(val))
                return 0;
            else
                return val;
        }
    });
</script>
<? }else{ ?>
<div style="width:760px" class="left ">
    <div class="mt10 mb10">
	<h1 class="column_head">Заява про вступ до мерiтократичної партiї України</h1>
    </div>
    <div style="width:750px" class="mt15 acenter bold fs14">
        <p><?=t('Спасибо, Ваше заявление направлено в Секритариат МПУ')?></p>
        <? if(!$recommend){ ?>
        <p>
        <?=t('Напоминаем, что для завершения процедуры вступления в члены МПУ Вам также необходимо получить рекомендацию от Вашего')?>
        <a target="_blank" href="/search?country=1&region=<?=$user['region_id']?>&city=<?=$user['city_id']?>&submit=1"><?=t('районного/регионального координатора')?></a>,
        <?=t('а при их отсутствии от')?>
        <a target="_blank" href="/people?function=1"><?=t('члена Политического Совета МПУ')?></a>.
        </p>
        <? } ?>
        <? if(session::has_credential('admin')){ ?>
        <p><a href="/profile/zayava?new=1"><?=t('Подать новое заявление')?></a>&nbsp;|&nbsp;<a href="/admin/zayava"><?=t('Перейти к списку заявлений')?></a></p>
        <? } ?>
    </div>
</div>
<? } ?>