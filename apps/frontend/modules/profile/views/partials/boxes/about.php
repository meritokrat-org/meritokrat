<? if ( $user_data['about'] ) { ?>
	<h2 class="column_head"><?=t('О себе')?></h2>
	<div class="content_pane" id="pane_bio">
		<p class="fs12 mt10"><?=stripslashes(htmlspecialchars($user_data['about']))?></p>
	</div>
<? } ?>