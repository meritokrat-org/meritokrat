<style type="text/css">
    .desktop_panel a {
        -moz-border-radius: 12px 12px 0 0;
        background: url("/static/images/common/menu_bg_center.png") repeat scroll 0 0 transparent;
        text-decoration: none;
        padding: 6px 12px 2px 12px;
        margin-left: 1px;
        margin-bottom: -4px;

    }
    .desktop_panel a.selected {
        color:#FFCC66;
        display:block;
        float: left;
        font-weight: bold;
        padding: 6px 12px 2px 12px;
    }
    .tab_pane {
        margin-bottom: 15px;
        margin-top:25px;
        background: none;
     }
    .content_recomended {
        height: <?=((count($people_recomended)+1)*20)>280 ? 280 : ((count($people_recomended)+1)*20)?>px;
        margin: 10px;
        overflow: auto;
    }
    .content_attr {
        height: <?=((count($people_attracted)+1)*20)>280 ? 280 : ((count($people_attracted)+1)*20)?>px;
        margin: 10px;
        overflow: auto;
    }
    #pane_information table td{
        padding-left: 12px;
    }
    #pane_information table td b{
        margin-left: -12px;
    }
    #pane_information table td .cbrown b{
        margin-left: 0px;
    }
/*    #main_res td {
        padding-left: 0px;
        width: 10%;
        
    }*/
</style>
<div class="profile" style="color:black;">

	<div class="left" style="width: 750px;">
            <h1 class="mb5 fs28 mt10" style="color:#660000; margin-left: 20px;">
	<?=t('Результаты')?>
       <?   load::model('user/user_sessions');
       $user_functions=explode(',',str_replace(array('{','}'),array('',''),$user_desktop['functions']));
       ?>
