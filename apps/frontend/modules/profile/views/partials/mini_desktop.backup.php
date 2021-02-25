<? $user_desktop['user_id'] ? $user_desktop['user_id']=$user_desktop['user_id'] : $user_desktop['user_id']=0; ?>

<? if( user_auth_peer::instance()->get_rights($user_desktop['user_id'], 1)){ // user_auth_peer::instance()->get_rights($user_desktop['user_id'], 10)  || $user['desktop']==1 ?>

    <? $recomended_to_m=db::get_scalar('SELECT count(*) FROM user_recommend WHERE recommending_user_id='.$user_desktop['user_id'].' and status=10'); ?>
    <? $recomended_to_member=db::get_scalar('SELECT count(*) FROM user_recommend WHERE recommending_user_id='.$user_desktop['user_id'].' and status=20'); ?>
    <? $all_recomended_by_user=count(user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id'])); ?>
    <? $all_attracted_by_user=count(user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id'],true)); ?>

    <div class="column_head_small mt10 fs11"><a href="/profile/desktop?id=<?=$user_data['user_id']?>"><?=t('Рабочий стол')?></a></div>

    <div class="ml10 fs11">
        <div class="hide" id="withoutinfo"><?=t('Нет информации')?></div>
        <div id="minidesktop-content">

<!-- ФУНКЦИИ -->

            <? $user_functions=explode(',',str_replace(array('{','}'),array('',''),$user_desktop['functions'])); ?>
            <? if ( strlen($user_desktop['functions'])>2  or  $user_desktop['user_id']==5) { ?>

                <b><?=t('Функции')?></b>

                <? if ( $user_desktop['user_id']==5) { ?>
                    &nbsp;&nbsp;<?=t('Глава (Лидер) Партии')?><br/>
                <? } ?>

                <? foreach (user_auth_peer::get_functions() as $function_id=>$function_title) { ?>
                    <? if (in_array($function_id, $user_functions)) { ?>
                        <? $c1=0; $c2=0; ?>
                        <? if(in_array($function_id,array(5,9))){ ?>
                            <? $rgs=user_desktop_peer::instance()->is_regional_coordinator($user_desktop['user_id']); ?>
                            <? if(is_array($rgs)){ ?>
                                <? foreach($rgs as $rs){ ?>
                                    <? if($f_region=geo_peer::instance()->get_region($rs)){ ?>
                                        <?=($c1==0)?'<p class="m0">'.t($function_title).'</p>':''?>
                                        <p class="m0">
                                        <a href="/search?submit=1&country=1&region=<?=$f_region['id']?>"><?=$f_region['name_' . translate::get_lang()]?></a>
                                        </p>
                                    <? $c1++; ?>
                                    <? } ?>
                                <? } ?>
                            <? } ?>
                        <? }else{ ?>
                            <? if(in_array($function_id,array(6,10))){ ?>
                                <? $rns=user_desktop_peer::instance()->is_raion_coordinator($user_desktop['user_id']); ?>
                                <? if(is_array($rns)){ ?>
                                    <? foreach($rns as $rn){ ?>
                                        <? if($f_raion=geo_peer::instance()->get_city($rn)){ ?>
                                            <?=($c2==0)?'<p class="m0">'.t($function_title).'</p>':''?>
                                            <p class="m0">
                                            <a href="/search?submit=1&country=1&region=<?=$f_raion['region_id']?>"><?=$f_raion['region_name_'.translate::get_lang()]?></a> /
                                            <a href="/search?submit=1&country=1&region=<?=$f_raion['region_id']?>&city=<?=$f_raion['id']?>"><?=$f_raion['name_' . translate::get_lang()]?></a>
                                            </p>
                                        <? $c2++; ?>
                                        <? } ?>
                                    <? } ?>
                                <? } ?>
                            <? }else{ ?>
                                <? if($function_id==18){ ?>
                                    <? $rgs=user_desktop_peer::instance()->is_logistic_coordinator($user_desktop['user_id']); ?>
                                    <? if(is_array($rgs)){ ?>
                                        <? foreach($rgs as $rn){ ?>
                                            <? if($f_region=geo_peer::instance()->get_region($rn)){ ?>
                                                <?=($c3==0)?'<p class="m0">'.t($function_title).'</p>':''?>
                                                <p class="m0">
                                                <a href="/search?submit=1&country=1&region=<?=$rn?>"><?=$f_region['name_' . translate::get_lang()]?></a>
                                                </p>
                                            <? $c3++; ?>
                                            <? } ?>
                                        <? } ?>
                                    <? } ?>
                                    <? $rns=user_desktop_peer::instance()->is_logistic_coordinator($user_desktop['user_id'],'city'); ?>
                                    <? if($rns[0]>0){ ?>
                                        <? foreach($rns as $rn){ ?>
                                            <? if($rn==0)continue;  ?>
                                            <? if($f_raion=geo_peer::instance()->get_city($rn)){ ?>
                                                <?=($c4==0)?'<p class="m0">'.t($function_title).'</p>':''?>
                                                <p class="m0">
                                                <a href="/search?submit=1&country=1&region=<?=$f_raion['region_id']?>"><?=$f_raion['region_name_'.translate::get_lang()]?></a> /
                                                <a href="/search?submit=1&country=1&region=<?=$f_raion['region_id']?>&city=<?=$f_raion['id']?>"><?=$f_raion['name_' . translate::get_lang()]?></a>
                                                </p>
                                                <? $c4++; ?>
                                            <? } ?>
                                        <? } ?>
                                    <? } ?>
                                <? } elseif(in_array($function_id, array(111, 112, 113, 121, 122, 123))){ ?>
																		<? $function_id = ($function_id - 110) > 3 ? $function_id - 120 : $function_id - 110; ?>
																		<? load::model("ppo/ppo"); ?>
																		<? $ppo = ppo_peer::get_user_ppo($user_desktop['user_id'], $function_id); ?>
																		<p class="m0">&nbsp;&nbsp;<a href="/ppo<?=$ppo["id"]?>/"><?=t($function_title)?></a></p>
																<? } else { ?>
                                    <p class="m0">&nbsp;&nbsp;<?=t($function_title)?></p>
                                <? } ?>
                            <? } ?>
                        <? } ?>
                    <? } ?>
                <? } ?>
            <? } ?>

<!-- ЛЮДИ -->

            <? if ( $user_desktop['information_people_count'] or $all_attracted_by_user or $all_recomended_by_user or $user_desktop['offline_count'] or $recomended_to_member or $recomended_to_m) { ?>

                <b><?=t('Люди')?></b><br style="margin-top:5px; margin-bottom:5px;">

                <? if($all_recomended_by_user){ ?>
                    &nbsp;&nbsp;<?=t('Рекомендовал в сеть')?> - <a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=people&recomended=1" class="cbrown"><?=$all_recomended_by_user//$user_desktop['people_recommended']?></a><br>
                <? } ?>
                    
                <? if($all_attracted_by_user){ ?>
                    &nbsp;&nbsp;<?=t('Присоединились к сети')?> - <a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=people&attracted=1" class="cbrown"><?=$all_attracted_by_user//$user_desktop['people_attracted']?></a><br>
                <? } ?>

                <? if ($recomended_to_m) { ?>
                    &nbsp;&nbsp;<?=t('Рекомендовал в "Меритократы"')?> - <a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=people&recommend_m=1" class="cbrown"><?=$recomended_to_m?></a><br>
                <? } ?>

                <? if ($recomended_to_member) { ?> 
                    &nbsp;&nbsp;<?=t('Рекомендовал в члены МПУ')?> - <a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=people&recommend_member=1" class="cbrown"><?=$recomended_to_member?></a><br>
                <? } ?>

                <? if($user_desktop['offline_count']){ ?>
                    &nbsp;&nbsp;<?=t('Создал оффлайн')?> - <a class="cbrown"><?=(!session::has_credential('admin')&&($user['id'] != session::get_user_id()))?intval($user_desktop['offline_count']):'<a href="/people?offline='.$user['id'].'">'.intval($user_desktop['offline_count']).'</a>'?></a>         <br style="margin-bottom:5px;">
                <? } ?>

            <? } ?>
                
<!-- АГИТАЦИЯ -->

            <? $infosumm = $user_desktop['information_people_private_count']+$user_desktop['information_people_phone_count']+$user_desktop['information_people_email_count']+$user_desktop['information_people_social_count'] ?>
            <? if ($infosumm>0 || (in_array(18,$user_functions) && intval($agitation['given'])) || intval($agitation['presented'])) { ?>

                <p class="mb5 mt5"><b><?=t('Агитация')?></b></p>
                <? if ($infosumm>0) { ?>
                    <p class="m0">&nbsp;&nbsp;<?=t('Проинформировал')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=info"><?=$infosumm?></a></p>
                <? } ?>
                <? if (in_array(18,$user_functions) && intval($agitation['given'])) { ?>
                    <p class="m0">&nbsp;&nbsp;<?=t('Передано агитматериалов')?> - <a class="cbrown"><?=intval($agitation['given'])?></a></p>
                <? } ?>
                <? if(intval($agitation['presented'])){ ?>
                    <p class="m0">&nbsp;&nbsp;<?=t('Вручено агитматериалов')?> - <a class="cbrown"><?=intval($agitation['presented'])?></a></p>
                <? } ?>
                <? if (count(unserialize($user_desktop['information_publications']))>0) { ?>
                    <p class="m0">&nbsp;&nbsp;<?=t('Публикации')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=info"><?=count(unserialize($user_desktop['information_publications']))?></a></p>
                <? } ?>
                <? if (count(unserialize($user_desktop['information_banners']))>0) { ?>
                    <p class="m0">&nbsp;&nbsp;<?=t('Баннеры')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=info"><?=count(unserialize($user_desktop['information_banners']))?></a></p>
                <? } ?>

            <? } ?>

<!-- НАГЛЯДНАЯ АГИТАЦИЯ -->

            <? if (count(unserialize($user_desktop['information_avtonumbers_photos']))>0) { ?>
                <p class="mt5 mb5"><b><?=t('Наглядная агитация')?></b></p>
                &nbsp;&nbsp;<?=t('Авторамки')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=naglyadka"><?=count(unserialize($user_desktop['information_avtonumbers_photos']))?></a>
                <br style="margin-bottom:5px;">
            <? } ?>

            <? if ($fact_signatures=db::get_scalar("SELECT SUM(fact) FROM user_desktop_signatures_fact WHERE user_id=:user_id", array('user_id'=>$user_desktop['user_id']))) { ?>
                <p class="mt5 mb5"><b><?=t('Подписи')?></b></p>
                &nbsp;&nbsp;<?=t('Собрано')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=tasks"><?=$fact_signatures?></a>
                <br style="margin-bottom:5px;">
            <? } ?>

<!-- СОБЫТИЯ -->
<? /* ?>
            <? if (db::get_scalar("SELECT count(id) FROM user_desktop_meeting WHERE user_id=:user_id", array('user_id'=>$user_desktop['user_id']))) { ?>
                <p class="mt5 mb5"><b><?=t('Мероприятия МПУ')?></b></p>
                <? $morganize = db::get_scalar("SELECT count(id) FROM user_desktop_meeting WHERE user_id=:user_id AND part=:part", array('user_id'=>$user_desktop['user_id'],'part'=>0)) ?>
                <? $mposetil = db::get_scalar("SELECT count(id) FROM user_desktop_meeting WHERE user_id=:user_id AND part=:part AND confirm=:confirm", array('user_id'=>$user_desktop['user_id'],'part'=>1,'confirm'=>1)) ?>
                <? $mneposetil = db::get_scalar("SELECT count(id) FROM user_desktop_meeting WHERE user_id=:user_id AND part=:part AND confirm=:confirm", array('user_id'=>$user_desktop['user_id'],'part'=>1,'confirm'=>2)) ?>
                
                <? if($morganize){ ?>
                    &nbsp;&nbsp;<?=t('Организовал')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=meetings"><?=$morganize?></a> <br/>
                <? } ?>

                <? if($mposetil){ ?>
                    &nbsp;&nbsp;<?=t('Посетил')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=meetings"><?=$mposetil?></a>
                <? } ?>

                <? if(session::has_credential('admin') && $mneposetil){ ?>
                    <br/>
                    &nbsp;&nbsp;*<?=t('Не посетил')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=meetings"><?=$mneposetil?></a>
                <? } ?>
                <br style="margin-bottom:5px;">
            <? } ?>

            <? if (db::get_scalar("SELECT count(id) FROM user_desktop_event WHERE user_id=:user_id", array('user_id'=>$user_desktop['user_id']))) { ?>
                <p class="mt5 mb5"><b><?=t('Другие мероприятия')?></b></p>
                &nbsp;&nbsp;<?=t('Организовал')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=events"><?=db::get_scalar("SELECT count(id) FROM user_desktop_event WHERE user_id=:user_id AND part=:part", array('user_id'=>$user_desktop['user_id'],'part'=>0))?></a> <br/>
                &nbsp;&nbsp;<?=t('Выступил')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=events"><?=db::get_scalar("SELECT count(id) FROM user_desktop_event WHERE user_id=:user_id AND part=:part", array('user_id'=>$user_desktop['user_id'],'part'=>2))?></a><br/>
                &nbsp;&nbsp;<?=t('Посетил')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=events"><?=db::get_scalar("SELECT count(id) FROM user_desktop_event WHERE user_id=:user_id AND part=:part", array('user_id'=>$user_desktop['user_id'],'part'=>1))?></a>
                <br style="margin-bottom:5px;">
            <? } ?>

            <? if (db::get_scalar("SELECT count(id) FROM user_desktop_education WHERE user_id=:user_id", array('user_id'=>$user_desktop['user_id']))) { ?>
                <p class="mt5 mb5"><b><?=t('Обучение МПУ')?></b></p>
                &nbsp;&nbsp;<?=t('Провел')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=educations"><?=db::get_scalar("SELECT count(id) FROM user_desktop_education WHERE user_id=:user_id AND part=:part", array('user_id'=>$user_desktop['user_id'],'part'=>0))?></a> <br/>
                &nbsp;&nbsp;<?=t('Принял участие')?> - <a class="bold" href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=educations"><?=db::get_scalar("SELECT count(id) FROM user_desktop_education WHERE user_id=:user_id AND part=:part", array('user_id'=>$user_desktop['user_id'],'part'=>1))?></a> <br style="margin-bottom:5px;">
            <? } ?>
<? */ ?>

<!-- ВЗНОСЫ -->
            <? if(session::get_user_id()==$user['id'] || user_auth_peer::instance()->get_rights(session::get_user_id(), $user_desktop['confidence'])){ ?>
                <? load::model('user/user_payments') ?>
                <? $minipayments = user_payments_peer::instance()->get_total($user['id']) ?>
                <p class="mt5 mb5"><b><?=t('Взносы')?></b></p>
                <? if($user['status']==20){ ?>
                &nbsp;&nbsp;<?=t('Вступительный')?> - <?=intval($minipayments[1])?> грн.<br/>
                &nbsp;&nbsp;<?=t('Ежемесячные')?> - <?=intval($minipayments[2])?> грн.<br/>
                <? } ?>
                &nbsp;&nbsp;<?=t('Благотворительные')?> - <?=intval($minipayments[3])?> грн.<br style="margin-bottom:5px;">
            <? } ?>

        </div>

        <? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a style="color:gray;" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>"> <?=t('Редактировать')?> &rarr;</a> <? } ?>

    </div>


<? } ?>