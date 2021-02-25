
<style>
.fl {
    float: left;
    clear: none;
}
.cb {
    float: none;
    clear: both;
}
#user_list2 {
    position: absolute;
    width: 180px;
}
.one_user_box {
    z-index: 100;
    background: #fff;
    position: relative;
    padding: 1px 10px;
    border: 1px solid #ccc;
    width: 180px;
}
.one_user_ava_box {}
.one_user_ava_box img {
    width: 32px;
    height: 40px;
}
.one_user_info_block {
    margin-left: 10px;
     width: 135px;
}
.one_user_name {
    line-height: 20px;

}
.one_user_city {
    line-height: 20px;
}
.user_selected {
    background-color: #dddddd;
}

.separator {
    border-bottom: 1px dashed #777; 
    padding-bottom: 4px !important; 
/*    margin-bottom: 2px;*/
}
#prl {
    list-style-position: inside;
    margin: 0;
    padding: 10px 0;
}
#prl li{
    padding: 0 20px;
}
</style>

<h1 class="column_head"><?=t('Темы')?></h1>
<div class="box_content" style="padding-bottom:10px">
	<ul class="mb5" id="prl">
        <? if(session::has_credential('admin') || $positiontotal>0){ ?>
            <li  <?=(request::get('theme')=='position') ? 'style="background-color:#e7e7e7"':''?> class="separator">
                <a href="/blogs/programs?theme=position" <?=(request::get('theme')=='position')?'class="bold fs12"':'class="fs12"'?> style="margin: 1px;"><?=t('Позиция')?></a>
                <div style="color:#660000" class="right fs12 bold"><?=$positiontotal?></div>
            </li>
        <? } ?>
        <? if(session::has_credential('admin') || $mputotal>0){ ?>
            <li <?=(request::get('theme')=='mpu')?'style="background-color:#e7e7e7"':''?>  class="separator">
                <a href="/blogs/programs?theme=mpu" <?=(request::get('theme')=='mpu')?'class="bold fs12"':'class="fs12"'?> style="margin: 1px;"><?=t('Идеи для Идеальной Страны')?></a>
                <div style="color:#660000" class="right fs12 bold"><?=$mputotal?></div>
            </li>
        <? } ?>
        <? foreach($themes as $k=>$v){ ?>
            <li <?=(request::get('theme')==$k)?'style="background-color:#e7e7e7"':''?> class="<?=(in_array($k,array(38, 40, 'position', 'mpu')) ? 'separator' : '')?>">
                <a href="/blogs/programs?theme=<?=$k?>" <?=(request::get_int('theme')==$k)?'class="bold fs12"':'class="fs12"'?> style="margin: 1px;"><?=$v['title']?></a>
                <div style="color:#660000" class="right fs12 bold"><?=$v['summ']?></div>
            </li>
	<? } ?>
	</ul>
    <? if(session::has_credential('admin')){ ?>
            <a href="/groups/index?category=2" class="fs12 quiet" style="margin-left:20px"><?=t('К списку рабочих групп')?> &rArr;</a>
        <? } ?>
</div>

<h1 class="column_head mt10"><?=t('Целевые группы')?></h1>
<div class="p10 box_content">
	<ul class="mb5">
        <? foreach($targets as $k=>$v){ ?>
            <li>
                <a href="/blogs/programs?target=<?=$k?>" <?=(request::get_int('target')==$k)?'class="bold fs12"':'class="fs12"'?> style="margin: 1px;"><?=$v['title']?></a>
                <div style="color:#660000" class="right fs12 bold"><?=$v['summ']?></div>
            </li>
	<? } ?>
	</ul>
</div>

<h1 class="column_head mt10"><?=t('Сейчас обсуждаются')?></h1>
<div>
    <? foreach ($talk as $id) { ?>
        <? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
        <?include 'small_post.php'; ?>
    <? } ?>
</div>