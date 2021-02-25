<div class="sub_menu mt5 mb10">
	<a href="/team" <?= ($sub_menu == '/team' && !request::get_string('req')) ? 'class="bold"' : '' ?>><?= t('Все') ?></a>
	<a href="/team/new" <?= $sub_menu == '/team/new' ? 'class="bold"' : '' ?>><?= t('Новые') ?></a>
	<? if ($allow_create) { ?>
		<a href="/team/create" <?= $sub_menu == '/team/create' ? 'class="bold"' : '' ?>><b><?= t('Создать') ?>
				команду</b></a>
	<? } ?>
	<? $is_memeber = db::get_scalar("SELECT count(*)
                FROM team_members
                WHERE user_id = :user_id 
                AND group_id in (SELECT id FROM team WHERE active=:active)", array('user_id' => session::get_user_id(), 'active' => 1));
	if ($is_memeber) {
		$team = db::get_row("SELECT id,number FROM team WHERE active=:active AND category=:category
            AND id in(SELECT group_id FROM team_members WHERE user_id = :user_id)", array('user_id' => session::get_user_id(),
			'active' => 1, 'category' => 1));
		?>
		<a href="/team<?= $team['id'] ?>/<?= $team['number'] ?>"><?= t('Моя') ?> команда</a>
	<? } ?>
	<div class="right mr15">
		<form action="/team/search" method="GET">
			<input name="req" type="text" class="text" value="<?= request::get_string('req') ?>"/>
			<input name="submit" type="submit" class="button" value="<?= t('Поиск') ?>"/>
		</form>
	</div>
</div>