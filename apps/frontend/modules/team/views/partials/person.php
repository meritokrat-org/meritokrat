<div class="box_empty box_content left p10" style="width: 343px; margin: 5px; height: 90px;">
	<? $user = user_data_peer::instance()->get_item($id);?>
	<? if($user["user_id"] > 0){
		$photo = $user["user_id"].$user["photo_salt"];
	} ?>
	<div style="width: 70px; height: 90px; float: left; background-image: url(https://image.meritokrat.org/t/user/<?=$photo ? $photo : "0" ?>.jpg); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>

	<div style="margin-left: 87px;height: 95px;">
		<div style="height: 55px;">
			<b><?=user_helper::full_name($id) ?></b><br/>

			<? if($user["user_id"] > 0){ ?>
				<? $friend = user_auth_peer::instance()->get_item($user["user_id"]) ?>
				<span class="fs11 quiet">
	                <?= user_auth_peer::get_status($friend['status']) ?>
	                <? if (session::has_credential('admin')) { ?>
		                <?= ', *' . team_members_peer::instance()->get_type($group['id'], $user["user_id"]) ?>
	                <? } ?>
				</span>
			<? } ?>
		</div>

		<? if(($user["user_id"] > 0) && (rating_helper::get_user_rank($id) > 0)){ ?>
			<div style="font-size: 12px; width: 120px; float: left">
				<?=t('Рейтинг')." <b>".(session::is_authenticated() ? "<a href='/admin/rating?page=".ceil(rating_helper::get_user_rank($id) / 50) . "'>" : "") . ((floatval(rating_helper::get_rating_by_id($id))) ? rating_helper::get_user_rank($id) : " - ") . (session::is_authenticated() ? "</a>" : "") . "</b><br/>"?>
				<?=t('Баллы')." <b>".(session::is_authenticated() ? "<a href='/profile-".$id."?atab=rating'>" : "").((floatval(rating_helper::get_rating_by_id($id))) ? number_format(floatval(rating_helper::get_rating_by_id($id)), 0, '.', ' ') : " - ") . (session::is_authenticated() ? "</a>" : "")?></b>
			</div>
		<? } ?>

		<? if (session::is_authenticated()) { ?>
			<div class="fs11" style="text-align: right">
				<a href="/messages/compose?user_id=<?= $id ?>"><?= t('Написать') ?></a>
			</div>
		<? } ?>
		<? if (session::has_credential('admin') || team_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
			<div class="fs11" style="text-align: right">
				<a href="/team/delete_user?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= t('Удалить') ?></a>
			</div>
		<? } ?>

	</div>
	<div class="clear"></div>
</div>