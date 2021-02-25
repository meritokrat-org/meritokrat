<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<title>Друк заяви</title>
<head>
<script type="text/javascript" src="/static/javascript/jquery/jquery-1.4.2.js"></script>
<?=tag_helper::css('system.css') ?>
<style>
    #zayava{border-collapse:separate !important;border:1px solid white;border-spacing: 2px;color:black}
    #zayava td{padding:5px}
    #zayava span{cursor:pointer}
    .midcenter{vertical-align:middle;text-align:center}
    td.grgr{background-color:gray}
</style>
</head>
<body>
    <div id="print" style="margin-bottom:10px;">
        <a href="javascript:;" onclick="$(this).hide();print();">ДРУКУВАТИ</a>
    </div>
    <table id="zayava" cellspacing="2" style="width:880px">
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
                    &laquo;Мерітократична партія України&raquo;. Зі Статутом
                    Мерітократичної партії України, Положенням про членство та Положенням
                    про членські та інші внески в МПУ, а також своїми правами та
                    обов'язками ознайомлений і зобов'язуюсь їх виконувати, у тому числі
                    брати активну участь у діяльності МПУ, а також регулярно сплачувати
                    членські внески.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14 grgr" style="color:white;padding-left:15px;">
                Особисті дані:
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
            </td>
        </tr>
        <tr>
            <td colspan="3" width="648" height="99" bgcolor="#ffffff" style="text-align:justify">
                <p>
                    <strong>1. П.І.Б.:</strong> <?=$item['lastname'].' '.$item['firstname'].' '.$item['fathername']?>
                </p>
                <p>
                    <strong>2. Дата народження: </strong> <?=$item['birthday']?>
                </p>
                <p>
                    <strong> 3. Громадянство: </strong> <?=(!$item['citizenship'])?'Украiна':$item['citizenship']?>
                </p>
                <p>
                    <?
                        $phone = array();
                        foreach(array('mobile','work_phone','home_phone','phone') as $p)
                        {
                            if($item[$p])$phone[] = stripslashes(htmlspecialchars($item[$p]));
                        }
                    ?>
                    <strong>4. Контактний   телефон: </strong> <?=implode(', ',$phone)?>
                </p>
                <p>
                    <strong>5. Контактний  e-mail: </strong> <?=stripslashes(htmlspecialchars($item['email']))?>
                </p>
                <p>
                    <strong>6. Країна / Область / Район / Нас.пункт: </strong>
                    <? if ( $item['country'] ) { ?>
                        <? $country = geo_peer::instance()->get_country($item['country']) ?>
                        <?=$country['name_' . translate::get_lang()]?>
                        <? if ( $item['region'] ) { ?>
                                <? $region = geo_peer::instance()->get_region($item['region']) ?> / <?=$region['name_' . translate::get_lang()]?>
                        <? } ?>
                        <? if ( $item['city'] ) { ?>
                                <? $city = geo_peer::instance()->get_city($item['city']) ?> / <?=$city['name_' . translate::get_lang()]?>
                        <? } ?>
                        <? if ( $item['location'] ) { ?> / <?=stripslashes(htmlspecialchars($item['location']))?> <? } ?>
                    <? } ?>
                </p>
                <p>
                    <strong>7. Поштовий індекс / Вулиця / Номер будинку / Номер квартири: </strong> <?=$item['postindex']?> / <?=$item['street']?> / <?=$item['building']?> / <?=$item['flat']?>
                </p>
                <p>
                    <strong>8. Сфера діяльності: </strong> <?=user_auth_peer::get_segment($item['segment'])?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14 grgr" style="color:white;padding-left:15px">
                Первинна партiйна органiзацiя:
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    <? if($item['ppotype']==1){ ?>
                        <? if($item['ppo']){
                            load::model('ppo/ppo');
                            $ppo = ppo_peer::instance()->get_item($item['ppo']);
                        } ?>
                        Прошу взяти мене на облiк у <?=($ppo)?$ppo['title']:'ППО мого регiону/району'?>
                    <? }elseif($item['ppotype']==2){ ?>
                        Прошу Секретарiат партії самостiйно визначити ППО, у якiй я маю перебувати на облiку
                    <? }else{ ?>
                        Планую створити нову ППО
                    <? } ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="p5 fs14 grgr" style="color:white;padding-left:15px;">
                Членськи внески:
            </td>
            <td width="80" class="acenter" style="padding:0;line-height:1;border:2px solid gray">
                До сплати, грн.
            </td>
        </tr>
        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                А.
            </td>
            <td style="border:2px solid gray">
                Членський квиток та значок
                <? if(!$item['kvitok']){ ?>
                <br/>
                <span class="fs12" style="font-style:italic">Прошу звiльнити мене вiд сплати членського внеску</span>
                <? } ?>
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <?=$item['kvitok']?>
            </td>
        </tr>
        <tr>
            <td width="20" class="midcenter" style="border:2px solid gray">
                Б.
            </td>
            <td style="border:2px solid gray">
                Щомiсячний членський внесок
            </td>
            <td width="80" class="midcenter" style="border:2px solid gray">
                <?=$item['vnesok']?>
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
                <?=$item['bvnesok']?>
            </td>
        </tr>

        <tr>
            <td colspan="3" class="p5 fs14">
                </td>
        </tr>
        <tr>
            <td colspan="3" class="p5 fs14">
                </td>
        </tr>
        
        <tr>
            <td width="20" class="p5 fs14 midcenter grgr" style="color:white;">
                !
            </td>
            <td class="p5 fs14 grgr" style="color:white;padding-left:15px;">
                ПОВНА СУМА ДО СПЛАТИ (сума внесків А + Б + В)
            </td>
            <td width="80" class="acenter midcenter" style="padding:0;line-height:1;border:2px solid gray">
                <span id="totalvnesok"><?=intval($item['kvitok'])+intval($item['vnesok'])+intval($item['bvnesok'])?></span>
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
            <td colspan="3" class="p5 fs14">
                </td>
        </tr>

        <tr>
            <td colspan="3" class="p5 fs14">
                <table id="zayava" cellspacing="2" style="width:880px">
                <tr>
                    <td class="aright" width="100">Дата:</td>
                    <td><?=date("d.m.Y",$item['date'])?></td>
                    <td class="aright" width="100">Пiдпис:</td>
                    <td>________________________________</td>
                    <td width="100"></td>
                </tr>
                </table>
            </td>
        </tr>

        <? if(count($recommend)>0){ ?>
        <tr>
            <td><b><?=t('Рекомендации')?>:</b></td>
            <td colspan="2" class="p5 fs14">
                <? foreach($recommend as $k=>$v){ ?>
                    <?=user_helper::full_name($v,false,array(),false)?><?=(($k+1)!=count($recommend))?', ':''?>
                <? } ?>
            </td>
        </tr>
        <? } ?>

    </table>
</body>
</html>