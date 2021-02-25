<?php
    $htlist = user_desktop_help_peer::getHelpTypes();
?>
<?if(session::has_credential('admin')) {
    $_types = user_desktop_help_types_peer::instance()->get_list();
    ?>

<form method="post" action="/deskhelp/index" class="hidden" id="changeType">
    <select name="oldtype">
        <?if(!empty($_types))
            foreach ($_types as $key => $value) { 
                $item = user_desktop_help_types_peer::instance()->get_item($value);
        ?>
        <option value="<?=$item['id']?>"><?=$item['name']?></option>
        <?}?>
    </select>
    <input type="text" name="newtype">
    <input type="submit" name="submit" value="<?=t("Сохранить")?>">
</form>
<? } ?>
<div id="need_select" style="float: left; clear: none; width: 260px;">
    <div class="clear"></div>
    <?if(session::has_credential('admin')) {?>
    <a href="javascript: ; " onClick="$('#changeType').slideToggle(300)">*Change</a>
    <? } ?>
    <h1 style="cursor: pointer;" class="column_head mt10"><a  href="/deskhelp/?need=1"><?=t('Запросы о помощи')?></a></h1>
    <div id="statuces" class="p10 box_content hide" style="display: block;">
	<ul class="mb5">
            <?php foreach($htlist as $help_type_id=>$help_type_name) {
                $data = user_desktop_help_peer::instance()->get_list(array('type'=>$help_type_id,'need'=>'1'));
                 if(!empty($data))
                echo '<li><a style="margin: 1px;" href="/deskhelp/?need=1&type='.$help_type_id.'">'.$help_type_name.'</a><div class="cbrown right fs11 bold">'.count($data).'</div></li>';
            }
            ?>
        </ul>
    </div>
    <h1 style="cursor: pointer;" class="column_head mt10"><a href="/deskhelp/?need=0"><?=t('Предлогают помощь')?></a></h1>
    <div id="statuces" class="p10 box_content hide" style="display: block;">
	<ul class="mb5">
            <?php foreach($htlist as $help_type_id=>$help_type_name) {
                 $data = user_desktop_help_peer::instance()->get_list(array('type'=>$help_type_id,'need'=>'0'));
                 if(!empty($data))
                echo '<li><a style="margin: 1px;" href="/deskhelp/?need=0&type='.$help_type_id.'">'.$help_type_name.'</a><div class="cbrown right fs11 bold">'.count($data).'</div></li>';
            }
            ?>
        </ul>
    </div>
    
</div>

<div style="width: 480px; float: right; clear: none;">

    <h1 class="column_head mt10"> <a href="/deskhelp?need=<? echo $need?>"><?=t($help_list_title)?></a><a class="right" href="/deskhelp?need=<? echo $need?>"><?=t('Все')?>:&nbsp;<?=($type_cnt) ? $type_cnt : (($need) ? $need_help_count  : $prov_help_count)?></a>
                                              <!--div style="display:inline; margin-left:140px"><a style="text-transform: none;" href="/people?online=1">Посл посещение</a></div-->

</h1>
<?php
$user_data = user_data_peer::instance()->get_item($id) ?>
<?
foreach($ids as $uid=>$id)
{
            $user_need_help_data_list = user_desktop_help_peer::instance()->get_list(array('user_id'=> $id,'need'=>'1'));
            $user_prov_help_data_list = user_desktop_help_peer::instance()->get_list(array('user_id'=> $id,'need'=>'0'));


             $help_types_list = user_desktop_help_peer::getHelpTypes();


            $user_need_list = array();
            $user_prov_list = array();

            if(!empty($user_need_help_data_list)) {
                foreach ($user_need_help_data_list as $n_id=>$need_help_id) {
                    $help_data = user_desktop_help_peer::instance()->get_item($need_help_id);
                    $user_need_list[$help_data['type']] = $help_types_list[$help_data['type']];
                }
               // print_r($user_need_list);
            }
            if(!empty($user_prov_help_data_list)) {
                foreach ($user_prov_help_data_list as $p_id=>$prov_help_id) {
                    $help_data = user_desktop_help_peer::instance()->get_item($prov_help_id);
                    $user_prov_list[$help_data['type']] = $help_types_list[$help_data['type']];
                }
               // print_r($user_prov_list);
            }


           


            
            


            
if ( $party_id = parties_members_peer::instance()->get($id) ) {
	$party = parties_peer::instance()->get_item($party_id);
} else { $party = null; } ?>
<div class="box_content p10 mb10">
	<?=user_helper::photo($id, 't', array('class' => 'border1 left'))?>
    <? if(request::get('bookmark')){ ?>
        <? if(request::get('bookmark')==6){ ?>
            <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),6,$user_data['user_id']); ?>
            <a class="right fs10" style="<?=($bkm)?'display:none;':'display:block;'?>width:70px;text-align:center;margin-right:4px;" href="#add_bookmark" onclick="Application.bookmarkThisItem(this,'6','<?=$user_data['user_id']?>');return false;"><b></b><span><?=t('Добавить в любимые авторы')?></span></a>
            <a class="right fs10" style="<?=($bkm)?'display:block;':'display:none;'?>width:70px;text-align:center;margin-right:4px;" href="#del_bookmark" onclick="Application.unbookmarkThisItem(this,'6','<?=$user_data['user_id']?>');return false;"><b></b><span><?=t('Удалить из любимых авторов')?></span></a>
        <? }else{ ?>
            <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),2,$user_data['user_id']); ?>
            <a class="bookmark mb10 ml5 right" style="<?=($bkm)?'display:none;':'display:block;'?>" href="#add_bookmark" onclick="Application.bookmarkThisItem(this,'2','<?=$user_data['user_id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
            <a class="unbkmrk mb10 ml5 right" style="<?=($bkm)?'display:block;':'display:none;'?>" href="#del_bookmark" onclick="Application.unbookmarkThisItem(this,'2','<?=$user_data['user_id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>
        <? } ?>
