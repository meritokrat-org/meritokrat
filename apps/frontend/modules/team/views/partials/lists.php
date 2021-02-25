<?
$id = $id["group_id"];
$group = team_peer::instance()->get_item($id);
if ($group) {
#$members_cnt = count(team_members_peer::instance()->get_members($group['id'],false,$group));
	$members_cnt = $cntarr[$id];
	if ($group['category'] == 2) $sqladd = "OR group_id IN(SELECT id FROM team WHERE city_id=" . (int)$group['city_id'] . " AND category=1)";
	elseif ($group['category'] == 3) $sqladd = "OR group_id IN(SELECT id FROM team WHERE region_id=" . (int)$group['region_id'] . " AND category=2)";
	$post_cnt = db::get_scalar('SELECT count(id) FROM blogs_posts WHERE (team_id = :team_id '
		//.str_replace("group_id","team_id",$sqladd)
		. ')', array('team_id' => $group['id']));
	$com_cnt = db::get_scalar('SELECT count(id) FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE (team_id = :team_id '
//.str_replace("group_id","team_id",$sqladd)
		. '))', array('team_id' => $group['id']));
	?>

	<div class="mb10 p10 box_content">
		<div class="right fs11">
			<? if (session::has_credential('admin') || team_members_peer::instance()->allow_edit(session::get_user_id(), $group)) { ?>
				<a class="bold right" href="/team/edit?id=<?= $group['id'] ?>"><?= t('Редактировать') ?></a>
			<? } ?>
			<? if (session::has_credential('admin')) { ?>
				<br/><span
					class="fs11">   <? if (!$group['active'] && (request::get_int('status') == 1 || session::has_credential('admin'))) { ?>
						<a class="ml10 fs11" href="/team/approve_team?team_id=<?= $group['id'] ?>">Схвалити</a>
						<a class="ml10 fs11" href="/team/delete_team?team_id=<?= $group['id'] ?>"
						   onclick="return confirm('Удалить эту команду ?');">Відмовити</a>
					<? } ?>
					<a onclick="return confirm('<?= t("Удалить эту команду ?") ?>');"
					   href="/team/delete_team?team_id=<?= $group['id'] ?>"
					   class="ml10 fs11"><?= t('Удалить') ?></a><br/></span>
			<? } ?>
		</div>
		<div class="left">
			<? if ($group['photo_salt']) { ?>
				<?= user_helper::team_photo(user_helper::team_photo_path($group['id'], 't', $group['photo_salt'])) ?>
			<? } else {
				?>
				<?= group_helper::photo(0, 't', false) ?>
				<?
			} ?>
		</div>
		<div style="margin-left: 85px;" class="ml10">
			<? if (request::get('bookmark')) { ?>
				<? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 8, $group['id']); ?>
				<a class="bookmark mb10 ml5 right" style="<?= ($bkm) ? 'display:none;' : 'display:block;' ?>"
				   href="#add_bookmark"
				   onclick="Application.bookmarkThisItem(this,'8','<?= $group['id'] ?>');return false;"><b></b><span><?= t('В закладки') ?></span></a>
				<a class="unbkmrk mb10 ml5 right" style="<?= ($bkm) ? 'display:block;' : 'display:none;' ?>"
				   href="#del_bookmark"
				   onclick="Application.unbookmarkThisItem(this,'8','<?= $group['id'] ?>');return false;"><b></b><span><?= t('Удалить из закладок') ?></span></a>
			<? } ?>
			<a href="/team<?= $group['id'] ?>/<?= $group['number'] ?>"><?= stripslashes(htmlspecialchars($group['title'])) ?></a>
			&nbsp;&nbsp;&nbsp;&nbsp;

			<div class="mt5 quiet fs11">
				<? if (session::has_credential('admin')) { ?>*<?= t('Создал') ?>: <?= user_helper::full_name($group['creator_id'], true, array(), false) ?><? } ?>
			</div>
			<div class="mt5 quiet fs11"><?= t('Участников') ?>
				<b><a href="/team/members?id=<?= $group['id'] ?>"><?= $members_cnt ?></a></b>
				&nbsp;
				<?= t('Обсуждений') ?> <b><a href="/team/posts?group_id=<?= $group['id'] ?>"><?= $post_cnt ?></a></b>
				&nbsp;
				<?= t('Комментариев') ?> <b><a href="/team/posts?group_id=<?= $group['id'] ?>"><?= $com_cnt ?></a></b>
				<?
				if ($group['privacy'] == team_peer::PRIVACY_PRIVATE) {
					load::model('team/applicants');
					$applicants = team_applicants_peer::instance()->get_by_group($group['id']);
					if (session::has_credential('admin') || team_peer::instance()->is_moderator($group['id'], session::get_user_id())) {
						?><?= t('Заявок') ?>    <b><a href="/team/edit?id=<?= $group['id'] ?>"><span id="new_applicants"
						                                                                             class="green fs10"><?= $applicants ? '+' . count($applicants) : '' ?></span></a>
						</b><? }
				} ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<? }else{ ?>
	<div class="mb10 p10 box_content">
		<div class="acenter">
			Пока нет				</div>
		<div class="clear"></div>
	</div>
<?}?>
