<div>
	<? $num = 0; ?>
	<? foreach ($list_6 as $item) { ?>
		<? $num++; ?>
		<? $group = reform_peer::instance()->get_item($item['obj_id']) ?>
		<? load::view_helper('group');
		include 'projects.php';
	} ?>
</div>