<? $sub_menu = '/ppo/mine'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">
	<h1 class="column_head"><?=t('Мои сообщества')?></h1>
	<? foreach ( $list as $id ) { include 'partials/group.php'; } ?>
</div>