</h1>
<div style="color: gray; margin-top: -15px;" class="right fs11 mr5 mb10"><?=user_sessions_peer::instance()->last_visit($user_data['user_id'])?></div>
<? #include 'partials/head.php' ?>
<div class="content_pane mt10" id="pane_information" style="line-height:110%;<?=(request::get_string('tab') and request::get_string('tab')!='information') ? 'display: none;' : ''?>">
    <table width="100%" class="fs12 " style="color:black">
        <tr>
            <td colspan="2">
                <table width="100%" class="fs12 " id="main_res" style="color:black; margin-bottom: 0px;">
                        <tr style="text-align: justify;">
                            <td>
                                <p style=" text-align: left;"><span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results"><?=number_format($ppo_cnt, 0, '', ' ')?></a></strong></span></p>
                            </td>
                            <td>
                                <span style="font-size: 12px;"><?=t('партийных организаций (первичных и местных) во всех регионах Украины')?></span>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <p  style=" text-align: left;"><span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results"><?=number_format($ppo_members, 0, '', ' ')?></a></strong></span></p>
                            </td>
                            <td>
                                <span style="font-size: 12px;"><?=t('лидеров - региональных и районных координаторов развития МПУ во всех регионах Украины')?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p  style=" text-align: left;"><span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results"><?=number_format($mpu_members, 0, '', ' ')?></a></strong></span></p>
                            </td>
                            <td>
                                <span style="font-size: 12px;"><?=t('членов МПУ (прием в члены начался 25.05.2011)')?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p  style=" text-align: left;"><span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results"><?=number_format($act, 0, '', ' ')?></a></strong></span></p>
                            </td>
                            <td>
                                <p><span style="font-size: 12px;"><?=t('активистов партии по всей Украине')?>&nbsp;(<a href="https://meritokrat.org/search?smap=1&distance=10&submit=1&su=1" target="_blank" style='color:#660000 !important; font-weight: bold;'><?=t('карта активистов')?></a>)</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p  style=" text-align: left;"><span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results"><?=number_format($meritokrat_members, 0, '', ' ')?></a></strong></span></p>
                            </td>
                            <td>
                                <span style="font-size: 12px;"><?=t('участников социальной сети')?>&nbsp;"<span style="text-decoration: underline;"><a href="https://meritokrat.org/home" target="_blank" style='color:#660000 !important; '><strong><?=t('Меритократ')?></strong></a></span>"&nbsp;<?=t('(сеть, объединяющая людей, интересующихся идеями меритократии, а также сторонников и членов Меритократической партии Украины)')?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p  style=" text-align: left;"><span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results"><?=number_format($informed_people, 0, '', ' ')?></a></strong></span></p>
                            </td>
                            <td>
                                <span style="font-size: 12px;"><?=t('людей проинформировано о МПУ и идеи меритократии во время личных встреч, телефонными звонками, электронными письмами и в социальных сетях')?></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="13%">
                                <p style=" text-align: left;">
                                    <span style="font-size: 16px;"><strong><a href="https://meritokrat.org/results">200 000 +</a></strong></span>
                                </p>
                            </td>
                            <td>
                                <span style="font-size: 12px;"><?=t('ознакомились с деятельностью партии благодаря рекламе МПУ в социальных сетях Facebook, VKontakte')?></span>
                            </td>
                        </tr>
                        
                        
                        
                        
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                 <table width="100%" class="fs12 " style="color:black; margin-bottom: 0px;">
                        <tr>
                            <td width="50%" style="padding-left: 0px;">
                                <table width="100%" class="fs12 " style="color:black;">
                                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                            <td class="bold cbrown" rel="info"><b><?=t('Партийная структура')?></b></td>
                                            <td class="aright"></td>
                                            <td class="aright"></td>
                                    </tr>
                                    <tr>
                                            <td class=" pointer" id="rpo"><?=t('Региональные партийные организации')?></td>
                                            <td class="cbrown"><a href="/ppo?category=3"><?=count($rpo)?></a></td>
                                    </tr>
                                    <tr>
                                    <td colspan="2" style="padding: 0 !important;">
                                        <table id="rpo_table" class="hide" style="margin-left: 10px;">
                                            <?foreach($rpo as $k=>$v) {?>
                                            <tr>
                                                <td coslpan="2">
                                                    <a href="/ppo<?=$v['id']?>"><?=$v['title']?></a>
                                                </td>
                                            </tr>
                                            <? } ?>
                                        </table>   
                                        </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                            <td class=" pointer" id="mpo"><?=t('Местные партийные организации')?></td>
                                             <td class="cbrown"><a href="/ppo?category=2"><?=count($mpo)?></a></td>
                                    </tr>
                                    <tr>
                                    <td colspan="2" style="padding: 0 !important;">
                                        <table id="mpo_table" class="hide" style="margin-left: 10px;">
                                         <?foreach($mpo as $k=>$v) {?>
                                            <tr>
                                                <td coslpan="2">
                                                    <a href="/ppo<?=$v['id']?>"><?=$v['title']?></a>
                                                </td>
                                            </tr>
                                            <? } ?>   
                                            
                                            
                                        </table>   
                                        </td>
                                    </tr>
                                    <tr>
                                            <td class="pointer" id="ppo"><?=t('Первичные партийные организации')?></td>
                                            <td class="cbrown"><a href="/ppo?category=1"><?=count($ppo)?></a></td>
                                    </tr>
                                    <tr>
                                    <td colspan="2" style="padding: 0 0 15px 0 !important;">
                                        <table id="ppo_table" class="hide" style="margin-left: 10px;">
                                        <?foreach($ppo as $k=>$v) {?>
                                            <tr>
                                                <td coslpan="2">
                                                    <a href="/ppo<?=$v['id']?>"><?=$v['title']?></a>
                                                </td>
                                            </tr>
                                        <? } ?>    
                                            
                                            
                                        </table>   
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%;">
                                <table>
                                    
                                    
                                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                            <td class="bold cbrown" rel="info"><b><?=t('Проинформировано людей')?></b></td>
                                            <td class="aright"></td>
                                            <td class="aright"></td>
                                    </tr>
                                    <tr>
                                            <td  width="280"><?=t('Во время личных встреч')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" href="/results/people?act=ipc" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_private_count) FROM user_desktop")?></a>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td ><?=t('Телефонными звонками')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" href="/results/people?act=ippc" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_phone_count) FROM user_desktop")?></a>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td ><?=t('Электронными письмами')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" href="/results/people?act=ipec" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_email_count) FROM user_desktop")?></a>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td ><?=t('В социальных сетях')?></td>
                                            <td>
                                                   <a target="_blank" href="/results/people?act=ipsc" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_social_count) FROM user_desktop")?></a>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td style="padding-bottom: 20px;"><?=t('Всего')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" href="/results/people?act=ipall" class="cbrown bold"><?=db::get_scalar("SELECT SUM(information_people_social_count)+SUM(information_people_email_count)+SUM(information_people_phone_count)+SUM(information_people_private_count) FROM user_desktop")?></a>
                                            </td>
                                    </tr>
                                </table>
                            </td>
                    </tr>
                    <tr>
                            <td width="50%">
                                
                                 <table width="100%" class="fs12 " style="color:black; margin-bottom: 0px;">
                                     <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                            <td class="bold cbrown" rel="info"><b><?=t('Взносы')?></b></td>
                                            <td class="aright"></td>
                                            <td class="aright"></td>
                                    </tr>
                                    <tr>
                                            <td ><?=t('Вступительные')?></td>
                                            <td class="cbrown">
                                                <?=db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE type=1 AND del=0 AND approve!=0")?>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td ><?=t('Членские')?></td>
                                            <td class="cbrown">
                                                <?=db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE type=2 AND del=0 AND approve!=0")?>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td ><?=t('Благотворительные')?></td>
                                            <td class="cbrown">
                                                <?=db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE type=3 AND del=0 AND approve!=0")?>
                                            </td>
                                    </tr>
                                    <tr>
                                        <td><i><?=t('Всего')?></i></td>
                                            <td class="cbrown">
                                                <?=db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE del=0 AND approve!=0")?>
                                            </td>
                                    </tr>
                                 </table>
                            </td>
                            <td style="width: 50%;">
                                <table>
                                    
                                    
                                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                            <td class="bold cbrown" rel="info"><b><?=t('Наглядная агитация')?></b></td>
                                            <td class="aright"></td>
                                            <td class="aright"></td>
                                    </tr>
                                    <tr>
                                                <td class="" width="280"><?=t('Авторамок')?></td>
                                                <td class="cbrown">
                                                    <a target="_blank" href="/results/people?act=avtonumbers"  class="cbrown">
                                    <?=$autcnt?></a>
                                                </td>
                                    </tr>
                                    <tr>
                                                <td class="" width="280"><?=t('Аватарки с "М"')?></td>
                                                <td class="cbrown">
                                                    <a target="_blank" href="/results/avatarm"  class="cbrown">
                                    <?=$avatarmcnt?></a>
                                                </td>
                                    </tr>
                                    <tr>
                                                <td class="" width="280"><?=t('Другое')?></td>
                                                <td class="cbrown">
                                                    <a target="_blank" href="/results/people?act=naglother"  class="cbrown">
                                    <?=$naglothercnt?></a>
                                                </td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                 </table>
                
                
                
                
                
            </td>
        </tr> 
        <tr>
            <td colspan="2" style="padding-top: 0px;">
                <h1 class="fs28 m0" style="color:#660000;">
                    <?=t('Статистика')?>
                </h1>
            </td>
        </tr>
        <style>#status_table td {padding-left: 25px !important;}</style>
        <tr>
            <td>
                <table width="100%" class="fs12" style="color: black !important;">
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" rel="info" colspan="2" style="width: 350px;"><b><?=t('Люди')?></b></td>
                                
                        </tr>
                        <tr>
                                <td class="pointer" id="status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="text-decoration: underline;"><?=t('По статусу')?></b></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 0 !important;">
                                <table id="status_table" class="hide">
                                    <tr>
                                            <td width="280"><?=t('Членов МПУ')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" class="cbrown" href="/people/index?status=20">
                                                <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE (status=20 OR ban=20)")?>
                                                </a>
                                            </td>
                                    </tr>
                       <!--         <tr>
                                            <td><?=t('Кандидатов у члены МПУ')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" class="cbrown" href="/people/index?status=15">
                                                <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE (status=15 OR ban=15)")?>
                                                </a>
                                            </td>
                                    </tr>-->
                                    <tr>
                                            <td><?=t('Меритократов')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" class="cbrown" href="/people/index?status=10">
                                                <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE (status>=10 OR ban>=10)")?>
                                                </a>
                                            </td>
                                    </tr>
                                     <tr>
                                            <td><?=t('Сторонников')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" class="cbrown" href="/people/index?status=5">
                                                <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE status=5")?>
                                                </a>
                                            </td>
                                    </tr>
                                   <tr>
                                            <td><?=t('Оффлайн анкет сторонников')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" class="cbrown" href="/people/index?offline=all">
                                                <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE offline > 0")?>
                                                </a>
                                            </td>
                                    </tr>
                                      <tr>
                                            <td><?=t('Гостей')?></td>
                                            <td class="cbrown">
                                                <a target="_blank" class="cbrown" href="/people/index?status=1">
                                                <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE status=1")?>
                                                </a>
                                            </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!--                 !!!!!!!!!FUNCTIONS!!!!!!!!!                   -->
                        <tr>
                            <td style="padding-top: 5px;" class="pointer" id="functions">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="text-decoration: underline;"><?=t('По функции')?></b></td>
                                <td class="aright"></td>
                        </tr>
                        <style>#functions_table td {padding-left: 25px !important;}</style>
                        <tr>
                            <td colspan="2" style="padding: 0 !important;">
                                <table id="functions_table" class="hide">
                                    <tr><td class="" width="280"><?=t('Членов Политсовета')?></td><td class="cbrown">
                                        <a href="/people?function=1" target="_blank">
                                        <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{1\}'")?> </a>                      </a>
                                    </td></tr>
                                     <tr><td class=""><?=t('Экспертов')?></td><td class="cbrown">
                                        <a href="/people?expert=1" target="_blank">
                                          <?=db::get_scalar("SELECT count(*) FROM user_auth WHERE expert != '0' AND expert != '' AND active=true")?> </a>                                         </a>
                                     </td></tr>
                                     <tr><td class=""><?=t('Работников Секретариата')?></td><td class="cbrown">
                                        <a href="/people?function=22" target="_blank">
                                          <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{22\}'")?> </a>                                   </a>
                                    </td></tr>
                                    <tr><td class=""><?=t('Координаторов развития региона')?></td><td class="cbrown">
                                        <a href="/people?function=5" target="_blank">
                                           <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{5\}'")?> </a>                                  </a>
                                    </td></tr>
                                    <tr><td class=""><?=t('Координаторов развития района')?></td><td class="cbrown">
                                        <a href="/people?function=6" target="_blank">
                                           <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{6\}'")?>  </a>                                  </a>
                                    </td></tr>
                                    <tr><td class=""><?=t('Логистических координаторов')?></td><td class="cbrown">
                                        <a href="/people?function=18" target="_blank">
                                           <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{18\}'")?>  </a>                                </a>
                                    </td></tr>
                                    <tr><td class=""><?=t('Представителей в ВУЗах')?></td><td class="cbrown">
                                        <a href="/people?function=14" target="_blank">
                                           <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{14\}'")?>  </a>                                 </a>
                                    </td></tr>
                                </table>
                            </td>
                        </tr>
                        
                            <!--                 !!!!!!!!!REGION!!!!!!!!!  
                            
                            -->
                            <style>#regions_table td {padding-left: 25px !important;}</style>
                            <tr>
                                    <td style="padding-top: 5px;" class="pointer" id="regions">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="text-decoration: underline;"><?=t('По региону')?></b></td>
                                    <td class="aright"></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 0 !important;">
                                    <table id="regions_table" class="hide">
                        	<? $all_regions=geo_peer::get_regions(1);
                                foreach ($all_regions  as $region_id => $title ) {
                                    $count_users=db::get_scalar('SELECT count(user_id) FROM user_data WHERE region_id=:region_id AND user_id in (SELECT id FROM user_auth WHERE active=:active)',array('active'=>1,'region_id'=>$region_id));
                                    $rate_regions[$count_users.($region_id%10)]=array('id'=>$region_id, 'title'=>$title, 'count'=>$count_users);
                                    krsort($rate_regions);
                                    }
                                    ?>
                                <? foreach ($rate_regions  as $region ) { ?>
                                              <tr>
                                                  <td class="" style="width:280px;"><?=$region['title']?></td>
                                                  <td class="cbrown">
                                                    <a href="/search?submit=1&country=1&region=<?=$region['id']?>" target="_blank">
                                                    <?=$region['count']?></a>                                
                                                   </td>
                                              </tr>                      
                                 <? } ?>
                                      </table>
                                </td>
                            </tr>
                              
                              
                              
                              
                    <!--                 !!!!!!!!!GENDER!!!!!!!!!                   -->
                                    <style>
                                        #gender_table td {padding-left: 25px !important;}
                                    </style>
                                    <tr>
                                        <td style="padding-top: 5px;" class="pointer" id="gender">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="text-decoration: underline;"><?=t('По гендерному признаку')?></b></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 0 !important;">
                                            <table id="gender_table" class="hide">
                                                <tr>
                                                        <td width="280"><?=t('Мужчин')?></td>
                                                        <td>
                                                        <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true AND user_data.gender='m'")?>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td><?=t('Женщин')?></td>
                                                        <td>
                                                        <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true AND user_data.gender='f'")?>
                                                       </td>
                                                </tr>
                                                <tr>
                                                        <td><?=t('Пол не указан')?></td>
                                                        <td>
                                                        <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true AND (user_data.gender!='m' AND user_data.gender !='f')")?>
                                                       </td>
                                                </tr>
                                                 <tr>
                                                        <td><?=t('Всего')?></td>
                                                        <td>
                                                        <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true")?>
                                                        </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!--                 !!!!!!!!!TARGET!!!!!!!!!                   -->
                                    <style>#target_table td {padding-left: 25px !important;}</style>
                                     <tr>
                                            <td style="padding-top: 5px;"  class="pointer" id="target">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="text-decoration: underline;"><?=t('По целевой группе')?></b></td>
                                            <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 0 !important;">
                                            <table id="target_table" class="hide">
                                             <?
                                             $targets=user_helper::get_targets();
                                             foreach($targets as $k=>$t)
                                                 $rait_targets[db::get_scalar('SELECT count(*) 
                                                                    FROM user_data WHERE target && :target',
                                                                        array('target'=>'{'.$k.'}'))]=array("function_id"=>$k,"function_title"=>$t);
                                             krsort($rait_targets);
                                             foreach ($rait_targets as $count=>$data) { if($data['function_id']==24)continue; ?>
                                                <tr>
                                                        <td><?= $data['function_title'] ?></td>
                                                        <td>
                                                            <a target="_blank" href="/people?target=<?= $data['function_id'] ?>">
                                                                <?=$count?>
                                                            </a> 
                                                        </td>
                                                </tr>
                                                <? } ?>
                                            </table>
                                        </td>
                                    </tr>
            
            
                </table>   
            </td>
            <td>
