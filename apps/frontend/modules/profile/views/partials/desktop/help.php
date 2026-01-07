<style>
    a.hoverdown:hover {
        background: url("/static/images/icons/down_icon_gray.png") no-repeat scroll right center transparent;
        color: #999999;
    }
    a.hoverdown {
        background: url("/static/images/icons/down_icon_brown.png") no-repeat scroll right center transparent;
        color: black;
        padding-right: 12px;
        font-style: normal !important;
    }
</style>

<table width="100%" class="fs12">
     <tr>
         <td style=" width: 80%;">
             <? if (session::get_user_id()==$user_desktop['user_id']) { ?>
             <div class="fs11 quiet" style="text-align: justify;color: #333333; width: 110%;">
                 <div class="show mt10">Якщо Ви готові приділяти частину Вашого часу наданню <b>БЕЗКОШТОВНОЇ</b> допомоги членам мерітократичної команди, або Вам потрібна допомога, будь ласка, заповніть цей розділ. Вкажіть, яку саме допомогу Ви готові надавати і скільки годин на тиждень Ви готові цьому присвячувати.</div>
                 
                 <div class="cut mt10" style="display:none;">
                     <p>Під допомогою тут ми розуміємо як послуги (наприклад, консультування з юридичних питань, з питань розвитку бізнесу і т.ін.), так і роботи (наприклад, для системних адміністраторів та програмістів - налаштування домашньої комп’ютерної техніки, для дизайнерів – розробка  макетів візитівок і т.п.).</p>
                     <p>Також у цьому розділі  Ви зможете публікувати міні-звіти про вже надану Вам допомогу, а члени мерітократичної команди, яким Ви допомагали – відгуки про Вашу роботу.</p>
                     Якщо в певний момент у Вас тимчасово не буде можливості надавати допомогу (наприклад, Ви їдете у відпустку або у цьому місяці вже вичерпався час, відведений Вами на надання безкоштовної допомоги) – скористайтесь галочкою «Тимчасово не надаю допомогу» і не забудьте зняти її, коли знову зможете допомагати.
                 </div>
                 <a style="line-height:30px;" class="hoverdown fs12" id="cutlink" href="javascript:void(0)" onClick="showhide();"><?=t('Узнать больше')?></a>
             </div>
             <? } ?>
         </td>
         <td>
         </td>
     </tr>
     <?if(request::get_int('need',0)==0 || request::get_int('need')==1 ) {?>
                 <tr style="background: url('/static/images/common/desktop_line.png') repeat scroll 0% 0% transparent;">
                    <td class="bold cbrown" width="35%"><a href="/deskhelp/?need=0"><?=t('Предлагаю помощь')?></a></td>
                    <td class="aright"><? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=help"><?=t('Добавить')?> </a> <? } else{ ?><a class="cbrown" href="/deskhelp/?need=0"><?=t('Все предложения')?></a><?}?></td>
                </tr>
                <tr><td></td></tr>
                        <tr>
                            <td style="color: #000;">
                              <?php
                              //echo '!!!!!!!!!!!!!1';
                                //print_r($user_help_types);
                                foreach($user_help_types as $help_id=>$help_name) {
                                    $user_prov_ids  = user_desktop_help_peer::instance()->get_list(array('user_id'=>$user_desktop['user_id'], 'need'=>'0', 'type'=>$help_id));
                                    if(!empty($user_prov_ids)) {?>
                                         <table style=" margin-bottom: 5px;">
                                        <tr>
                                        <td width="30%"><?="<b>".$help_name."</b>";?></td>

                                        <?foreach($user_prov_ids as $id=>$help_id) {
                                            $data = user_desktop_help_peer::instance()->get_item($help_id);
                                                if($id==0) {
                                                    echo "<td style='color: #000;'>".$data['describe']."<br><div style='margin-top: 3px;'><i>Годин на тиждень:</i> ".$data['hours_per_week']."</div></td></tr>";
                                                    continue;
                                                }
                                            ?>
<!--                                            echo $data['describe'].'<hr style="color: #ccc; border: 0;">';-->
                                    <tr>
                                        <td></td>
                                        <td style='color: #000;'>
                                            <?=$data['describe']."<br><div style='margin-top: 3px;'><i>Годин на тиждень:</i> ".$data['hours_per_week']."</div>";?>
                                        </td>
                                    </tr>

                                  <?php  }?>
                                  </table>
                                   <? }
                                }


                              ?>
                            
                            </td>
                  
                        </tr>
                        <?php
                                  if(!empty($active_help)) {
                                    $act = user_desktop_active_help_peer::instance()->get_item($active_help[0]);
                                  if($act['active']==0) {                             ?>
                        <tr>
                            <td style="color: #000;">
                                  <?php
                                        echo t('Временно не предоставляю помощь');
                                    ?>
                            </td>
                        </tr>
                        <?php }
                      }?>
                        
                        
                        <? } ?>
