<? $topic = ppo_topics_peer::instance()->get_item($id) ?>
<div class="mb5 box_content p5 mr10" id="comment<?=$id?>">
	<div class="mb5 bold">
		<a href="/ppo/talk_topic?id=<?=$id?>"><?=stripslashes(htmlspecialchars($topic['topic']))?></a>
	</div>
	<div class="left"><?=user_helper::photo($topic['last_user_id'], 's', array('class' => 'border1'))?></div>
	<div class="left ml10" style="width: 525px;">
		<div class="fs11 pb5">
			<div class="left">
				<span class="quiet"><?=t('Всего сообщений')?>: </span>
				<b><?=$topic['messages_count']?></b>

				<div class="quiet mt5"><?=t('Последнее сообщение')?></div>
				<?=user_helper::full_name($topic['last_user_id'], false)?>,
				<?=date_helper::human($topic['updated_ts'], ', ')?>
				<a class="ml10" href="/ppo/talk_topic?id=<?=$id?>&last=1"><?=t('Смотреть')?> &rarr;</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
