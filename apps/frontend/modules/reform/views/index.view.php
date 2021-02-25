<? $sub_menu = '/projects'; ?>
<? include 'partials/sub_menu.php';
load::view_helper('group');
load::model('groups/groups'); ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">
	<h1 class="column_head">
		<a href="/projects/index?category=3"
		   class=" mr15<?= request::get_int('category') == 3 ? '' : ' white' ?>"><?= t('Киев') ?>
			&nbsp;<?= (int)db::get_scalar("SELECT count(*) FROM reform WHERE category=3 AND active=1") ?></a>
		<a href="/projects/index?category=2"
		   class="mr15<?= request::get_int('category') == 2 ? '' : ' white' ?>"><?= t('Районы') ?>
			&nbsp;<?= (int)db::get_scalar("SELECT count(*) FROM reform WHERE category=2 AND active=1") ?></a>
		<a href="/projects" class="mr15 <?= request::get_int('category') == 1 ? '' : ' white' ?>"><?= t('Местные общины') ?>
			&nbsp;<?= (int)db::get_scalar("SELECT count(*) FROM reform WHERE category=1 AND active=1") ?></a>
		<? /* if ( $cur_type ) { ?>
			<a href="/ppo"><?=t('Партийные организации')?></a> &rarr;
			<?=ppo_peer::get_type($cur_type)?>
		<? } elseif ( $cur_level ) { ?>
			<a href="/ppo"><?=t('Партийные организации')?></a> &rarr;
			<?=ppo_peer::get_level($cur_level)." ".t('уровень')?>
                <? } elseif ( $cur_category ) { ?>
			<a href="/ppo"><?=t('Партийные организации')?></a> &rarr;
			<?=ppo_peer::get_category($cur_category)?>
                <? } elseif ( $cur_region ) { ?>
			<a href="/ppo"><?=t('Партийные организации')?></a> &rarr;
			<?$region=geo_peer::instance()->get_region($cur_region); 
                        echo $region['name_'. translate::get_lang()];?>
                <? }  elseif ( request::get_string('req') ) { ?>
			<?=t('Поиск')?>
		<? } else { ?>
			<?=request::get_int('user_id')>0 ? user_helper::full_name(request::get_int('user_id'),false).' &mdash; ' : ''?> <?=t('Все партийные организации')?>
		<? }*/ ?>
		<span class="right">
                   <a class="<?= request::get_int('category') == 'all' ? 'white' : '' ?>"
                      href="/projects"><?= t('Все') ?>
	                   (<?= (int)db::get_scalar("SELECT count(*) FROM reform WHERE active=1") ?>)</a>
                </span>
	</h1>

	<? if (request::get_string('req')) { ?>
		<div class="mt5 fs11 mb10"><a href="/projects" class="cgray">&larr; <?= t('Вернуться к общему списку') ?></a>
		</div>
	<? } ?>

	<? if ($hot) {
		foreach ($hot as $id) {
			include 'partials/group.php';
		}
	} else {
		?>
		<div class="mb10 p10 box_content">
			<div class="acenter">
				<?= t('Пока нет') ?>
			</div>
			<div class="clear"></div>
		</div>
	<? }
	?>
	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?= pager_helper::get_full($pager) ?></div>
</div>
<style type="text/css">
	.column_head a.white {
		color: #fff !important;
	}
</style>