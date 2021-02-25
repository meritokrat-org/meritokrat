<div class="mr10 mb10 p10 fs11 <?=!$message['is_read'] ? 'highlight' : ( $message['sender_id'] === session::get_user_id() ? 'box_content' : 'box_deep' ) ?>">
	<? if ( $message['sender_id'] != session::get_user_id() ) { ?>
	<?=user_helper::full_name(
		$message['sender_id'],
		true,
		array('class' => 'bold green fs11'))
	?>
	<? } else { ?>
		<span class="quiet bold">Я</span>
	<? } ?>
	<span class="quiet fs11 ml10"><?=date('H:i d/m/y', $message['created_ts'])?></span>
        <a class="quiet fs11 ml10" href="/messages/compose?resend=<?=$message['id']?>"><?=t('Переслать')?></a>
        <div class="mt5 fs12">
            <?//html_entity_decode(stripslashes(nl2br(strtr($message['body'],array('<p>'=>'','</p>'=>''))))) ?>
            <?=user_helper::get_links(html_entity_decode(stripslashes(nl2br($message['body']))),false)?>
        </div>
	<? if ( $message['attached'] ) { ?>
		<div class="top_line_2 mt10 mb10 p10"><?=$message['attached']?></div>
	<? } ?>
</div>