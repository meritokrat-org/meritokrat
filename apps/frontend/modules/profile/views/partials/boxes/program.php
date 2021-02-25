<? if ( $candidate['program'] ) { ?>
	<div class="content_pane hide" id="pane_program">
		<? if ( session::get_user_id() == $user['id'] ) { ?>
			<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
				<a class="ml10" href="/profile/edit"><?=t('Редактировать программу')?></a>
			</div>
		<? } ?>
		<p class="fs12 mt10"><?=$candidate['program']?></p>
	</div>
<? } ?>