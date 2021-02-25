<script src="/static/javascript/jquery/jquery.prettyPhoto.js" type="text/javascript"></script>
<link type="text/css" href="/static/javascript/jquery/css/prettyPhoto.css" rel="stylesheet" />
<style>
    .paimg img{border: 1px solid #CDC9C9;}
    .paimg a.acenter{width:160px; height: 160px; vertical-align: middle; padding:9px; background-color:#F7F7F7; margin:0 5px 5px 0;line-height:0.9}
    .paimg a.acenter span{line-height:1;}
</style>
<? include 'partials/head.php' ?>
<div class="ml15 paimg">
<?
foreach($list as $photos){
foreach (unserialize($photos['information_avtonumbers_photos']) as $avtonumber) { ?>
<div id="photo_<?=$avtocount?>" class="img ml5 mr5 left thread box_empty p10 mb10 mr10 box_content">
    <a href="<?=context::get('image_server').'o/avtonumber/'.$photos['user_id'].$avtonumber['salt'].'.jpg'?>" rel="prettyPhoto[gallery]"
       title="<?=user_helper::full_name($photos['user_id'], false)?> - <?=htmlspecialchars(stripslashes($avtonumber['description']))?>">
    <?=tag_helper::image('p/avtonumber/'.$photos['user_id'].$avtonumber['salt'].'.jpg', array('alt'=>$avtonumber['description'],'style'=>'cursor: pointer;'), context::get('image_server')); ?>
</a>
</div>
                            <? $avtocount++; } } ?>
    </div>
<script type="text/javascript">
        $(document).ready(function($){
            $(".paimg a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
        });
</script>