<!--                <table width="100%" class="fs12 " style="color:black">
                            <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                    <td class="bold cbrown" rel="info"><b><?=t('Структуры')?></b></td>
                                    <td class="aright"></td>
                            </tr>
                <tr>
                        <td width="280"><?=t('Политсовет')?></td>
                        <td>
                                 <a href="/people?function=1" target="_blank">
                                    <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{1\}'")?> </a>     
                        </td>
                </tr>
                            <tr>
                        <td ><?=t('ЦКРК')?></td>
                        <td>
                                 <a href="/people?function=2" target="_blank">
                                    <?=db::get_scalar("SELECT count(*) FROM user_desktop WHERE functions && '\{2\}'")?> </a>     
                        </td>
                </tr>
                            <tr>
                        <td ><?=t('Сообщества в "М"')?></td>
                        <td>
                                 <a href="/groups" target="_blank">
                                    <?=db::get_scalar("SELECT count(*) FROM groups WHERE hidden=0")?> </a>     
                        </td>
                </tr>
                </table>-->
            <table width="100%" class="fs12 " style="color:black">
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" rel="info"><b><?=t('Активность в "М"')?></b></td>
                                <td class="aright"></td>
                        </tr>
            <tr>
                <td width="280"><?=t('Привлеченных в "М" по рекомендации')?></td>
                    <td class="cbrown">
                       <a target="_blank" class="cbrown" href="/results/people?act=reci"><?=db::get_scalar("SELECT COUNT(id) FROM user_auth WHERE (invited_by>1 or recomended_by>1) and active is TRUE")?></a>
                   </td>
            </tr>
             <tr>
                    <td ><?=t('Мыслей и тем (в сообществах)')?></td>
                    <td>
                             <a href="/results/people?act=blogs" target="_blank">
                                <?=db::get_scalar("SELECT count(*) FROM blogs_posts WHERE visible=true")?> </a>     
                    </td>
            </tr>
            <tr>
                    <td ><?=t('Комментариев к мыслям и темам (в сообществах)')?></td>
                    <td>
                             <a href="/results/people?act=com" target="_blank">
                                <?=db::get_scalar("SELECT count(*) FROM blogs_comments")?>
                             </a>
                    </td>
            </tr>
            <tr>
                    <td ><?=t('Личных сообщений')?></td>
                    <td>            
            <?=db::get_scalar('SELECT count(id) FROM messages')?>
                    </td>
            </tr>
            <tr>
                    <td><?=t('Опросов')?></td>
                    <td>
                             <a href="/results/people?act=polls" target="_blank">
                                <?=db::get_scalar("SELECT count(*) FROM polls WHERE hidden=0")?>
                             </a>     
                    </td>
            </tr>
                        <tr>
                    <td><?=t('Голосований в опросах')?></td>
                    <td>
                             <a href="/results/people?act=voites" target="_blank">
                                <?=db::get_scalar("SELECT count(*) FROM polls_votes 
                                    WHERE poll_id NOT IN(SELECT id FROM blogs_posts WHERE visible=true)")?>
                             </a>     
                    </td>
            </tr>
            <tr>
            <td><?=t('Позитивных оценок мыслей')?></td>
                    <td>
                             <a href="/results/people?act=vpoz" target="_blank">
               <?=db::get_scalar("SELECT count(*) FROM rate_history
                                    WHERE type=1 AND rate=1 AND object_id
                                    NOT IN(SELECT id FROM blogs_posts WHERE visible=false)")?>
                                 </a>     
                    </td>
            </tr>
            <tr>
            <td><?=t('Негативных оценок мыслей')?></td>
                    <td>
                             <a href="/results/people?act=vneg" target="_blank">
               <?=db::get_scalar("SELECT count(*) FROM rate_history
                                    WHERE type=1 AND rate=-1 AND object_id
                                    NOT IN(SELECT id FROM blogs_posts WHERE visible=false)")?>
                                 </a>     
                    </td>
            </tr>
         
            <tr>
            <td><?=t('Времени в сети')?> (<?=t('часов')?>)</td>
                    <td>
                              <a href="/results/people?act=time" target="_blank">
                               <? $all_network_time=db::get_scalar("SELECT SUM(all_time) FROM user_sessions")?>
                               <?=intval($all_network_time/3600)?>
                             </a>     
                    </td>
            </tr>
            <tr>
            <td><?=t('Кол-во посещений')?></td>
                    <td>
                             <a href="/results/people?act=visits" target="_blank">
               <?=db::get_scalar("SELECT SUM(all_visits) FROM user_sessions")?>
                             </a>     
                    </td>
            </tr>
            
                </table>
<!--                 <table width="100%" class="fs12 " style="color:black;">
            <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                    <td class="bold cbrown" rel="info"><b><?=t('Агитация')?></b></td>
                    <td class="aright"></td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Получено агитматериалов')?></td>
                        <td class="cbrown">
                                <a href="/results/agitation"  class="cbrown"><?=$agitation['receive']?></a>
                        </td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Передано агитматериалов')?></td>
                        <td class="cbrown">
                                <a href="/results/agitation"  class="cbrown"><?=$agitation['given']?></a>
                        </td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Вручено агитматериалов')?></td>
                        <td class="cbrown">
                                <a href="/results/agitation"  class="cbrown"><?=$agitation['presented']?></a>
                        </td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Остаток')?></td>
                        <td class="cbrown">
                                <? $agittotal = $agitation['receive'] - $agitation['given'] - $agitation['presented'] ?>
                                <a href="/results/agitation"  class="cbrown"><?=($agittotal>0)?$agittotal:0?></a>
                        </td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Авторамок')?></td>
                        <td class="cbrown">
                            <a target="_blank" href="/results/people?act=avtonumbers"  class="cbrown">
            <?=$autcnt?></a>
                        </td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Аватарки с "М"')?></td>
                        <td class="cbrown">
                            <a target="_blank" href="/results/avatarm"  class="cbrown">
            <?=$avatarmcnt?></a>
                        </td>
            </tr>
            <tr>
                        <td class="" width="280"><?=t('Другое')?></td>
                        <td class="cbrown">
                            <a target="_blank" href="/results/people?act=naglother"  class="cbrown">
            <?=$naglothercnt?></a>
                        </td>
            </tr>
            <tr>
                <td style="padding-top: 15px;"><b><?=t('Проинформировано людей')?></b></td>
                <td></td>
            </tr>
            <tr>
                    <td  width="280"><?=t('Во время личных встреч')?></td>
                    <td class="cbrown">
                        <a target="_blank" href="/results/people?act=ipc" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_private_count) FROM user_desktop")?></a>
                    </td>
            </tr>
            <tr>
                    <td ><?=t('Телефонными звонками')?></td>
                    <td class="cbrown">
                        <a target="_blank" href="/results/people?act=ippc" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_phone_count) FROM user_desktop")?></a>
                    </td>
            </tr>
            <tr>
                    <td ><?=t('Электронными письмами')?></td>
                    <td class="cbrown">
                        <a target="_blank" href="/results/people?act=ipec" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_email_count) FROM user_desktop")?></a>
                    </td>
            </tr>
            <tr>
                    <td ><?=t('В социальных сетях')?></td>
                    <td>
                           <a target="_blank" href="/results/people?act=ipsc" class="cbrown"><?=db::get_scalar("SELECT SUM(information_people_social_count) FROM user_desktop")?></a>
                    </td>
            </tr>
            <tr>
                    <td ><?=t('Всего')?></td>
                    <td class="cbrown">
                        <a target="_blank" href="/results/people?act=ipall" class="cbrown bold"><?=db::get_scalar("SELECT SUM(information_people_social_count)+SUM(information_people_email_count)+SUM(information_people_phone_count)+SUM(information_people_private_count) FROM user_desktop")?></a>
                    </td>
            </tr>

