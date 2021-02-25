<? $sub_menu = '/blogs'; ?>
<? if(session::is_authenticated()){ ?>
	<? include 'partials/sub_menu.php' ?>
<? } ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

	<div class="column_head">
		<h1 class="left"><a href="/blogpost<?=$history_post_data['id']?>"><?=stripslashes(htmlspecialchars($history_post_data['title']))?></a></h1>
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
