<h1 class="column_head mt10 mr10">
	<a href="/events"><?= t('События') ?></a>
						→
	<a href="/event<?=$event['id']?>"><?=htmlspecialchars($event['name'])?></a>
</h1>
<div class="left"><? foreach ( $list as $id ) include 'partials/member.php'; ?></div>
<div class="right pager"><?=pager_helper::get_short($pager)?></div>