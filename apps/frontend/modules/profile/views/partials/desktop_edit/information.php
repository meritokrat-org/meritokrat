<style>.agitdata{width:60px}</style>
<form id="information_form" class="form mt10 <?=!session::has_credential('admin') ? '' : 'hidden'?>">
    <? if ( session::has_credential('admin') ) { ?>
            <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
    <? } ?>
    <input type="hidden" name="type" value="information">
    <?//if(session::get_user_id()==5968) {?>
    <table width="100%" class="fs12" >
            <tr><td class="cgray fs11" colspan="2" style="padding-left: 45px;">
                    <span style="padding-left: 10px;padding-right: 50px;">
                    <?=t('В этом разделе, пожалуйста, фиксируйте всю работу по распространению информации об идеях МПУ, которую Вы осуществили путем:')?> </span><br/>
                    <span style="padding-left: 30px;padding-right: 50px;">
                        <?=t('1) непосредственного общения с людьми')?>;</span><br/>
                    <span style="padding-left: 30px;padding-right: 50px;">
        <?=t('2) распространение брошюр')?>;</span><br/>
                    <span style="padding-left: 30px;padding-right: 50px;">
        <?=t('3) размещение электронных баннеров на сайтах (своих или друзей, партнеров)')?>;</span><br/>
                    <span style="padding-left: 30px;padding-right: 50px;">
        <?=t('4) публикации материалов о наших идеи интернет-или печатных изданиях')?>.
                    </span><br/>
                        </td>

            </tr>
            <tr>
                    <td colspan="2" style="padding: 0 0 0 45px;"><span class="ml10 fs18" style="text-decoration:none;"><?=t('Уличная агитация')?></span></td>
            </tr>
            <tr>
                <td colspan="2">
                <?
                if($tent_data) {
                foreach ($tent_data as $k => $tent_info_1) {
    //                if(session::get_user_id()==5968) { var_dump ($tent_data);echo '!!!11111!'; var_dump ($tent_info['date']); }
                ?>
                <table>
                    <tr>
                        <td class="aright" width="19%">
                            <span class="aright"><?=t('Количество часов')?></span>
                        </td>
                        <td id='tent_hours'>
                            <?=tag_helper::select('tent_hours[]', range(1, 24, 1),array('value'=>(intval($tent_info_1['hours'])-1)))?>
                        </td>
                    </tr>
                                </tr>
                    <tr<?//=$publication['publ']==1 ? '' : 'class="hide"'?>>
                            <td class="aright"><?=t('Дата')?></td>
                            <td id='tent_date'>
                                <? /*if($tent_info['date']){
                                   $dparts = explode('/',$tent_info['date']);
                                   var_dump($tent_info['date']);
                                   $tent_info['date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);

                                }*/?>
                                <?=user_helper::datefields('tent_date',$tent_info_1['date'],true)?>
                            </td>
                    </tr>
                    <tr>
                        <td class="aright" width="19%">
                            <span class="aright"><?=t('Детали')?>&nbsp;(<?=t('название, место проведения и т.д.')?>)</span>
                        </td>
                        <td>
                            <textarea name="tent_details[]"><?=$tent_info_1['description']?></textarea>
                        </td>
                    </tr>
                </table>
                <? } }?>

                <table  id="agitation_tent">
                    <tr>
                        <td class="aright" width="19%">
                            <span class="aright"><?=t('Количество часов')?></span>
                        </td>
                        <td id='tent_hours'>
                            <?=tag_helper::select('tent_hours[]', range(1, 24, 1))?>
                        </td>
                    </tr>
                                </tr>
                    <tr<?//=$publication['publ']==1 ? '' : 'class="hide"'?>>
                            <td class="aright"><?=t('Дата')?></td>
                            <td id='tent_date'>
                                <? if($tent_info['date']){
                                   $dparts = explode('/',$tent_info['date']);
                                   $tent_info['date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);
                                }?>
                                <?=user_helper::datefields('tent_date',intval($tent_info['date']),true)?>
                            </td>
                    </tr>
                    <tr>
                        <td class="aright" width="19%">
                            <span class="aright"><?=t('Детали')?>&nbsp;(<?=t('название, место проведения и т.д.')?>)</span>
                        </td>
                        <td>
                            <textarea name="tent_details[]"></textarea>
                        </td>
                    </tr>
                    
                </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <input type="button" class="button" id="add_tent" value="+ <?=t('Добавить')?>">
        </td>
    </tr>
</table>








    <table width="100%" class="fs12" >
            <tr>
                    <td colspan="2" style="padding: 0 0 0 45px;"><span class="ml10 fs18" style="text-decoration:none;"><?=t('Агитация в интернете')?></span></td>
            </tr>
            <tr>
                <td colspan="2">

                <?
                if($inet_data) {
//                    echo "<pre>";
//                    var_dump($inet_data);
//                    exit;
                
                foreach ($inet_data as $k => $inet_info_1) {
        //                if(session::get_user_id()==5968) { var_dump ($tent_data);echo '!!!11111!'; var_dump ($tent_info['date']); }
                ?>
                    <table>
                        <tr>
                            <td class="aright" width="19%">
                                <span class="aright"><?=t('Количество часов')?></span>
                            </td>
                            <td id='inet_hours'>
                                <?=tag_helper::select('inet_hours[]', range(1, 24, 1),array('value'=>(intval($inet_info_1['hours'])-1)))?>
                            </td>
                        </tr>
                        <tr<?//=$publication['publ']==1 ? '' : 'class="hide"'?>>
                                <td class="aright"><?=t('Дата')?></td>
                                <td id='inet_date'>
                                    <? /*if($tent_info['date']){
                                       $dparts = explode('/',$tent_info['date']);
                                       var_dump($tent_info['date']);
                                       $tent_info['date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);

                                    }*/?>
                                    <?=user_helper::datefields('inet_date',$inet_info_1['date'],true)?>
                                </td>
                        </tr>
                        <tr>
                            <td class="aright" width="19%">
                                <span class="aright"><?=t('Детали')?>&nbsp;(<?=t('название, место проведения и т.д.')?>)</span>
                            </td>
                            <td>
                                <textarea name="inet_details[]"><?=$inet_info_1['description']?></textarea>
                            </td>
                        </tr>
                    </table>
                    <? } }?>

                    <table  id="agitation_inet">
                        <tr>
                            <td class="aright" width="19%">
                                <span class="aright"><?=t('Количество часов')?></span>
                            </td>
                            <td id='inet_hours'>
                                <?=tag_helper::select('inet_hours[]', range(1, 24, 1))?>
                            </td>
                        </tr>
                                    </tr>
                        <tr<?//=$publication['publ']==1 ? '' : 'class="hide"'?>>
                                <td class="aright"><?=t('Дата')?></td>
                                <td id='inet_date'>
                                    <? if($tent_info['date']){
                                       $dparts = explode('/',$tent_info['date']);
                                       $tent_info['date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);
                                    }?>
                                    <?=user_helper::datefields('inet_date',intval($tent_info['date']),true)?>
                                </td>
                        </tr>
                        <tr>
                            <td class="aright" width="19%">
                                <span class="aright"><?=t('Детали')?>&nbsp;(<?=t('название, место проведения и т.д.')?>)</span>
                            </td>
                            <td>
                                <textarea name="inet_details[]"></textarea>
                            </td>
                        </tr>
                    </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="button" class="button" id="add_inet" value="+ <?=t('Добавить')?>">
            </td>
        </tr>
    </table>

<?// } ?>

    <table width="100%" class="fs12" id="agitation_inform">
            <tr>
                    <td class="aleft ml15" colspan="2" style="padding: 20px 0 0 45px;">
                        <span class="ml10 fs18" style="text-decoration:none;"><?=t('Проинформировано людей')?></span>
                    </td>
            </tr>
            <tr>
                    <td class="aleft ml15" width="25%" style="padding-left: 55px;"><?=t('Во время личных встреч')?></td>
                    <td>
                            <a href="#input" class="bold"><?=intval($user_desktop['information_people_private_count'])?></a>
                            <input type="text" name="information_people_private_count" value="<?=intval($user_desktop['information_people_private_count'])?>" class="hide linkval" style="width:50px;"/>
                            <input type="button" value="OK" class="fs11 button hide linkval" />
                            <span>+</span>
                            <input type="text" value="0" class="addval" />
                            <input type="button" id="people_private_addval" name="people_private_addval" value="<?=t('Додати')?>" class="fs11 button addval" />
                    </td>
            </tr>
            <tr>
                    <td class="aleft ml15" width="25%" style="padding-left: 55px;"><?=t('С помощью телефонных звонков')?></td>
                    <td>
                            <a href="#input" class="bold"><?=intval($user_desktop['information_people_phone_count'])?></a>
                            <input type="text" name="information_people_phone_count" value="<?=intval($user_desktop['information_people_phone_count'])?>" class="hide linkval" style="width:50px;"/>
                            <input type="button" value="OK" class="fs11 button hide linkval" />
                            <span>+</span>
                            <input type="text" value="0" class="addval" />
                            <input type="button" id="people_phone_addval" name="people_phone_addval" value="<?=t('Додати')?>" class="fs11 button addval" />
                    </td>
            </tr>
            <tr>
                    <td class="aleft ml15" width="25%" style="padding-left: 55px;"><?=t('С помощью электронных писем')?></td>
                    <td>
                            <a href="#input" class="bold"><?=intval($user_desktop['information_people_email_count'])?></a>
                            <input type="text" name="information_people_email_count" value="<?=intval($user_desktop['information_people_email_count'])?>" class="hide linkval" style="width:50px;"/>
                            <input type="button" value="OK" class="fs11 button hide linkval" />
                            <span>+</span>
                            <input type="text" value="0" class="addval" />
                            <input type="button" id="people_email_addval" name="people_email_addval" value="<?=t('Додати')?>" class="fs11 button addval" />
                    </td>
            </tr>
            <tr>
                    <td class="aleft ml15" width="25%" style="padding-left: 55px;"><?=t('С помощью социальных сетей')?></td>
                    <td>
                            <a href="#input" class="bold"><?=intval($user_desktop['information_people_social_count'])?></a>
                            <input type="text" name="information_people_social_count" value="<?=intval($user_desktop['information_people_social_count'])?>" class="hide linkval" style="width:50px;"/>
                            <input type="button" value="OK" class="fs11 button hide linkval" />
                            <span>+</span>
                            <input type="text" value="0" class="addval" />
                            <input type="button" id="people_social_addval" name="people_social_addval" value="<?=t('Додати')?>" class="fs11 button addval" />
                    </td>
            </tr>
            <tr>
                    <td class="aright fs18" width="25%"><?=t('Всего')?></td>
                    <td>
                        <span id="all_people_count" class="cgray fs18"><?=($user_desktop['information_people_private_count']+$user_desktop['information_people_phone_count']+$user_desktop['information_people_email_count']+$user_desktop['information_people_social_count']) ?></span>
                    </td>
            </tr>
    </table>        
    <table width="100%" class="fs12" id="agitation_statistics">
            <tr>
                    <td colspan="2" style="padding: 0 0 0 45px;"><span class="ml10 fs18" style="text-decoration:none;"><?=t('Агитационные материалы МПУ')?></span></td>
            </tr>

            <tr>
                    <td colspan="2">
                    <table width="100%" class="fs12" style="margin-bottom:0">
                    <tr>
                        <td class="aright" width="19%">
                            <span style="text-decoration:none;font-weight: bold;"><?=t('Тип агитматериалов')?></span>
                        </td>
                        <td>
                            <?=tag_helper::select('agitmat', user_helper::get_agimaterials(), array('id'=>'agitmat'), array_flip(user_helper::get_agimaterials()))?>
                        </td>
                    </tr>
                    </table>
                        <hr style="width: 650px; margin: 10px 0 0 50px;">
                    </td>
            </tr>
            <tr>
                <td colspan="2">
                <? $numm=0 ?>
                <? foreach(user_helper::get_agimaterials() as $k=>$v){ ?>
                    <? $aitem = user_agitmaterials_peer::instance()->get_user($user_desktop['user_id'],$k) ?>
                    <div id="agitmat_<?=$k?>" class="agitmat <?=($numm)?'hide':''?>">
                    <table width="100%" class="fs12" id="">

                            <tr>
                                    <td class="aright" width="19%"><?=t('Получил')?></td>
                                    <td>
                                            <input type="hidden" class="at" value="<?=$k?>"/>
                                            <input type="hidden" class="atp" value="receive"/>
                                            <a href="javascript:;" class="changeval bold"><?=intval($aitem['receive'])?></a>
                                            <? if(!(is_array($user_functions) && in_array(5,$user_functions) && !session::has_credential('admin'))){ ?>
                                            <span class="changeval">+</span>
                                            <input type="text" value="0" class="addval" />
                                            <?=t('Дата')?>:
                                            <input type="text" id="agitmaterial_receive_data_<?=$k?>" class="agitdata" />
                                            <input type="button" value="<?=t('Сохранить')?>" class="fs11 button addagit" />
                                            <?=tag_helper::image('common/loading.gif', array('class'=>'hide','width' => 15,'align' => 'absmiddle'));?>
                                            <? } ?>
                                    </td>
                            </tr>
                            <tr><td class="cgray fs11" colspan="2">
                                    <div class="left" style="padding-left: 80px;padding-right: 50px;">
                                        <? if(!in_array(18,$user_functions)){ ?>
                                            <?=t('в это поле введите количество агитматериалов, переданных Вам логистическим координатором, и нажмите кнопку "Сохранить"')?>
                                        <? }else{ ?>
                                            <?=t('количество агитматериалов, переданных Вам Секретариатом МПУ')?>
                                        <? } ?>
                                    </div>
                                </td>
                            </tr>

                            <? if (@in_array(18,$user_functions) OR ($user_desktop['user_id']==session::get_user_id() && session::has_credential('admin'))) { ?>
                            <tr>
                                    <td class="aright" width="19%"><?=t('Передал')?></td>
                                    <td>
                                            <input type="hidden" class="at" value="<?=$k?>"/>
                                            <input type="hidden" class="atp" value="given"/>
                                            <a href="javascript:;" class="changeval bold"><?=intval($aitem['given'])?></a>
                                            <span class="changeval">+</span>
                                            <input type="text" value="0" class="addval" />
                                            <?=t('Дата')?>:
                                            <input type="text" id="agitmaterial_given_data_<?=$k?>" class="agitdata" />
                                            <?=t('Профиль')?>:
                                            <input type="text" class="prof" style="width:60px" />
                                            <input type="button" value="<?=t('Сохранить')?>" class="fs11 button addagit" />
                                            <?=tag_helper::image('common/loading.gif', array('class'=>'hide','width' => 15,'align' => 'absmiddle'));?>
                                    </td>
                             </tr>
                             
                            <tr><td class="cgray fs11" colspan="2">
                                    <div class="left" style="padding-left: 80px;padding-right: 50px;">
                                <?=t('в это поле введите, какое количество полученных агитматериалов Вы передали участнику сети, укажите его ID и нажмите кнопку "Сохранить"')?>
                                    </div>
                                        </td>

                            </tr>
                            <? }else{ ?>
                                <? $aitem['given']=0 ?>
                            <? } ?>

                            <tr>
                                    <td class="aright" width="19%"><?=t('Вручил')?></td>
                                    <td>
                                            <input type="hidden" class="at" value="<?=$k?>"/>
                                            <input type="hidden" class="atp" value="presented"/>
                                            <a href="javascript:;" class="changeval bold"><?=intval($aitem['presented'])?></a>
                                            <span class="changeval">+</span>
                                            <input type="text" value="0" class="addval" />
                                            <?=t('Дата')?>:
                                            <input type="text" id="agitmaterial_presented_data_<?=$k?>" class="agitdata" />
                                            <? if(session::has_credential('admin')){ ?>
                                            <?=t('Пометка')?>:
                                            <?=tag_helper::select('presentedprof', array('0'=>'&mdash;','9999999'=>t('Расход офиса')),array('class'=>'agitdata presentedprof'))?>
                                            <? } ?>
                                            <input type="button" value="<?=t('Сохранить')?>" class="fs11 button addagit" />
                                            <?=tag_helper::image('common/loading.gif', array('class'=>'hide','width' => 15,'align' => 'absmiddle'));?>
                                    </td>
                            </tr>
                            <tr><td class="cgray fs11" colspan="2">
                                    <div class="left" style="padding-left: 80px;padding-right: 50px;">
                            <?=t('в это поле введите, какое количество агитматериалов Вы передали активистам (не участникам сети), и нажмите кнопку "Сохранить"')?>
                                    </div>
                                  </td>
                            </tr>
                            <tr class="agittotal">
                                    <td class="aright" width="19%"><?=t('Осталось')?></td>
                                    <td>
                                            <a href="javascript:;" class="bold">
                                                <? $num = (intval($aitem['receive']) - intval($aitem['given']) - intval($aitem['presented'])) ?>
                                                <?=($num>0)?$num:0?>
                                            </a>
                                    </td>
                            </tr>
                            <tr><td class="cgray fs11" colspan="2">
                                    <div class="left" style="padding-left: 80px;padding-right: 50px;">
                                        <?=t('остаток агитматериалов. Чтобы получить агитационные материалы, обратитесь в секретариат МПУ')?>
                                        <? if(session::has_credential('admin')){ ?>
                                        <br/><br/>
                                        <a href="/results/agitation_user?user_id=<?=$user_desktop['user_id']?>&type=<?=$k?>" target="_blank" class="button" style="padding:4px"><?=t('Статистика по типу')?></a>
                                        <a href="/results/agitation_user?user_id=<?=$user_desktop['user_id']?>" target="_blank" class="button ml10" style="padding:4px"><?=t('Общая статистика')?></a>
                                        <? } ?>
                                    </div>
                                  </td>
                            </tr>
                            <tr>
                                <td class="cgray fs11" colspan="2">
                                    <div class="left" style="padding-left: 80px;padding-right: 50px;padding-top:10px">
                                        *<?=t('По состоянию на')?> 17.06.2011:
                                        <?=t('вручено')?>: <?=$user_desktop['information_brochure_presented']?>
                                        <? if (@in_array(18,$user_functions) OR ($user_desktop['user_id']==session::get_user_id() && session::has_credential('admin'))) { ?>
                                            , <?=t('передано')?>: <?=$user_desktop['information_brochure_given']?>
                                        <? } ?>
                                    </div>
                                  </td>
                            </tr>
                    </table>
                    </div>
                <? $numm++ ?>
                <? } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
    <table width="100%" class="fs12" id="banners">
            <tr>
                <td class="aright mr15" width="19%"><span class="mr15 fs18" style="text-decoration:none;"><?=t('Баннеры')?>&nbsp;</span></td>
                    <td></td>
            </tr>
            <tr><td class="cgray fs11" colspan="2">
                    <div class="left" style="padding-left: 80px;padding-right: 50px;">
            <?=t('В поле «Ссылка» введите ссылку на страницу сайта, где по Вашей помощи был размещен баннер МПУ, в поле «Комментарий» укажите, является ли это Ваш собственный сайт или сайт Ваших друзей / знакомых, как долго баннер сможет там находиться и другую необходимую, по Вашему мнению, информацию.')?><br/>
            <?=t('Если Вы разместили более одного баннера, нажмите кнопку «Добавить баннер».')?>
                    </div>
                        </td>

            </tr>
            <?  $bannercount=0;
            if (is_array(unserialize($user_desktop['information_banners'])) && count(unserialize($user_desktop['information_banners']))>0) {
                foreach (unserialize($user_desktop['information_banners']) as $banner) {
            ?>
            <tr>
                    <td class="aright"><?=t('Название')?></td>
                    <td>
                        <input name="b_title[]" rel="<?=t('')?>" class="text" type="text" value="<?=$banner['title']?>" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Ссылка')?></td>
                    <td>
                        <input name="b_url_<?=$bannercount?>" rel="<?=t('')?>" class="text" type="text" value="<?=$banner['url']?>" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Комментарий')?></td>
                    <td>
                        <textarea style="height: 35px; width: 400px;" name="b_description_<?=$bannercount?>"><?=stripslashes(htmlspecialchars($banner['description']))?></textarea>
                    </td>
            </tr>
            <? $bannercount++;  } ?>
            <? } ?>
            <tr>
                    <td class="aright"><?=t('Название')?></td>
                    <td>
                        <input name="b_title[]" rel="<?=t('')?>" class="text" type="text" value="" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Ссылка')?></td>
                    <td>
                        <input name="b_url_<?=$bannercount?>" rel="<?=t('')?>" class="text" type="text" value="" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Комментарий')?></td>
                    <td>
                        <textarea style="height: 35px; width: 400px;" name="b_description_<?=$bannercount?>"></textarea>
                    </td>
            </tr>
    </table>
                </td>
            </tr>

            <tr style="margin-top: -10px;">
                <td> </td>
                <td>
            <a class="button" id="add_banner">+ <?=t('Добавить баннер')?></a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
     <table width="100%" class="fs12" id="publications">
            <tr>
                    <td class="aright" width="20%"><span class="ml10 fs18" style="text-decoration:none;"><?=t('Публикации')?></span></td>
                    <td class="acenter"></td>
            </tr>
            <tr><td class="cgray fs11" colspan="2">
                    <div class="left" style="padding-left: 80px;padding-right: 50px;">
            <?=t('Приведите информацию относительно публикаций об идеях меритократии и деятельность нашей команды, которые были осуществлены Вами или с Вашей помощью в интернет-или печатных изданиях.')?><br/>
            <?=t('Если таких публикаций было несколько, воспользуйтесь кнопкой «Добавить публикацию».')?>
                    </div>
                        </td>

            </tr>
            <? $publicationcount=0;
                $types = user_desktop_peer::instance()->get_publication_types();
                $types['']="&mdash;";
                ksort($types);
                if (is_array(unserialize($user_desktop['information_publications'])) && count(unserialize($user_desktop['information_publications']))>0) {
                foreach (unserialize($user_desktop['information_publications']) as $publication) {                        ?>
            <tr>
            <td class="aright"><?=t('Вид')?></td>
            <td>
            <input type="checkbox" name="publ[<?=$publicationcount?>]" value="1" class="publ" <?=$publication['publ']==1 ? 'checked' : ''?> /> <?=t('Печатная')?><input type="checkbox" name="purl[<?=$publicationcount?>]" value="1" class="purl" <?=$publication['purl']==1 ? 'checked' : ''?>/> <?=t('Интернет')?>
            </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Название публикации')?></td>
                    <td>
                        <input name="p_title[<?=$publicationcount?>]" class="text" type="text" value="<?=mb_substr($publication['title'],0,100,'UTF-8')?>" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Тип')?></td>
                    <td><?=tag_helper::select('p_type['.$publicationcount.']', $types, array('use_values' => false, 'value'=>$publication['type'], 'class'=>'p_types', 'id'=>'p_type['.$publicationcount.']'))?>
                    <div class="mt5 <?=$publication['type']==16 ? '' : 'hide'?>" id="another_p_type_<?=$publicationcount?>">
                    <input type="text" name="another_p_type[<?=$publicationcount?>]" value="<?=$publication['another_type']?>" >
                    </div>
                    </td>
            </tr>
            <tr <?//=$publication['publ']==1 ? '' : 'class="hide"'?>>
                    <td class="aright"><?=t('Название СМИ')?></td>
                    <td>
                        <input name="p_media_name[<?=$publicationcount?>]" rel="<?=t('')?>" class="text" type="text" value="<?=$publication['media_name']?>" />
                    </td>
            </tr>
            <tr <?//=$publication['publ']==1 ? '' : 'class="hide"'?>>
                    <td class="aright"><?=t('Дата')?></td>
                    <td>
                        <? if($publication['date']){
                           $dparts = explode('/',$publication['date']);
                           $publication['date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);
                        }?>
                        <?=user_helper::datefields('p_date',intval($publication['date']),true)?>
                    </td>
            </tr>
            <tr <?=$publication['purl']==1 ? '' : 'class="hide"'?>>
                    <td class="aright"><?=t('Ссылка')?></td>
                    <td>
                        <input name="p_url[<?=$publicationcount?>]" class="text" type="text" value="<?=$publication['url']?>" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Комментарий')?></td>
                    <td>
                        <textarea style="height: 35px; width: 400px;" name="p_description[<?=$publicationcount?>]"><?=stripslashes(htmlspecialchars($publication['description']))?></textarea>
                    </td>
            </tr>
            <? $publicationcount++;   } ?>
            <? } ?>
            <tr>
            <td class="aright"><?=t('Вид')?></td>
            <td>
            <input type="checkbox" name="publ[<?=$publicationcount?>]" value="1" class="publ" /> <?=t('Печатная')?><input type="checkbox" name="purl[<?=$publicationcount?>]" value="1" class="purl" /> <?=t('Интернет')?>
            </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Название публикации')?></td>
                    <td>
                        <input name="p_title[<?=$publicationcount?>]" rel="<?=t('')?>" class="text" type="text" value="" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Тип')?></td>
                    <td><?=tag_helper::select('p_type['.$publicationcount.']', $types, array('use_values' => false, 'class'=>'p_types', 'id'=>'p_type['.$publicationcount.']'))?>
                    <div class="mt5 hide" id="another_p_type_<?=$publicationcount?>">
                    <input type="text" name="another_p_type[<?=$publicationcount?>]" >
                    </div>
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Название СМИ')?></td>
                    <td>
                        <input name="p_media_name[<?=$publicationcount?>]" rel="<?=t('')?>" class="text" type="text" value="" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Дата')?></td>
                    <td>
                        <?=user_helper::datefields('p_date',0,true)?>
                    </td>
            </tr>
            <tr class="hide">
                    <td class="aright"><?=t('Ссылка')?></td>
                    <td>
                        <input name="p_url[<?=$publicationcount?>]" rel="<?=t('')?>" class="text" type="text" value="" />
                    </td>
            </tr>
            <tr>
                    <td class="aright"><?=t('Комментарий')?></td>
                    <td>
                        <textarea style="height: 35px; width: 400px;" name="p_description[<?=$publicationcount?>]"></textarea>
                    </td>
            </tr>
    </table>
                   </td>
            </tr>
            <tr  style="margin-top: -10px;" class="mb15">
                <td> </td>
                <td>
                <a class="button" id="add_publication">+ <?=t('Добавить публикацию')?> </a>
                </td>
            </tr>
            <!--tr class="mt15 mb15">
                    <td class="aright">&nbsp;</td>
                    <td>
                    </td>
            </tr>
            <tr class="mt15 mb15">
                    <td class="aright" width="20%"><?=t('Авторамки')?></td>
                    <td>
                            <a href="#input" class="bold"><?=intval($user_desktop['information_avtonumbers'])?></a>
                            <input type="text" name="information_avtonumbers" value="<?=intval($user_desktop['information_avtonumbers'])?>" class="hide linkval" style="width:50px;"/>
                            <input type="button" value="OK" class="fs11 button hide linkval" />
                            <span>+</span>
                            <input type="text" value="0" class="addval" />
                            <input type="button" value="<?=t('Додати')?>" class="fs11 button addval" />
                            <? /* <input type="button" class="fs11 button" onclick="$('#information_form').hide();$('#photo_form').show();" value="<?=t('Добавить фото')?>"/> */ ?>
                    </td>
            </tr-->
            <tr class="mt15 mb15">
                <td colspan="2" class="mb15 mt15">&nbsp;</td>
            </tr>
            <tr class="mt15">
                    <td></td>
                    <td>
                            <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                            <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                            <?=tag_helper::wait_panel('information_wait') ?>
                            <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                    </td>
            </tr>
    </table>
</form>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('#agitmat').change(function(){
        $('.agitmat').hide();
        $('#agitmat_'+$(this).val()).show();
    });
    $('input.agitdata').each(function(){
        $('#'+$(this).attr('id')).datepicker({
            changeMonth: true,
            changeYear: true,
            autoSize: true,
            showOptions: {direction: 'left'},
            dateFormat: 'dd-mm-yy',
            shortYearCutoff: 90,
            yearRange: '2010:2025',
            firstDay: true
        });
    });
    $('a[href$="#input"]').click(function(){
        var $this = $(this);
        var obj = $this.next().next('input');
        obj.unbind('click').click(function(){
            $this.siblings('.linkval').hide();
            $this.html(_intv($(this).prev('input').val())).show();
            $this.siblings('.addval, span').show();
        });
        $this.next().val(_intv($(this).html()));
        $this.hide().siblings('.addval, span').hide().end().siblings('.linkval').show();
        return false;
    });
    $('input.addval[type="button"]').click(function(){
        var linkval = _intv($(this).siblings('a').html());
        var addval = _intv($(this).prev().val());
        var all_count = _intv($('#all_people_count').html());
        $(this).siblings('a').html(linkval+addval);
        $(this).siblings('a').next().val(linkval+addval);
        $(this).prev().val(0);
        if ($(this).attr('id')=='people_private_addval' || $(this).attr('id')=='people_phone_addval' || $(this).attr('id')=='people_email_addval' || $(this).attr('id')=='people_social_addval')
        {
            $('#all_people_count').html(all_count+addval);
        }
    });
    <? if(session::has_credential('admin')){ ?>
    $('a.changeval').click(function(){
        $html = '<input type="text" style="width:50px;" value="'+$(this).html()+'"/><input type="button" onclick="appendval(this)" value="OK" class="fs11 ml5 button" />';
        $($html).insertAfter($(this));
        $(this).parent().find('.changeval, .addval').hide();
    });
    <? } ?>
    $('input.addagit[type="button"]').click(function(){
        var $this = $(this);
        var user = '<?=$user_desktop['user_id']?>';
        var $img = $this.siblings('img');
        var ostatok = _intv($this.parent().parent().siblings('tr.agittotal').find('a').html());
        var agitval = $this.siblings('input.at').val();
        var agittype = $this.siblings('input.atp').val();
        var linkval = _intv($this.siblings('a').html());
        var addval = _intv($this.siblings('input.addval').val());

        if(!addval || addval<0){
            $this.siblings('input.addval').val(0);
            alert('<?=t('Пожалуйста, укажите колчество материалов')?>');
            return false;
        }
        if(ostatok<addval && agittype!='receive'){
            alert('<?=t('У Вас недостаточно материалов')?>');
            return false;
        }
        var datval = $this.siblings('input.agitdata').val();
        if(!datval){
            alert('<?=t('Пожалуйста, укажите дату')?>');
            return false;
        }
        var profval = 0;
        if($this.siblings('input.prof').length>0){
            profval = _intv($this.siblings('input.prof').val());
            if(!profval){
                alert('<?=t('Пожалуйста, укажите ID участника')?>');
                return false;
            }
        }
        if($this.siblings('select.presentedprof').length>0)
        {
            profval = $this.siblings('select.presentedprof').val();
        }

        if(confirm('<?=t('Вы уверены?')?>'))
        {
            $this.attr('disabled',true);
            $img.show();

            $.post('/profile/addagitation',{
                'agitation':agitval,
                'type':agittype,
                'value':linkval,
                'plus':addval,
                'data':datval,
                'profile':profval,
                'user':user
            },function(responce){
                $this.removeAttr('disabled');
                $img.hide();
                var res = eval('('+responce+')');
                if(res.result){
                    $this.siblings('a').html(linkval+addval);
                    var newtotal;
                    if(agittype!='receive'){
                        newtotal = ostatok-addval;
                    }else{
                        newtotal = ostatok+addval;
                    }
                    $this.parent().parent().siblings('tr.agittotal').find('a').html(newtotal);
                    $this.siblings('input.addval').val(0);
                    $this.siblings('input.agitdata').val('');
                    $this.siblings('input.prof').val('')
                }
            });
        }else{
            return false;
        }
    });
    
});
function _intv(val)
{
    var val = parseInt(val);
    if(isNaN(val))
        return 0;
    else
        return val;
}
function appendval(obj)
{
    var $this = $(obj);
    var user = '<?=$user_desktop['user_id']?>';
    var $img = $this.siblings('img');
    var agitval = $this.siblings('input.at').val();
    var agittype = $this.siblings('input.atp').val();
    var changeval = _intv($this.prev().val());
    var $next = $this.next();
    var $total = $next.parent().parent().siblings('tr.agittotal').find('a');

    if(!changeval || changeval<0){
        $this.prev().val(0);
        alert('<?=t('Пожалуйста, укажите колчество материалов')?>');
        return false;
    }

    $this.attr('disabled',true);
    $img.show();

    $.post('/profile/addagitation',{
        'agitation':agitval,
        'type':agittype,
        'user':user,
        'changeval':changeval,
        'change':1
    },function(responce){
        $this.removeAttr('disabled');
        $img.hide();
        var res = eval('('+responce+')');
        if(res.result){
            $next.siblings('a').html(changeval);
            $total.html(res.sum);
            $next.siblings('input.addval').val(0);
            $next.siblings('input.agitdata').val('');
            $next.siblings('input.prof').val('');
        }
    });
    $next.parent().find('.changeval, .addval').show();
    $this.prev().remove();
    $this.remove();
}
$("#add_tent").click(function(){
//    banner += 1 ;
$("#agitation_tent").append("<tr>\n\
    <td class=\"aright\"><?=t('Количество часов')?></td><td>\n\
    "+$('#tent_hours').html()+"<?//=tag_helper::select('tent_hours[]', range(1, 24, 1))?>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Дата')?></td>\n\
    <td>"+$('#tent_date').html()+"<?//=user_helper::datefields('tent_date',intval($tent_info['date']),true)?>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Детали')?>&nbsp;(<?=t('название, место проведения и т.д.')?>)</td>\n\
    <td>\n\
    <textarea style=\"height: 35px; width: 400px;\" name=\"tent_details[]\"></textarea>\n\
    </td>\n\
    </tr>");
    });

    $("#add_inet").click(function(){
//    banner += 1 ;
$("#agitation_inet").append("<tr>\n\
    <td class=\"aright\"><?=t('Количество часов')?></td><td>\n\
    "+$('#inet_hours').html()+"<?//=tag_helper::select('tent_hours[]', range(1, 24, 1))?>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Дата')?></td>\n\
    <td>"+$('#inet_date').html()+"<?//=user_helper::datefields('tent_date',intval($tent_info['date']),true)?>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Детали')?>&nbsp;(<?=t('название, место проведения и т.д.')?>)</td>\n\
    <td>\n\
    <textarea style=\"height: 35px; width: 400px;\" name=\"inet_details[]\"></textarea>\n\
    </td>\n\
    </tr>");
    });
</script>