</table>-->
<!--                <table width="100%" class="fs12 " style="color:black">
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" rel="info"><b><?=t('Стастистика')?></b></td>
                                <td class="aright"></td>
                        </tr>
                        <tr>
                    <td ><b><?=t('По гендерному признаку')?></b></td>
                    <td></td>
            </tr>
                        <tr>
                                <td width="280"><?=t('Мужчин')?></td>
                                <td>
    <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true AND user_data.gender='m'")?>
    </td>
                        </tr>
                        <tr>
                                <td><?=t('Женщин')?></td>
                                <td>
        <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true AND user_data.gender='f'")?>
       </td>
                        </tr>
                        <tr>
                                <td><?=t('Пол не указан')?></td>
                                <td>
        <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true AND (user_data.gender!='m' AND user_data.gender !='f')")?>
       </td>
                        </tr>
                         <tr>
                                <td><?=t('Всего')?></td>
                                <td>
    <?=db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id WHERE user_auth.active=true")?>
    </td>
                        </tr>
             <tr>
                    <td style="padding-top: 15px;"><b><?=t('По целевой группе')?></b></td>
                    <td></td>
            </tr>
         <?
         $targets=user_helper::get_targets();
         foreach($targets as $k=>$t)
             $rait_targets[db::get_scalar('SELECT count(*) 
                                FROM user_data WHERE target && :target',
                                    array('target'=>'{'.$k.'}'))]=array("function_id"=>$k,"function_title"=>$t);
         krsort($rait_targets);
         foreach ($rait_targets as $count=>$data) { if($data['function_id']==24)continue; ?>
            <tr>
                    <td><?= $data['function_title'] ?></td>
                    <td>
                        <a target="_blank" href="/people?target=<?= $data['function_id'] ?>">
                            <?=$count?>
                        </a> 
                    </td>
            </tr>    
    <? } ?>
                </table>-->
