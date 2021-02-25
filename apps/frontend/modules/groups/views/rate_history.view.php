<? $sub_menu = '/blogs'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

	<div class="column_head">
		<h1 class="left"><a href="/blogpost<?=$post_data['id']?>"><?=stripslashes(htmlspecialchars($post_data['title']))?></a></h1>
	</div>

	<table class="fs11">
		<? foreach ( $list as $data ) { ?>
			<tr>
				<td width="50%"><?=user_helper::full_name($data['user_id'])?></td>
				<td><?=$data['rate']?></td>
			</tr>
		<? } ?>
	</table>

</div>