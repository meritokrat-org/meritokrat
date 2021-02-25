<? $group = team_peer::instance()->get_item($item['obj_id']);
$members_cnt = count(team_members_peer::instance()->get_members($group['id'], false, $group));
if ($group['category'] == 2) $sqladd = "OR group_id IN(SELECT id FROM team WHERE city_id=" . (int)$group['city_id'] . " AND category=1)";
elseif ($group['category'] == 3) $sqladd = "OR group_id IN(SELECT id FROM team WHERE region_id=" . (int)$group['region_id'] . " AND category=2)";
?>
FYGUHJIKL:HUYI
<div id="invite_item_<?= $item['id'] ?>" class="mb10 p10 box_content">
	<div class="left"><?= group_helper::photo($group['id'], 't', true, array()) ?></div>
	<div style="margin-left: 85px;" class="ml10">
		<a href="/team<?= $item['obj_id'] ?>/<?= $group['number'] ?>"><b><?= stripslashes(htmlspecialchars($group['title'])) ?></b></a>
		&nbsp;&nbsp;&nbsp;&nbsp; <? if (session::has_credential('admin')) { ?> <?= $group['active'] ? 'схвалена' : 'не схвалена' ?><? } ?>
		<div class="mt5 quiet fs11">
			<? #team_peer::get_type($group['type'])?>
		</div>
		<div class="mt5 quiet fs11"><?= t('Членов') ?>
			<b><a href="/team/members?id=<?= $group['id'] ?>"><?= $members_cnt ?></a></b>
			&nbsp;
			<?= t('Обсуждений') ?> <b><a
					href="/team/posts?group_id=<?= $group['id'] ?>"><?= db::get_scalar('SELECT count(id) FROM blogs_posts WHERE (team_id = :team_id ' . str_replace("group_id", "team_id", $sqladd) . ')', array('team_id' => $group['id'])) ?></a></b>
			&nbsp;
			<?= t('Комментариев') ?> <b><a
					href="/team/posts?group_id=<?= $group['id'] ?>"><?= db::get_scalar('SELECT count(id) FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE (team_id = :team_id ' . str_replace("group_id", "team_id", $sqladd) . '))', array('team_id' => $group['id'])) ?></a></b>
		</div>
	</div>
	<div class="mt10" style="margin-left:85px;">
		<a class="uline button p5" style="text-decoration:none;" href="javascript:;"
		   onclick="invitesController.group('<?= $item['id'] ?>','1');"><?= t('Вступить') ?></a>&nbsp;&nbsp;
		<a class="uline nopromt button p5" style="text-decoration:none;" href="javascript:;"
		   onclick="invitesController.group('<?= $item['id'] ?>','2');"><?= t('Отказать') ?></a>
	</div>
</div>