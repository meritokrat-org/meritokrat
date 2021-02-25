<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Лента модераторов')?></h1>

	<? foreach ( $feed as $item ) { ?>
		<div class="box_content p10 mb10">
			<?=date('H:i d.m', $item['created_ts'])?>
			<?=user_helper::full_name($item['user_id'])?>

			<div class="fs10 mt10">
				<p>
                                    <b><?=$types[$item['type']]?>:</b> <?=$item['link']?>
                                    <br/>
                                    <b>Причина <?=($item['action']==1)?t('редактирования'):t('удаления')?>:</b> <?=$item['why']?>
                                </p>
				<?=stripslashes($item['text'])?>
                                <br/><br/><b>Автор:</b> <?=user_helper::full_name($item['author_id'])?>
                                <a class="right" href="/admin/undo?id=<?=$item['id']?>"><?=t('Восстановить')?></a>
			</div>
		</div>
	<? } ?>

	<div class="box_content p10 mb10 fs11">
		<? if ( $page > 1 ) { ?>
			<a href="/admin/mfeed?page=<?=$page - 1?>">&larr; Назад</a> &nbsp; &nbsp;
		<? } ?>
			
		<a href="/admin/mfeed?page=<?=$page + 1?>">Далее &rarr;</a>
	</div>

</div>
