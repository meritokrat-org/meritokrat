<? $topic_message = groups_positions_messages_peer::instance()->get_item($id) ?>
<div class="mb10 comment_bg mr10" id="talk_message<?=$id?>">
	<div class="left"><?=user_helper::photo($topic_message['user_id'], 's', array('class' => 'border1'))?>
        <? if (!$flag) { ?>
            <br/>
		<div class="fs11 mb5 quiet"><?=t('Просмотров')?></div>
		<div class="acenter bold"><?=(int)$topic['views']?></div>
       <? } $flag=1; ?>
        </div>
	<div class="left ml10" style="width: 680px;">
		<div class="fs11 pb5">
			<?=user_helper::full_name($topic_message['user_id'])?>
			<span class="quiet ml10"><?=date_helper::human($topic_message['created_ts'], ', ')?></span>

		<div class="right">
			<?  if ( session::is_authenticated() && !groups_positions_messages_peer::instance()->has_rated($topic_message['id'], session::get_user_id()) ) { ?>
				<span>
					<a href="javascript:;" onclick="groupsController.ratePositionMessage(this, <?=$topic_message['id']?>, true);"><?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
					<a href="javascript:;" onclick="groupsController.ratePositionMessage(this, <?=$topic_message['id']?>, false);"><?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
				</span>
			<? }  ?>
			<span class="bold mr10" id="message_rate" style="color:<?=$topic_message['rate'] >= 0 ? $topic_message['rate'] > 0 ? 'green' : '#999' : 'red' ?>"><?=$topic_message['rate'] > 0 ? '+' : ''?><?=$topic_message['rate']?></span>
		</div>
                </div>

		<div class="fs12 mt5"><?=$counter==1 ? stripslashes($topic_message['text']) : stripslashes(nl2br($topic_message['text']))?></div>

		<? if ( ( $topic_message['user_id'] == session::get_user_id() ) || groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
			<div class="fs10 mt10">
				<a href="javascript:;" onclick="groupsController.deletePositionMessage(<?=$id?>);" class="maroon dotted"><?=t('Удалить')?></a>
                                <a href="/groups/position_message_edit?id=<?=$id?><?=$counter==1 ? '&tinymce=1' : ''?>" class="maroon dotted"><?=t('Редактировать')?></a>
			</div>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>