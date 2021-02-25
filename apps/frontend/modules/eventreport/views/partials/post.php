<style type="text/css">
    .box_content_dark {
        background-color: #e6e6e6;
    }
</style>
<?if($post_data) {?>
<div class="box_content mb10">
    <div class="left ml5" style="width: 60px;">
        <?=user_helper::photo($post_data['user_id'], 's', array('class' => 'mt5 border1'))?>
        <div class="quiet fs11 mb10 acenter" style="line-height: 1.2;"></div>
    </div>
<div class="mt5" style="margin-left: 70px;">

	<h3 class="mb5">
            <a href="/eventreport/view?id=<?=$post_data['id']?>" style="font-weight:normal;" class="fs18"><?=stripslashes(htmlspecialchars($post_data['name']))?></a>
                
        </h3>
	<div class="fs12">
            <div class="box_content">
                <? $post_ppo = ppo_peer::instance()->get_item($post_data['po_id'])?>
                <a class="fs11" href="/ppo<?=$post_data['po_id']?>/"><?=stripslashes(htmlspecialchars($post_ppo['title']))?></a><br/>
		<? $post_user = user_data_peer::instance()->get_item($post_data['user_id'])?>
                <a class="fs11" href="/profile-<?=$post_data['user_id']?>"><?=stripslashes(htmlspecialchars($post_user['first_name'] . ' ' . $post_user['last_name']))?></a>
                <span class="fs11 cgray mr5">&nbsp;<?=date("d/m H:i",$post_data['start'])?></span>
            </div>
	</div>
</div>
<div class="clear"></div>
</div>
<? } ?>