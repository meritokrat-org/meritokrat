<?if(session::has_credential('admin')) {?>

<style>
    .del_btn {
    }
    .upd_btn {
    }
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

<form id="help_form" class="form hidden">
    <table>
       <tr>
         <td style=" width: 100%;">
             <div class="fs11 quiet" style="text-align: justify;color: #333333; width: 100%;">
                 <div class="show mt10">Якщо Ви готові приділяти частину Вашого часу наданню безкоштовної допомоги членам мерітократичної команди, або Вам потрібна допомога, будь ласка, заповніть цей розділ. Вкажіть, яку саме допомогу Ви готові надавати і скільки годин на тиждень Ви готові цьому присвячувати.</div>

                 <div class="cut mt10" style="display:none;">
                     <p>Під допомогою тут ми розуміємо як послуги (наприклад, консультування з юридичних питань, з питань розвитку бізнесу і т.ін.), так і роботи (наприклад, для системних адміністраторів та програмістів - налаштування домашньої комп’ютерної техніки, для дизайнерів – розробка  макетів візитівок і т.п.).</p>
                     <p>Також у цьому розділі  Ви зможете публікувати міні-звіти про вже надану Вам допомогу, а члени мерітократичної команди, яким Ви допомагали – відгуки про Вашу роботу.</p>
                     Якщо в певний момент у Вас тимчасово не буде можливості надавати допомогу (наприклад, Ви їдете у відпустку або у цьому місяці вже вичерпався час, відведений Вами на надання безкоштовної допомоги) – скористайтесь галочкою «Тимчасово не надаю допомогу» і не забудьте зняти її, коли знову зможете допомагати.
                 </div>
                 <a style="line-height:30px;" class="hoverdown fs12" id="cutlink" href="javascript:void(0)" onClick="showhide();"><?=t('Узнать больше')?></a>
             </div>
         </td>
     </tr>
    </table>
<!--////////////////////////////////////////EDIT BLOCK////////////////////////////////////////-->  
<?php 
    $user_provide = user_desktop_help_peer::instance()->get_list(array('user_id'=>$user_desktop['user_id'],'need'=>'0'));
   ?>
     <table >
            <tr>
                        <td width="30%" class="aright"><span style="text-decoration: none;" class="ml10 fs18"><?=t('Предлагаю помощь')?></span></td>
                        <td class="acenter"></td>
            </tr>
     </table>
     <?php  if(!empty($user_provide)) { ?>
<?php    foreach($user_provide as $nid=>$ph_id) {
            $current_user = user_desktop_help_peer::instance()->get_item($ph_id);
    
?>
<table id="table_<?php echo $current_user['id'];?>">
        <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                    <?=t('Вид помощи')?>
            </td>
            <td >
                <select style="width:395px" name="change_help_type_<?php echo $current_user['id'];?>" disabled>
                   <?php foreach($help_types as $id=>$help_type) {?>
                    <option value="<?php echo $id;?>" <?php if($id==$current_user['type']) echo ' selected'?>><?php echo $help_type;?></option>
                   <?php }?>
               </select>
            </td>
            
        </tr>
         <tr >
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Детализация')?>:
            </td>
            <td>
                <textarea type="text" disabled name="change_help_describe_<?php echo $current_user['id'];?>"><?php echo $current_user['describe'];?></textarea>
            </td>
        </tr>
        <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Количество часов в неделю')?>:
            </td>
            <td>
                <input type="text" disabled name="change_hours_per_week_<?php echo $current_user['id'];?>" style="width: 50px;" value="<?php echo $current_user['hours_per_week'];?>">
            </td>
        </tr>
        <tr class="mt15">
            <td></td>
             <td>
                 <input type="button" style="display:none;" need="0" class="button change_help_btn" rec_id="<?php echo $current_user['id'];?>" value=" <?=t('Подтвердить')?> ">
                 <?=tag_helper::wait_panel('help_wait') ?>
                 <div class="help_success_<?php echo $current_user['id'];?> hidden mr10 mt10"></div>
             </td>
         </tr>
         <tr>
             <td></td>
             <td>
                <input type="button" class="button upd_btn" value="<?=t('Редактировать')?>" update_id="<?php echo $current_user['id'];?>">
                <input type="button" class="button del_btn" value="<?=t('Удалить')?>" delete_id="<?php echo $current_user['id'];?>">

             </td>
         </tr>
    </table>
<? } ?>
<?}?>   
    <input type="hidden" name="type" value="help">
    <table  id="prov_help_add"  <?if(!empty($user_provide)) echo 'style="display: none;"' ?>>
        <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Вид помощи')?>

            </td>
            <td>
                <select style="width:395px" name="help_type" sel_need="0">
                    <?php foreach($help_types as $id=>$help_type) {?>
                        <option value="<?php echo $id;?>"><?php echo $help_type;?></option>
                    <?php }?>
                        <option value="-1">Інше</option>
                </select>
            </td>
            <td>
                <input name="other_help" style="display:none;" inp_need="0">
            </td>
        </tr>
         <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Детализация')?>:
            </td>
            <td>
                <textarea  name="help_describe"></textarea>
            </td>
        </tr>
         <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Количество часов в неделю')?>:
            </td>
            <td>
                <input type="text" name="hours_per_week" style="width: 50px;">
            </td>
        </tr>
         <tr class="mt15">
            <td></td>
             <td>
                 <input type="button" name="add_help_btn" class="button" need="0" value=" <?=t('Сохранить')?> ">
                 <input onclick="clearForm('received_help_form')" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                 <?=tag_helper::wait_panel('educations_wait') ?>
                 <div class="help_success hidden mr10 mt10"></div>
             </td>
         </tr>
         <tr class="provide_help">
            <td>

            </td>
<!--            <td>
                <input type="button" name="active_btn"   class="button"
                <?php
//
//                    if(!empty($active_help)){
//                        $act = user_desktop_active_help_peer::instance()->get_item($active_help[0]);
//                        if($act['active'])
//                                echo "value='".t('Временно не предоставляю помощь')."' active='0'";
//                            else
//                                echo "value='".t('Снова предоставляю помощь')."' active='1'";
//                    }
//                    else
//                        echo "value='".t('Временно не предоставляю помощь')."' active='0'";
                ?>/>
            </td>-->
        </tr>
         </table>
    <table>
        <tr>
            <td style="width: 35%;"></td>
            <td>
              <input type="button" class="button" value="<?=t('Добавить')?>" onClick="show('#prov_help_add')">
            </td>
        </tr>
    </table>
<?php
    $user_need = user_desktop_help_peer::instance()->get_list(array('user_id'=>$user_desktop['user_id'],'need'=>'1'));
    ?>
    
    <table id="table_<?php echo $current_user['id'];?>">
        <tr>
                        <td width="30%" class="aright"><span style="text-decoration: none;" class="ml10 fs18"><?=t('Нуждаюсь в помощи')?></span></td>
                        <td class="acenter"></td>
        </tr>
    </table>
   <?php if(!empty($user_need)) { ?>
<?php   foreach($user_need as $pid=>$nh_id) {
            $current_user = user_desktop_help_peer::instance()->get_item($nh_id);

?>
<table id="table_<?php echo $current_user['id'];?>">
        <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                    <?=t('Вид помощи')?>
            </td>
            <td >
                <select style="width:395px" name="change_help_type_<?php echo $current_user['id'];?>" disabled>
                   <?php foreach($help_types as $id=>$help_type) {?>
                    <option value="<?php echo $id;?>" <?php if($id==$current_user['type']) echo ' selected'?>><?php echo $help_type;?></option>
                   <?php }?>
               </select>
            </td>
        </tr>
         <tr >
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Детализация')?>:
            </td>
            <td>
                <textarea disabled name="change_help_review_<?php echo $current_user['id'];?>"><?php echo $current_user['describe'];?></textarea>
            </td>
        </tr>
        <tr class="mt15">
            <td></td>
             <td>
                 <input type="button" style="display:none;" need="1" class="button change_help_btn" rec_id="<?php echo $current_user['id'];?>" value=" <?=t('Подтвердить')?> ">
                 <?=tag_helper::wait_panel('help_wait') ?>
                 <div class="help_success_<?php echo $current_user['id'];?> hidden mr10 mt10"></div>
             </td>
         </tr>
         <tr>
             <td></td>
             <td>
                 <input type="button" class="button upd_btn" value="<?=t('Редактировать')?>" update_id="<?php echo $current_user['id'];?>">
                 <input type="button" class="button del_btn" value="<?=t('Удалить')?>" delete_id="<?php echo $current_user['id'];?>">
             </td>
         </tr>
    </table>
<? } ?>
    <?}?>

    
    <input type="hidden" name="type" value="help">
    <table id="need_help_add" <?if(!empty($user_need)) echo 'style="display: none;"' ?>>
        <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Вид помощи')?>

            </td>
            <td>
                <select style="width:395px" name="help_type" sel_need="1">
                    <?php foreach($help_types as $id=>$help_type) {?>
                        <option value="<?php echo $id;?>"><?php echo $help_type;?></option>
                    <?php }?>
                        <option value="-1">Інше</option>
                </select>
            </td>
            <td>
                <input name="other_help" style="display:none;" inp_need="1">
            </td>
        </tr>
        <tr >
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Короткое описание проблеми')?>:
            </td>
            <td>
                <textarea type="text" name="help_review"></textarea>
            </td>
        </tr>
         
         <tr class="mt15">
            <td></td>
             <td>
                 <input type="button" name="add_help_btn1" class="button" need="1" value=" <?=t('Сохранить')?> ">
                 <input onclick="clearForm('received_help_form')" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                 <?=tag_helper::wait_panel('educations_wait') ?>
                 <div class="help_success hidden mr10 mt10"></div>
             </td>
         </tr>
         <tr class="provide_help">
            <td>

            </td>
<!--            <td>
                <input type="button" name="active_btn"   class="button"
                <?php
//
//                    if(!empty($active_help)){
//                        $act = user_desktop_active_help_peer::instance()->get_item($active_help[0]);
//                        if($act['active'])
//                                echo "value='".t('Временно не предоставляю помощь')."' active='0'";
//                            else
//                                echo "value='".t('Снова предоставляю помощь')."' active='1'";
//                    }
//                    else
//                        echo "value='".t('Временно не предоставляю помощь')."' active='0'";
                ?>/>
            </td>-->
        </tr>
         
    </table>
        <table>
        <tr>
            <td style="width: 35%;"></td>
            <td>
              <input type="button" class="button" value="<?=t('Добавить')?>" onClick="show('#need_help_add')">
            </td>
        </tr>
    </table>
        <table>
        <tr>
                        <td width="35%" class="aright">
                            <span class="ml10 fs16" style="text-decoration: none;">
                                <a style="padding-right: 15px; font-weight: bold; background: url(/static/images/icons/down_icon_brown.png) no-repeat scroll right 5px transparent;" href="javascript: void(0);" id="already_receive"><?=t('Уже предоставили помощь?');echo '?';?></a>
                            </span>
                        </td>
                        <td class="acenter"></td>
        </tr>
    </table>
</form>

<!--////////////////////////////////POPUP BLOCK///////////////////////////////////////-->
<form id="received_help_form" class="form" style="display:none;">
    <? if ( session::has_credential('admin') ) { ?>
<!--                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">-->
        <? } ?>
    <input type="hidden" name="type" value="receive">
    <table>
        <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Кому')?>
            </td>
            <td>
                <table style="margin: 0px;">
                    <tr>
                        <td style="padding: 0px;">
                            <input type="text" id="search_users" style="float: left; clear: none" name="user_to">
                            <div style="line-height: 20px; float: left; clear: none; font-size: 12px; font-style: italic; margin-left: 10px;"><?=t('Начните вводить имя или фамилию человека')?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            <div id="user_list2" class="cb" style="display: none;"></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
         <tr>
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Вид помощи')?>

            </td>
            <td>
                <select style="width:395px" name="received_help_type">
                    <?php foreach($help_types as $id=>$help_type) {?>
                    <option value="<?php echo $id;?>"><?php echo $help_type;?></option>
                    <?php }?>
                </select>

            </td>
        </tr>
        <tr class="">
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('З')?>
            </td>
            <td>
                <select name="mounth_from">
                    <?php
                        foreach(user_desktop_received_help_peer::getMounth() as $num=>$name)
                            echo '<option value="'.$num.'">'.$name.'</option>';
                    ?>
                </select>
                <select name="year_from">
                    <?php
                        foreach(user_desktop_received_help_peer::getYears() as $num)
                            echo '<option value="'.$num.'">'.$num.'</option>';
                        ?>
                </select>
            </td>
        </tr>
        <tr class="">
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('По')?>
            </td>
            <td>
                <select name="mounth_to">
                    <?php
                        foreach(user_desktop_received_help_peer::getMounth() as $num=>$name)
                            echo '<option value="'.$num.'">'.$name.'</option>';
                    ?>
                </select >
                <select name="year_to">
                    <?php
                        foreach(user_desktop_received_help_peer::getYears() as $num)
                            echo '<option value="'.$num.'">'.$num.'</option>';
                        ?>
                </select>
            </td>
        </tr>
        <tr class="received_help">
            <td class="fs12" style="width: 35%; text-align: right;">
                <?=t('Короткое описание предоставленной помощи')?>
            </td>
            <td>
                <textarea type="text" name="describe"></textarea>
            </td>
        </tr>   
         <tr class="mt15">
            <td></td>
             <td>
                 
                 <input type="button" id="received_help_btn" class="button" style="background: #660000; border-top: 1px solid #B83638; border-left: 1px solid #B83638; " value=" <?=t('Сохранить')?> ">
                 <input onclick="clearForm('received_help_form')" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                 <?=tag_helper::wait_panel('educations_wait') ?>
                 <div class="receive_success hidden mr10 mt10"><?=t('Указанному Вами пользователю отослан запрос о подтверждении предоставленной помощи')?></div>
             </td>
         </tr>
    </table>
</form>
<!--<div id="search_box">
  <input type="text" id="search_users">
  <div style="border: 1px solid #CCCCCC;   overflow: hidden; display: none;   width: 200px;" id="user_list_div">
      <select id="users_list" multiple="multiple" style="width: 220px;padding: 0px; border: 0px;">
      </select>
  </div>
 </div>-->

<script type="text/javascript">
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
function show($id) {
    if($($id).css('display')=='none') {
        $($id).show();
        $(this).css('background','none');
    }
    else {
        $($id).hide();
        $(this).css('background','url("/static/images/icons/down_icon_brown.png") no-repeat scroll right center transparent');
    }
}
</script>

<? } ?>