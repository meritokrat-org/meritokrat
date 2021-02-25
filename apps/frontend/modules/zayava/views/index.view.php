<? if(count($zayava)==0 || (($access || $auth['status']==20) && request::get_int('new')) || ($access && request::get_int('id'))){ ?>
<? if(request::get_int('new'))unset($item,$user) ?>
<style>
    #zayava{border-collapse:separate !important;border:1px solid white;border-spacing: 2px;color:black}
    #zayava td{padding:5px}
    #zayava span{cursor:pointer}
    .midcenter{vertical-align:middle;text-align:center}
    .zayavalist a{text-decoration:underline}
</style>
<div style="width:760px" class="left zayavalist">
    <div class="mt10 mb10">
	<h1 class="column_head">Заява про вступ до мерiтократичної партiї України</h1>
    </div>

    <div style="width:750px">
        <form method="post" action="/zayava<?=(request::get_int('list'))?'?list='.request::get_int('list'):''?>" id="zayavaform">
        <? if($item){ ?>
        <input type="hidden" id="zayava_id" name="zayava_id" value="<?=$item['id']?>"/>
        <? } ?>
        <? if(request::get_int('new')){ ?>
        <input type="hidden" id="new" name="new" value="1"/>
        <? }?>
        <input type="hidden" id="warning" name="warning" value="<?=intval($item['warning'])?>"/>
        <table id="zayava" cellspacing="2">
        <tr>
            <td colspan="3" class="p5" style="text-align:center">
                <p align="center">
                    <? if(!request::get_int('new')){ ?>
                        Перш нiж заповнити заяву, ознайомтесь iз <a target="_blank" href="/blogpost2012" style="font-weight:bold">процедурою вступу в МПУ</a>
                    <? }else{ ?>
                        <b>Заповнювати заяву можна
                        лише за осіб, які не мають доступу до Інтернету і не зареєтровані у
                        мережі. Заява, заповнена за особу, що зареєстрована у цій мережі, буде
                        автоматично видалена</b>
                    <? } ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
                <p class="fs12 aright bold">
                    Голові Політичної партії<br/>&laquo;Мерітократична партія України&raquo;<br/>Коннову С. В.
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
                <p class="quiet"><i>*<?=t('Внимание! Для корректного написания Вашей фамилии, имени и отчества на партийном билете заполните эти поля на украинском языке')?></i></p>
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
                    <? if(!request::get_int('new') && !$auth['offline']){ ?>
                        <? if($item['user_id']){$ownerdata = user_auth_peer::instance()->get_item($item['user_id']);} ?>
                        <? if(!$ownerdata['offline']){ ?>
                            <strong>5. Контактний  e-mail: </strong>
                            <input type="text" id="email" rel="<?=t('Вкажiть електронну пошту')?>" style="width:190px" name="email" value="<?=($item)?$item['email']:((trim($user['email'])!='')?stripslashes(htmlspecialchars($user['email'])):stripslashes(htmlspecialchars($auth['email'])))?>"/>
                        <? } ?>
                    <? } ?>
                </p>
                <p>
                    <? $сountries = geo_peer::instance()->get_countries();ksort($сountries); ?>
                    <input name="country_id" type="hidden" value="<?=$user['country_id']?>" />
                    <strong>6. Країна: </strong> <?=tag_helper::select('country', $сountries, array('use_values' => false, 'value' => $user['country_id'], 'style'=>'width:110px', 'id'=>'country', 'rel'=>t('Выберите страну'), /*'onchange'=>"getRegions(this,'info','region','/ua/ajax/action/regions/')"*/ ))?>
                    <? if ($user['country_id']==1)  $regions = geo_peer::instance()->get_regions($user['country_id']);
                        elseif($user['country_id']>1) $regions['9999']='закордон';
                        else  $regions = array(); ?>
                    <? $userreg = ($item)?$item['region']:($user['region_id'])?$user['region_id']:0 ?>
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
                <p>
                    <strong>8. Сфера діяльності:<? $sferas = user_auth_peer::instance()->get_segments();?>
                        <? $sferas['']="&mdash;";
                        ksort($sferas);
                        ?>
                        <?=tag_helper::select('segment', $sferas, array('use_values' => false, 'value' => $user['segment']))?>
                 </strong> 
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14" style="background:gray;color:white;padding-left:15px">
                Первинна партiйна органiзацiя:
            </td>
        </tr>
        <? $ppo = ppo_peer::instance()->get_by_region($userreg,true,1) ?>
        <tr class="ppotr <?=(count($ppo)>0)?'':'hide'?>">
            <td><input type="radio" name="ppotype" value="1" <?=((count($ppo)>0) && (!$item['ppotype'] || $item['ppotype']==1))?'checked="checked"':''?>/></td>
            <td colspan="2">
                <p align="LEFT" style="margin:0">Прошу взяти мене на облiк у <span id="ppoholder"><?=(count($ppo)>0)?tag_helper::select('ppoval',$ppo,array('id'=>'ppoval','value'=>$item['ppo'])):'ППО мого регiону/району'?></span></p>
            </td>
        </tr>
        <tr>
            <td><input type="radio" name="ppotype" value="2" <?=($item['ppotype']==2 || count($ppo)==0)?'checked="checked"':''?>></td>
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
            <td colspan="2" class="p5 fs14 midcenter" style="text-align:left;padding-left: 15px;background:gray;color:white;">
                Перелiк внескiв:
            </td>
            <td width="80" class="acenter" style="padding:0;line-height:1;border:2px solid gray">
                До сплати, грн.
            </td>
        </tr>

        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                А.
                <!--input type="checkbox" class="vstupvnesok" <?=($item && !$item['kvitok'])?'':'checked="checked"'?> /><input type="hidden" id="vstupvnesok" name="vstupvnesok" value="30" /-->
            </td>
            <td style="border:2px solid gray">
                Членський квиток та значок<br/>
                <div style="float:right">
                <span class="fs12" style="font-style:italic;text-align:right">Прошу звiльнити мене вiд сплати вступного внеску</span>
                <input type="checkbox" class="vstupvnesok" <?=($item['kvitok'] || !$item)?'':'checked="checked"'?> /><input type="hidden" id="vstupvnesok" name="vstupvnesok" value="<?=($item)?$item['kvitok']:'30'?>" />
                </div>
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <span id="vstupvnesokval"><?=($item)?$item['kvitok']:'30'?></span>
            </td>
        </tr>
        <tr>
            <td width="20" rowspan="2" class="midcenter" style="border:2px solid gray">
                Б.
                <!--input type="checkbox" class="monthvnesok" name="avnesok" value="1" <?=($item && $item['avnesok']!=1)?'':'checked="checked"'?> /-->
            </td>
            <td style="border:2px solid gray">
                <input type="checkbox" class="monthvnesok" name="avnesok" value="1" <?=($item && $item['avnesok']!=1)?'':'checked="checked"'?> />
                Щомісячний членський внесок - (40 або 30 грн.) - визначте і впишіть самостійно
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <input type="text" style="width:70px;text-align:center" rel="<?=t('Вкажiть сумму щомiсячного членского внеску')?>" name="monthvnesok" <?=(($item && $item['avnesok']==1) || !$item)?'':'disabled="disabled"'?> value="<?=($item['avnesok']==1)?$item['vnesok']:'0'?>" id="monthvnesok1" />
            </td>
        </tr>
        <tr>
            <td style="border:2px solid gray">
                <input type="checkbox" class="monthvnesok" name="avnesok" value="2" <?=($item['avnesok']==2)?'checked="checked"':''?> />
                Щомісячний членський внесок - iнший розмір - визначте і впишіть самостійно (<em>рекомендовано: 1% від середньомісячного доходу у
                розмірі від 5&nbsp;000 до 10&nbsp;000 грн., 2% - при доході від 10&nbsp;001 до 30&nbsp;000
                грн., - при доході від 30&nbsp;001 і більше)</em>
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <input type="text" style="width:70px;text-align:center" rel="<?=t('Вкажiть сумму щомiсячного членского внеску')?>" name="monthvnesok" <?=($item && $item['avnesok']==2)?'':'disabled="disabled"'?> value="<?=($item && $item['avnesok']==2)?$item['vnesok']:'0'?>" id="monthvnesok2" />
            </td>
        </tr>
        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                В.
            </td>
            <td style="border:2px solid gray">
                Добровільний благодійний внесок
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <input type="text" style="width:70px;text-align:center" name="dobrovnesok" rel="<?=t('Вкажiть сумму благодiйного внеску')?>" id="dobrovnesok" value="<?=($item)?$item['bvnesok']:'0'?>" />
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
                <p align="JUSTIFY">Оберіть та відмітьте варіант розміру щомісячного
                членського внеску, що найбільше відповідає Вашим фінансовим
                можливостям на даний момент. <strong>УВАГА!</strong> Член МПУ може в
                будь-який момент змінювати (збільшувати або зменшувати) розмір свого
                щомісячного членського внеску в залежності від фінансових можливостей
                на відповідний момент, проте внесок не може бути меншим за 30 грн. на
                місяць.<br/>
                Ви також можете зробити добровільний благодійний
                внесок у будь-якому розмірі.
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
                <input type="button" name="submitbutton" class="button mr15" value=" <?=(request::get_int('id'))?t('Сохранить'):t('Подать заявление')?> " id="submit_button"/>
                <input type="button" class="button_gray mr15" value=" <?=t('Очистить')?> " id="clear"/>
                <? if(session::has_credential('admin') && request::get_int('id')){ ?>
                <input type="button" class="button mr15" value=" <?=t('Добавить информацию в профиль')?> " id="update"/>
                <a class="button mr15" style="padding:5px 7px;text-decoration:none" href="/zayava?zayava=<?=request::get_int('id')?>&print=1"><?=t('Печатать заявление')?></a>
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
                yearRange: '1930:2010',
                firstDay: true,
                minDate: new Date(1930, 1 - 1, 1)
        });
    });
