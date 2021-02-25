<? $user_data = user_data_peer::instance()->get_item($id);
 ?>
<div id="ph<?=$id?>" class="box_content border1 mr10 p10 mb10">
    <?=tag_helper::image(user_helper::photo_path($id, 't'),array('class' => 'border1 left'),context::get('image_server'))?><div class="left acenter m10">
         <?=user_helper::full_name($id, true, array(), false)?><br/><span class="fs16">→</span></div>
    <?=tag_helper::image(user_helper::photo_path($id, 't','user','new_'),array('class' => 'border1 left'),context::get('image_server'))?>
	<div style="margin-left: 85px;">
        <div class="right fs10" style="text-align: right;width:110px">
            <a rel="<?=$id?>" class="phtapr" href="javascript:;"><?=t('Утвердить')?></a><br/><br/>
            <a rel="<?=$id?>" class="phtapri" href="javascript:;"><?=t('Утвердить с условием')?></a><br/><br/>
            <a rel="<?=$id?>" class="phdel" href="javascript:;"><?=t('Удалить')?></a>
        </div>
	</div>
	<div class="clear"></div>
</div>