<? } ?>
	<div style="margin-left: 85px;">
            <div class="right fs10 mr10" style="text-align: right;width:110px">
        <?//=str_replace(array(t("Последнее посещение").": ",'<img style="margin-top: 2px;" class="left" alt="online" src="/static/images/common/user_online.png">'), array("",""),user_sessions_peer::instance()->last_visit($id))?>

        <? if ( 1 ) { ?>
                <div class="right fs11" style="text-align: right;<?=(request::get('need') ? 'margin-left: -15px;' : 'margin-left: -50px;')?>margin-top: 30px; position:absolute;">
            <input type="button" class="button" style="background: #660000; border-top: 1px solid #B83638; border-left: 1px solid #B83638; font-weight: bold;" value="<?=t($help_button_title)?>" onClick="javascript: document.location = '<?=conf::get('host');?>/profile/desktop?id=<?php echo $id;?>&tab=help&need=<?=($need+1)?>'">
            <? /*$last_visit=user_sessions_peer::instance()->get_item($id); ?>
            останнє: <?=intval(($last_visit['visit_ts']-$last_visit['start'])/60) . " хв."?><br/>
            усього: <?=intval(db_key::i()->get('all_time_'.$id)/3600)." год., ".intval((db_key::i()->get('all_time_'.$id)%3600)/60)."хв."*/?>
            </div>
        <? } ?>

            </div>
		<?=user_helper::full_name($id,true,array('class'=>'bold'))?>

		<div class="fs11">
			<div class="left" style="width:205px;">

                           <?php
                           if($need==1)
                               if(isset($user_need_list))
                                   foreach($user_need_list as $need_type_id=>$user_need_help_name)
                                        {
                                           echo "<a href='/deskhelp/?need=1&type=".$need_type_id."'>".$user_need_help_name."</a>&nbsp;";
                                        }
                                   // echo ' / ';
                           if($need==0)
                               if(isset($user_prov_list))
                                   foreach($user_prov_list as $prov_type_id=>$user_prov_help_name)
                                        {
                                            echo "<a href='/deskhelp/?need=0&type=".$prov_type_id."'>".$user_prov_help_name."</a>&nbsp;";
                                        }

                           ?>
                            <br>
        <? load::model('geo') ?>
	<? if ( $user_data['country_id'] ) { ?>
		<? $country = geo_peer::instance()->get_country($user_data['country_id']) ?>
    <a href="/search?submit=1&country=<?=$user_data['country_id']?>"><?=$country['name_' . translate::get_lang()]?></a>
	<? if ( $user_data['region_id'] ) { ?>
		<? $region = geo_peer::instance()->get_region($user_data['region_id']) ?> / <a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>"><?=$region['name_' . translate::get_lang()]?></a>
	<? } ?>
	<? if ( $user_data['city_id'] ) { ?>
		<? $city = geo_peer::instance()->get_city($user_data['city_id']) ?> / <a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>&city=<?=$user_data['city_id']?>"><?=$city['name_' . translate::get_lang()]?></a>
	<? } ?>

<br/>
	<? } ?>
        <? if (request::get_int('function')>0) { ?>
            <? load::model('user/user_desktop'); ?>
            <? $user_desktop=user_desktop_peer::instance()->get_item($id); ?>
            <? if ( $user_desktop['user_id']==5) { $echo_functions[$id][]=t('Глава Оргкомитета'); } ?>
            <? foreach (user_auth_peer::get_functions() as $function_id=>$function_title) { ?>
            <? if (in_array($function_id, explode(',',str_replace(array('{','}'),array('',''),$user_desktop['functions'])))) { $echo_functions[$id][]=$function_title; } ?>
            <? } ?>
            <?=implode(', ',$echo_functions[$id])?>
        <? } else { ?>

        <? load::model('user/user_work') ?>
        <? $user_work = user_work_peer::instance()->get_item($id) ?>
        <? if ( $user_work['work_name']) { ?> <?=stripslashes(htmlspecialchars($user_work['work_name']))?>, <? } if ( $user_work['position']) { ?> <?=stripslashes(htmlspecialchars($user_work['position']))?>   <? } } ?>
			</div>

			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
    <?php }?>

		<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>