<?if(request::get_int('need',0)==0 || request::get_int('need')==2 ) {?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" width="35%"><a href="/deskhelp/?need=1"><?=t('Нуждаюсь в помощи')?></a></td>
                                <td class="aright"><? if (session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id'];?>&tab=help"><?=t('Добавить')?></a> <? }else{ ?><a class="cbrown" href="/deskhelp/?need=1"><?=t('Все запросы')?></a><?}?></td>
                                
                        </tr>
                        <tr><td></td></tr>
                        <tr>
                            <td style="color: #000;">
                               <?php
                              //echo '!!!!!!!!!!!!!1';
                                //print_r($user_help_types);
                                foreach($user_help_types as $help_id=>$help_name) {
                                    $user_need_ids  = user_desktop_help_peer::instance()->get_list(array('user_id'=>$user_desktop['user_id'], 'need'=>'1', 'type'=>$help_id));
                                    if(!empty($user_need_ids)) {?>
                                <table style=" margin-bottom: 5px;">
                                    <tr>
                                        <td width="30%"><?="<b>".$help_name."</b>";?></td>
                                    
                                        <?foreach($user_need_ids as $id=>$help_id) {
                                            $data = user_desktop_help_peer::instance()->get_item($help_id);
                                                if($id==0) {
                                                    echo "<td style='color: #000;'>".$data['describe']."</td></tr>";
                                                    continue;
                                                }
                                            ?>
<!--                                            echo $data['describe'].'<hr style="color: #ccc; border: 0;">';-->
                                    <tr>
                                        <td></td>
                                        <td style='color: #000;'>
                                            <?=$data['describe'];?>
                                        </td>
                                    </tr>
                                
                                  <?php  }?>
                                  </table>
                                    <?}
                                }
                                ?>
                            </td>
                        </tr>


<? } ?>








                          <? if (!empty($send_help_list) && !request::get_int('need')) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" width="35%"><span name="ancor"><?=t('Предоставленная помощь')?></span></td>
                                <td></td>

                        </tr>
                        <tr><td></td></tr>
                        <tr>
                            <td>
                                  <?php
//                                  echo "<pre>";
//                                  print_r($rec_help_list);
                                 // exit;
                               if(!empty($send_help_list))
                                 foreach($send_help_list as $sid=>$sitem) {
                                    $sh_item = user_desktop_received_help_peer::instance()->get_item($sitem);
                                    $_type = user_desktop_help_types_peer::instance()->get_item($sh_item['type']);
                                    //print_r($sh_item);
//                                    print_r($send_help_list);
                                    if($sh_item['approved']==1) {
                                    ?>
                                <form>
                                    <table>
                                        <tr>
                                            <td class="fs12" colspan="2" style="color: #000;">
                                           
                                            <?  $tmp = user_data_peer::instance()->get_item($sh_item['userto']); ?>
                                                <?=$_type['name']?>,
                                                <?=$tmp['first_name'].' '.$tmp['last_name']?>,
<!--                                                <input type="text" disabled="disabled" value="<?=$tmp['first_name'].' '.$tmp['last_name']?>" />-->
                                                <?=$sh_item['receive_date'];?>
<!--                                                <input type="text"  disabled="disabled" value="<?=$sh_item['receive_date'];?>" />-->
                                            </td>
                                        </tr>
                                       
                                        <? if (!empty($sh_item['review'])) { ?>
                                        <tr class="">
                                            <td class="fs12" colspan="2" style="color: #000;">
                                                <?=t('Отзыв')?>:&nbsp;
                                            
                                               
                                                <b><?=$sh_item['review'];?></b>
<!--                                                <textarea  disabled="disabled" style="width:250px"><?=$sh_item['review'];?></textarea>-->
                                            </td>
                                        </tr>
                                        <?}?>

                                    </table>
                                </form>
                                   <?php
                                        }
                                      }
?>
                           </td>
                        </tr>
                        <? } ?>














                        <? if ((!empty($rec_help_list ) && session::get_user_id()==$user_desktop['user_id'] && !request::get_int('need')) || session::has_credential('admin')) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" width="35%"><?=t('Полученная помощь')?></td>
                                <td></td>

                        </tr>
                        <tr><td></td></tr>
                        <tr>
                            <td>
                                  <?php
