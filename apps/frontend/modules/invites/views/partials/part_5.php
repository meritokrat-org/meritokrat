<div>
	<? $num = 0; ?>
	<? foreach ($list_5 as $item) { ?>
		<? $num++; ?>
		<? $group = team_peer::instance()->get_item($item['obj_id']) ?>
		<? load::view_helper('group');
		include 'team.php';
	} ?>
</div>