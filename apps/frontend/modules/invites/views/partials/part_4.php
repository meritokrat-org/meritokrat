<div>
	<? $num = 0; ?>
	<? foreach ($list_4 as $item) { ?>
		<? $num++; ?>
		<? $group = ppo_peer::instance()->get_item($item['obj_id']) ?>
		<? load::view_helper('group');
		include 'ppo.php';
	} ?>
</div>