//                                  echo "<pre>";
//                                  print_r($rec_help_list);
//                                  exit;
                                  if(!empty($rec_help_list))

                                 foreach($rec_help_list as $rid=>$ritem) {
                                    $rh_item = user_desktop_received_help_peer::instance()->get_item($ritem);
//                                    print_r($rh_item);
                                    if($rh_item['approved']==0) {
                                    ?>
                                <form>
                                    <table>
                                        <tr>
                                            <td class="fs12" style="color: #000;width: 150px; text-align: right;">
                                                <?=t('Кто предоставил')?>
                                            </td>
                                            <?  $tmp = user_data_peer::instance()->get_item($rh_item['userfrom']); ?>
                                            <td>
                                                <b><?=$tmp['first_name'].' '.$tmp['last_name']?>" </b>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td class="fs12" style="color: #000;width: 150px; text-align: right;">
                                                <?=t('Когда')?>
                                            </td>
                                            <td>
                                                <b><?=$rh_item['receive_date'];?>" </b>
                                            </td>
                                        </tr>
                                        <tr class="received_help">
                                            <td class="fs12" style="color: #000;width: 150px; text-align: right;">
                                                <?=t('Короткое описание предоставленной помощи')?>
                                            </td>
                                            <td>
                                                <b><?=$rh_item['describe'];?>"</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                               
                                            </td>
                                            <td>
                                            
                                                <input type="button" class="button" approve_id="<?=$rh_item['id']?>" value="<?=t('Подтвердить')?>">
                                                <input type="button" class="button_gray" refuse_id="<?=$rh_item['id']?>"  value="<?=t('Опровергнуть')?>">
                                            <div id="app_msg_<?=$rh_item['id']?>"></div>
                                            </td>
                                            
                                        </tr>
                                         <tr>
                                            <td class="fs12" style=" color: #000;width: 150px; text-align: right;">
                                                <?=t('Отзыв')?>
                                            </td>
                                            <td>
                                                <textarea  style="width: 250px;" id="help_review_<?=$rh_item['id']?>"></textarea>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                            </td>
                                            <td>
                                                <input type="button" class="button" review_add_id="<?=$rh_item['id']?>" id="add_review" value="<?=t('Добавить')?>">
                                                <input type="button"  cancel_id="<?=$rh_item['id']?>" onClick="" class="button_gray" value="<?=t('Отменить')?>">
                                                <div id="review_success_<?=$rh_item['id']?>"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                                   <?php
                                        }
                                      }
?>
                           </td>
                        </tr>
                        <? } ?>
                        <? if (session::get_user_id()!=$user_desktop['user_id']) { ?>
                        <tr>
                            <td>
                                <p style="color: #000; font-weight: bold;">Для того, щоб отримати або запропонувати цій людині свою допомогу, напишіть їй внутрішнє <a href='https://meritokrat.org/messages/compose?user_id=<?php echo $user_desktop['user_id'];?>' style="color: black;">повідомлення</a> у Мерітократ.org.
                            </td>
                        </tr>
                        <? } ?>
    <?php //echo "<pre>";
       // print_r($help_data); ?>
    </table>
<script>
function showhide() {
        if ($("table").find(".cut").css("display")=="none") {
                $("table").find(".cut").show();
                $('#cutlink').css('background','none');
                $('#cutlink').html('Згорнути');
                }
        else {
             $("table").find(".cut").hide();
             $('#cutlink').css('background','url("/static/images/icons/down_icon_brown.png") no-repeat scroll right center transparent');
             $('#cutlink').html('Дізнатися більше');
           }
}
</script>
<script>
    need = '<?=request::get_int('need');?>'
    if(need!='0') {
        $('#desktop_info_text').hide();
        $('#desktop_panels_box').hide();
    }
</script>