</script>

<script type="text/javascript">
    
    jQuery(document).ready(function($){
        $('#region').change(function(){
            var $obj;
            <? if($access){ ?>
                var admin = 1;
            <? }else{ ?>
                var admin = 0;
            <? } ?>
            if($(this).val()==2 || $(this).val()==13){
                $('input#location').val($(this).find('option:selected').html());
            }
            $.post('/ppo/getppo_byregion',{'region_id':$(this).val()},function(response){
                if(response.regions.length>0){
                    $obj = '<select id="ppoval" name="ppoval">';
                    for(var i=0; i<response.regions.length; i++){
                        $obj += '<option value="'+response.regions[i].id+'">'+response.regions[i].title+'</option>';
                    }
                    $obj += '</select>';
                    $('.ppotr').show();
                    $('input:radio:first').attr('checked','checked');
                }else{
                    $('.ppotr').hide();
                    if($('input:radio:checked').val()==1)
                        $('input:radio[value$="2"]').attr('checked','checked');
                    $obj = 'ППО мого регiону/району';
                }
                $('#ppoholder').html($obj);
            },'json');
        });
        $('#city').change(function(){
            if($(this).val()>700){
                $('input#location').val($(this).find('option:selected').html());
            }
        });
        $('#clear').click(function(){
            $('#zayavaform').find('input[type$="text"]').each(function(){
                $(this).val('');
                _dosum();
            });
            return false;
        });
        $('#submit_button').click(function(){
            var error = 0;
            var $this = $(this);
            $('#zayavaform').find('input[type$="text"]').each(function(){
                if(!$(this).attr('disabled') && $(this).attr('name') && $(this).is(':visible') && (!$(this).val() || $(this).val()=='0')){
                    if($(this).attr('name')!='dobrovnesok' && !($(this).attr('name')=='location' && ($('#city').val()>=700 || $('#region').val()==13))){
                        alert($(this).attr('rel'));
                        error = 1;
                        return false;
                    }
                }
            });
            if(!error){
                var birthday = $('#birthday').val().split('-');
                var dat = new Date(parseInt(birthday[0]),(parseInt(birthday[1])-1),parseInt(birthday[2]));
                var cur = new Date();
                if((cur-dat)/1000 < (18*365*86400)){
                    alert('<?=t('Согласно действующего законодательства членами политической партии могут быть только совершеннолетние лица. Пожалуйста, подайте ваше заявление про вступление в МПУ после достижения Вами 18 лет.')?>');
                    error = 1;
                    return false;
                }
            }
            <? if(!request::get_int('id') && count($zayava)!=0){ ?>
            if(!error){
            $.post('/admin/users_create_offline',{
                'firstname':$('input#firstname').val(),
                'lastname':$('input#lastname').val(),
                'region':$('select#region').val()
                },function(response){
                    if(response.error){
                        error = 1;
                        if(confirm(response.error)){
                            $('input#warning').val('1');
                            error = 0;
                        }
                    }
                    if(!error)
                        $this.prev().click();
                },'json'
            );}
            <? }else{ ?>
            if(!error)
                    $this.prev().click();
            <? } ?>
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
            if($(this).is(':checked')){
                $('input#vstupvnesok').val('0');
                $('#vstupvnesokval').html('0');
            }else{
                $('input#vstupvnesok').val('30');
                $('#vstupvnesokval').html('30');
            }
            _dosum();
        });
        $('#vstupvnesok, #monthvnesok1, #monthvnesok2, #dobrovnesok').change(function(){
            $(this).val(_intv($(this).val()));
            _dosum();
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
            $('#totalvnesok').text(summ+'.00');
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
    <div style="width:750px" class="mt15 ml15 fs14">

        <? if(request::get_int('offline')){ ?>
            <p align="center"><b>Дякуємо, заява направлена до Секретаріату МПУ.</b></p>
            <p><b>Для завершення процедури вступу до МПУ особи, заяву якої Ви щойно
            заповнили, Ви маєте зробити наступні дії:</b></p>
            <p><b>- роздрукувати і дати на підпис особі, чию заяву Ви подавали, паперову
            версію заяви.</b> Роздрукувати заяву можна просто зараз, натиснувши кнопку
            "Друкувати заяву" внизу цієї сторінки, або у будь-який інший зручний
            момент, скориставшись посиланням "Моя заява" під фото у офф-лайн
            профілі, який Ви щойно створили. Підписану паперову версію заяви
            необхідно надіслати поштою у Секретріат МПУ;</p>
            <p><b>- завантажити у створений офф-лайн профіль портретне фото заявника</b>, що
            буде надруковане на партійному квитку (саме з профілів учасників
            Секретаріат МПУ бере фотографії для друку на партійних картках);</p>
            <p><b>-  надати електронну рекомендацію заявнику</b> шляхом натискання на
            посилання "Рекомендувати" у офф-лайн профілі, який Ви щойно створили;</p>
            <? if($zdata['kvitok']){ ?>
                <p><b>- нагадати заявнику, що протягом 30 днів після ухвалення позитивного
                рішення щодо його прийому у члени МПУ  він має сплатити вступний та
                щомісячний за перший місяць членські внески.</b> Тільки після отримання
                вступного внеску Секретаріат МПУ зможе замовити для заявника партійний
                квиток та інші атрибути членства.</p>
            <? } ?>
            <p>
                <a class="button mr15 p5" href="/zayava?zayava=<?=$zdata['id']?>&print=1"><?=t('Печатать заявление')?></a>
                <a class="button mr15 p5" href="/profile-<?=request::get_int('offline')?>"><?=t('Перейти к офф-лайн профилю')?></a>
                <? if($access || $auth['status']==20){ ?>
                <a class="button_gray mr15 p5" href="/zayava?new=1">*<?=t('Подать новое заявление')?></a>
                <? } ?>
                <? if($access){ ?>
                <a class="button_gray mr15 p5" href="/zayava/list">*<?=t('Перейти к списку заявлений')?></a>
                <? } ?>
            </p>
        <? }else{ ?>
            <p align="center"><b>Дякуємо, Ваша заява направлена до Секретаріату МПУ.</b></p>
            <p><b>Для завершення процедури вступу до МПУ Вам необхідно зробити наступні дії:</b></p>
            <p><b>- роздрукувати, підписати і направити у Секретаріат МПУ паперову
            версію Вашої заяви.</b> Роздрукувати заяву можна просто зараз, натиснувши
            кнопку "Друкувати заяву" внизу цієї сторінки, або у будь-який інший
            зручний для Вас момент, скориставшись посиланням "Моя заява" під фото
            у Вашому профілі;</p>
            <p><b>- завантажити у профіль портретне фото</b>, яке Ви хотіли б бачити на
            своєму партійному квитку (саме з профілів учасників Секретаріат МПУ
            бере фотографії для друку на партійних картках);</p>
            <? if(!$recommend){ ?>
            <p><b>- отримати рекомендацiю вiд Вашого
            <a target="_blank" href="/search?country=1&region=<?=$user['region_id']?>&city=<?=$user['city_id']?>&submit=1">районного/регiонального координатора</a>,</b>
            а за їх вiдсутностi вiд
            <a target="_blank" href="/people?function=1"><?=t('члена Политического Совета МПУ')?></a>.
            </p>
            <? } ?>
            <? if($zdata['kvitok']){ ?>
                <p><b>- після ухвалення позитивного рішення щодо прийому Вас у члени МПУ
                протягом 30 днів сплатити вступний та щомісячний за перший місяць
                членські внески</b>. Тільки після отримання вступного внеску Секретаріат
                МПУ зможе замовити для Вас партійний квиток та інші атрибути членства.</p>
            <? } ?>
            <p>
            <a class="button mr15 p5" href="/zayava?zayava=<?=$zdata['id']?>&print=1"><?=t('Печатать заявление')?></a>
            <a class="button mr15 p5" href="/profile-<?=$zdata['user_id']?>"><?=t('Перейти к профилю')?></a>
            <? if($access || $auth['status']==20){ ?>
            <a class="button_gray mr15 p5" href="/zayava?new=1">*<?=t('Подать новое заявление')?></a>
            <? } ?>
            <? if($access){ ?>
            <a class="button_gray mr15 p5" href="/zayava/list">*<?=t('Перейти к списку заявлений')?></a>
            <? } ?>
            </p>
        <? } ?>


        
    </div>
</div>
<? } ?>