</td></tr>
        <tr>
        <td>
           
        </td>
        <td>
                
        </td>
        </tr></table>
	<div class="clear"></div>
</div>
        </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
		$('.tab_pane > a').bind('click', function() {
			$('.tab_pane > a').removeClass('selected');
			$(this).addClass('selected');
			$('.content_pane').hide();
			$('#pane_' + $(this).attr('rel')).show();
			$(this).blur();
		});

    $('.regions').change(function () {
            	var region_id = $(this).val();
		var region_attr_id = $(this).attr('id');
		if (region_id == '0') {
			$('#city_'+region_attr_id).html('');
			$('#city_'+region_attr_id).attr('disabled', true);
			return(false);
		}
		$('#city_'+region_attr_id).attr('disabled', true);
		$('#city_'+region_attr_id).html('<option>завантаження...</option>');

		var url = '/profile/get_select';
		$.post(	url,{"region":region_id},
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = '<option value="">- оберіть місто/район -</option>';
					$(result.cities).each(function() {
						options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
					});
					$('#city_'+region_attr_id).html(options);
					$('#city_'+region_attr_id).attr('disabled', false);
				}
			},
			"json"
		);
	});
        
        $('a.signature_delete').click(function(){
            if (confirm('Точно?'))
            {
                var id = $(this).attr('id');
                $("#div_"+id).hide();
                $.get('/profile/signatures_del',{id:id});
            }

    });
    
     $('#show_add_signature').click(function(){
            $('tr.add_signature').show();
            $('#show_add_signature').hide();
            });
        
		$('.desktop_panel > a').bind('click', function() {
			$('.desktop_panel > a').removeClass('selected');
			$(this).addClass('selected');
			$('.content_pane').hide();
			$('#pane_' + $(this).attr('rel')).show();
			$(this).blur();
		});


$('#show_all_recomended').click(function() {
                       $("#all_recomended").slideDown(70);
                       $("#show_all_recomended").hide();
});

$('#show_all_attracted').click(function() {
                       $("#all_attracted").slideDown(70);
                       $('#show_all_attracted').hide();
});

$('#show_attracted').click(function() {
                if(!$("#attracted").is(":visible"))
                {
                       $("#attracted").slideDown(70);

                }
                else
                {
                       $("#attracted").slideUp(70);
                       $("#all_attracted").slideUp(70);
                }
});
$('#show_recomended').click(function() {
                if(!$("#recomended").is(":visible"))
                {
                       $("#recomended").slideDown(70);

                }
                else
                {
                       $("#recomended").slideUp(70);
                       $("#all_recomended").slideUp(70);
                }
});

$('.meeting_title').click(function() {
                if(!$("#"+this.id+"_description").is(":visible"))
                {
                       $("#"+this.id+"_description").slideDown(70);

                }
                else
                {
                       $("#"+this.id+"_description").slideUp(70);
                }
});
});
$(function(){
    $('td.pointer').each(function() {
        $(this).click(function(){
            $('#'+$(this).attr('id')+'_table').slideToggle(300);
        })
    });
})
</script>
