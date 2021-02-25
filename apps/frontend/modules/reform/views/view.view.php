<? load::model('user/user_auth'); ?>
<? load::model('reform/members'); ?>
<? load::model('reform/applicants'); ?>
<? load::model('reform/news'); ?>

<? $allow_edit = reform_members_peer::instance()->allow_edit(session::get_user_id(), $group); ?>
<? if(session::has_credential("admin")) { $allow_edit = true; } ?>

<div class="profile d-flex flex-row">
	<div style="width: 230px">
		<a style="margin: 5px 0 5px 8px; display: block" href="/projects">← <?= t('Назад к списку') ?></a>

		<div style="text-align:center; margin-bottom: 5px;">
			<? if ($group['photo_salt']) { ?>
				<?= user_helper::reform_photo(user_helper::reform_photo_path($group['id'], 'p', $group['photo_salt'])) ?>
			<? } else {
				load::view_helper('group');
				load::model('groups/groups'); ?>
				<?= group_helper::photo(0, 'p', false) ?>
				<?
			} ?>
		</div>

		<div style="width: 227px; margin: auto;">

			<div class="ml5 profile_menu">
				<?
				/*db::get_scalar("SELECT count(*)
		FROM reform_members
		WHERE group_id IN(SELECT id FROM reform WHERE category=1)
		AND user_id=".session::get_user_id())==0*/

				if ($group['ptype'] != 1 || ($group['ptype'] == 1 && $user_data['region_id'] == $group['region_id'])) {
					if ($user['status'] == 20 && $group['category'] == 1) { ?>
						<? if (!reform_applicants_peer::instance()->is_applicant($group['id'], session::get_user_id())) { ?>
							<a id="menu_apply" href="javascript:;" style="<?= $is_member ? 'display:none;' : '' ?>"
							   rel="<?= $group['id'] ?>">
								<?= tag_helper::image('icons/check.png', array('class' => 'vcenter mr5')) ?>
								<?= ($has_invite) ? t('Принять приглашение') : t('Подать заявку на вступление') ?>
							</a>
							<div id="text_apply"
							     class="hidden quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
						<? } else { ?>
							<div class="quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
						<? } ?>
					<? }
				} ?>
			</div>

			<div class="share left ml5" style="margin-left:40px">
				<?// if ($allow_edit && $group['category'] == 1) { ?>
					<a href="javascript:;" onclick="Application.inviteItem('projects', 6, <?= $group['id'] ?>)"
					   class="share mb5 ml15"><span class="fs18"><?= t('Пригласить') ?></span></a>
				<?// } ?>
			</div>

			<br/>
			<? if (!$privacy_closed) {
				$group['glava_id'] = (int)reform_members_peer::instance()->get_user_by_function(1, $group['id'], $group);
				$group['secretar_id'] = (int)reform_members_peer::instance()->get_user_by_function(2, $group['id'], $group);
				?>
				<div class="column_head_small mt15"><?= t('Руководство') ?></div>
				<div class="fs11 p10 mb5 box_content">
					<div style="height: <?= (session::has_credential('admin')) ? '100' : '70'; ?>px;">
						<div class="left">
							<?= user_helper::photo($group['glava_id'], 's', array('class' => 'border1'), true, 'user', '', false, false) ?>
						</div>
						<div class="left ml10">
							<?= user_helper::full_name($group['glava_id']) ?>
							<br/>
							Координатор 1<br/>
							<? $udata = user_auth_peer::instance()->get_item($group['glava_id']) ?>
							<? if ($udata['status'] > 0) { ?><span
								class="cgray"><?= user_auth_peer::get_status($udata['status']) ?></span> <? } ?>
						</div>
					</div>
				</div>
				<div class="fs11 p10 mb5 box_content">
					<div style="height: <?= (session::has_credential('admin')) ? '100' : '70'; ?>px;">
						<div class="left">
							<?= user_helper::photo($group['secretar_id'], 's', array('class' => 'border1'), true, 'user', '', false, false) ?>
						</div>
						<div class="left ml10">
							<?= user_helper::full_name($group['secretar_id']) ?>
							<br/>
							Координатор 2<br/>
							<? $udata = user_auth_peer::instance()->get_item($group['secretar_id']) ?>
							<? if ($udata['status'] > 0) { ?><span
								class="cgray"><?= user_auth_peer::get_status($udata['status']) ?></span> <? } ?>
						</div>
					</div>
				</div>
				<br/>
				<div class="column_head_small">
					<span class="left"><a href="/projects/news?id=<?= $group['id'] ?>"
					                      class="fs11 right"><?= t('Новости') ?></a></span>

					<div class="clear"></div>
				</div>
				<? load::view_helper('image') ?>
				<? if ($news) { ?>
					<? foreach ($news as $id) { ?>
						<? $new = reform_news_peer::instance()->get_item($id) ?>
						<div class="fs11 mb5 pb5 clear" style="background: #F7F7F7;">
							<div style="width: 60px;" class="left mr10">
								<?= user_helper::reform_photo(user_helper::reform_photo_path($group['id'], 's', $group['photo_salt'])) ?>
							</div>
							<div class="fs11 ml5 white bold"></div>
							<div class="fs11 p5">
								<div class="mb5 quiet"><?= date_helper::human($new['created_ts'], ', ') ?></div>
								<a href="/projects/newsread?id=<?= $new['id'] ?>" class="fs12 bold"
								   style="color:black"><?= stripslashes(nl2br(htmlspecialchars($new['title']))) ?></a>
							</div>
						</div>
					<? } ?>
				<? } else { ?>

					<div class="fs11 pb5" style="background: #F7F7F7;">
						<div class="fs11 ml5 white bold"></div>
						<div class="fs11 p5">
							<?= t('Новостей еще нет') ?>
						</div>
					</div>
				<? } ?>
				<? if (reform_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
					<div class="box_content left mt5 fs11"><a href="/projects/add_news?id=<?= $group['id'] ?>"
					                                          class="ml10 fs11"><?= t('Добавить новость') ?> &rarr;</a>
					</div>
				<? } ?>                <br/>
			<? } ?>
		</div>
	</div>

	<div class="flex-grow-1 pt-2">
		<h1 class="mb5" style="font-size: 20px; line-height: 1; height: 60px; overflow: hidden; color: rgb(102, 0, 0);">
			<?= stripslashes(htmlspecialchars($group['title'])) ?>
		</h1>

		<div style="margin-top: -35px;" class="left">
                <span class="fs11 bold"><? if ($group['category'] == 0) $group['category'] = 1;
	                $levels = reform_peer::get_levels();
	                echo $levels[$group['category']]; ?></span>
		</div>
		<div class="right fs11 quiet"><?= $group['active'] == 1 ? t('Одобрено') : t('Не одобрено') ?></div>
		<div class="left fs11 quiet bold">

			<a rel="common" class="tab_menu selected ml10 fs11 quiet"
			   href="javascript:;"><?= t('Основные сведения') ?></a>
			<? if (session::has_credential('admin') || (reform_members_peer::instance()->allow_edit(session::get_user_id(), $group)
					&& sizeof(array_intersect(array(113, 123), $user_functions)) > 0)
			) { ?>
				<a rel="more" class="tab_menu ml10 fs11" href="javascript:;"><?= t('Служебная информация') ?></a>
			<? } ?>
			<? if ($allow_edit) { ?>
				<a href="/projects/edit?id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Редактировать') ?></a>
			<? } ?>
			<? if (session::has_credential('admin')) { ?>
				<?= $group['active'] != 1 ? '<a href="/projects/approve_ppo?ppo_id=' . $group['id'] . '" class="ml10 fs11">' . t('Одобрить') . '</a>' : '' ?>
				<a onclick="return confirm('Видалити цю партiйну органiзацiю?');"
				   href="/projects/delete_ppo?ppo_id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Удалить') ?></a>
			<? } ?>
			<? if ($allow_edit) { ?>
				<a href="/messages/compose_ppo?ppo=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Рассылки') ?></a>
			<? } ?>
		</div>

		<div class="clear"></div>
		<table id="common_box" class="tbox fs12 mt10" style="margin-bottom:0px;">
			<tr>
				<td width="40%" class="bold aright">
					<?= t('Регион') ?> / <?= t('Район') ?> / <?= t('Нас. пункт') ?>:
				</td>
				<td>
					<? $region = geo_peer::instance()->get_region($group['region_id']); ?>
					<?= $region['name_' . translate::get_lang()] ?><? $city = geo_peer::instance()->get_city($group['city_id']); ?>
					<?= $group['category'] < 3 ? ' / ' . $city['name_' . translate::get_lang()] : '' ?><? if ($group['location']) { ?> / <?= stripslashes(htmlspecialchars($group['location'])) ?><? } ?>
				</td>
				<? if ($group['coords']){ ?>
			<tr>
				<td width="40%" class="bold aright">
					<?= t('Территория деятельности') ?>:
				</td>
				<td>
					<a id="teritory" href="javascript:;"><?= t('Посмотреть на карте') ?></a>
				</td>
			</tr><? } ?>
			<? if ($group['category'] == 1) { ?>
				<tr>
				<td width="40%" class="bold aright">
					<?= t('Тип') ?>:
				</td>
				<? if (session::is_authenticated()) { ?>
					<td>
						<? $types = reform_peer::get_ptypes();
						echo $types[$group['ptype']] ?>
						<? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 8, $group['id']); ?>
						<a class="right bookmark mb10 ml5 b8" style="<?= ($bkm) ? 'display:none' : '' ?>"
						   href="#add_bookmark"
						   onclick="Application.bookmarkItem('8','<?= $group['id'] ?>');return false;"><b></b><span><?= t('В закладки') ?></span></a>
						<a class="right unbkmrk mb10 ml5 b8" style="<?= ($bkm) ? '' : 'display:none' ?>"
						   href="#del_bookmark"
						   onclick="Application.unbookmarkItem('8','<?= $group['id'] ?>');return false;"><b></b><span><?= t('Удалить из закладок') ?></span></a>
					</td>
				<? } ?>
				</tr><? } ?>
		</table>
		<? if (session::has_credential('admin') || reform_members_peer::instance()->allow_edit(session::get_user_id(), $group)) { ?>
			<table id="more_box" class="tbox hidden fs12 mt10" style="margin-bottom:0px;">
				<tr>
					<td class="bold aright">
						<?= t('Создал') ?>
					</td>
					<td>
						<?= user_helper::full_name($group['creator_id']) ?>
					</td>
				</tr>
				<tr>
					<td class="bold aright">
						<?= t('Адрес') ?>
					</td>
					<td>
						<?= stripslashes(htmlspecialchars($group['adres'])) ?>
					</td>
				</tr>
				<? if ($group['dzbori'] || $group['uchasniki']) { ?>
					<tr>
					<td style="width:50%;" class="aright mr15 bold"><?= t('Учредительное собрание') ?>:</td>
					<td></td>
					</tr><? } ?>
				<? if ($group['dzbori']) { ?>
					<tr>
					<td class="aright"><?= t('Дата') ?></td>
					<td>
						<?= $group['dzbori'] ? date("d-m-Y", $group['dzbori']) : '' ?>
					</td>
					</tr><? }
				$members = explode(',', str_replace(array('{', '}'), array('', ''), $group['uchasniki']));
				if ($members[0] > 0) { ?>
					<tr>
						<td class="aright">Учасники</td>
						<td>
							<? $mc = 1;
							foreach ($members as $m): ?>
								<?= user_helper::full_name((int)$m, true, array(), false) ?>
								<?= $mc != count($members) ? ', ' : '' ?>
								<? $mc++; endforeach; ?>
						</td>
					</tr> <? } ?>
				<? if ($group['uhvalnum'] || $group['duhval'] || $group['dovidnum']) { ?>
					<tr>
						<td class="aright mr15 bold">Рiшення Голови про затвердження:</td>
						<td></td>
					</tr>
				<? } ?>
				<? if ($group['uhvalnum']) { ?>
					<tr>
					<td class="aright">№</td>
					<td>
						<?= $group['uhvalnum'] ?>
					</td>
					</tr><? } ?>
				<? if ($group['duhval']) { ?>
					<tr>
					<td class="aright"><?= t('Дата') ?></td>
					<td>
						<?= ($group['duhval']) ? date("d-m-Y", $group['duhval']) : '' ?>
					</td>
					</tr><? } ?>
				<? if ($group['dovidnum'] || $group['doviddate'] || $group['protokolsdate'] || $group['dovidsdate']) { ?>
					<tr>
						<td class="aright mr15 bold">Легалiзацiя:</td>
						<td></td>
					</tr>
				<? } ?>
				<? if ($group['dovidnum']) { ?>
					<tr>
					<td class="aright">№ довiдки</td>
					<td>
						<?= $group['dovidnum'] ?>
					</td>
					</tr><? } ?>
				<? if ($group['doviddate']) { ?>
					<tr>
					<td class="aright"><?= t('Дата выдачи') ?></td>
					<td>
						<?= ($group['doviddate']) ? date("d-m-Y", $group['doviddate']) : '' ?>
					</td>
					</tr><? } ?>
				<? if ($group['svidcopy']) { ?>
					<tr>
						<td class="aright">Копія свідоцтва видана</td>
						<td>
						</td>
					</tr><? } ?>
				<? if ($group['protokolsdate'] || $group['dovidsdate']) { ?>
					<tr>
						<td class="aright mr15 bold">Отримання документів Секретаріатом:</td>
						<td></td>
					</tr><? } ?>
				<? if ($group['protokolsdate']) { ?>
					<tr>
						<td class="aright">Протокол</td>
					</tr><? } ?>
				<? if ($group['dovidsdate']) { ?>
					<tr>
						<td class="aright">Довiдка / Свiдоцтво</td>
					</tr><? } ?>
				<? if ($group['zayava']) { ?>
					<tr>
						<td class="aright">Заява</td>
					</tr><? } ?>
				<? if ($group['vklnumber'] || $group['vkldate']) { ?>
					<tr>
						<td class="aright mr15 bold">Рiшення Голови про включення в структуру:</td>
						<td></td>
					</tr><? } ?>
				<? if ($group['vklnumber']) { ?>
					<tr>
					<td class="aright">№</td>
					<td>
						<?= $group['vklnumber'] ?>
					</td>
					</tr><? } ?>
				<? if ($group['vkldate']) { ?>
					<tr>
					<td class="aright"><?= t('Дата принятия') ?></td>
					<td>
						<?= ($group['vkldate']) ? date("d-m-Y", $group['vkldate']) : '' ?>
					</td>
					</tr><? } ?>
				<? if ($group['svidvruch'] || $group['svidvig'] || $group['svidnum']) { ?>
					<tr>
						<td class="aright mr15 bold">Свідоцтво:</td>
						<td></td>
					</tr><? } ?>
				<? if ($group['svidnum']) { ?>
					<tr>
					<td class="aright">№ Свідоцтва</td>
					<td>
						<?= $group['svidnum'] ?>
					</td>
					</tr><? } ?>
				<? if ($group['svidvig']) { ?>
					<tr>
						<td class="aright">Виготовлення</td>
					</tr><? } ?>
				<? if ($group['svidvruch']) { ?>
					<tr>
						<td class="aright">Вручення</td>
					</tr><? } ?>
				<? if ($group['svidcom'] != '') { ?>
					<tr>
					<td class="aright">Коментар</td>
					<td><?= $group['svidcom'] ?></td>
					</tr><? } ?>


				<!--        PARTY INVENTORY        -->
				<?
				if (!empty($inv_owners) && session::has_credential('admin')) {
					$inventory_types = user_party_inventory_peer::instance()->get_inventory_type();
					$inventory_types[0] = '&mdash;';
					ksort($inventory_types);
					foreach ($inventory_types as $inv_id => $inv_name) {
						$current = db::get_scalar("SELECT sum(inventory_count) FROM party_inventory WHERE inventory_type=:itype AND user_id IN (" . implode(',', $inv_owners) . ")", array('itype' => $inv_id));
						if ($current) { ?>
							<? if (!$spike) { ?>
								<tr>
									<td class="aright mr15 bold">Партійний інвентар</td>
									<td></td>
								</tr>
								<? $spike = 1;
							} ?>
							<tr>
								<td class="aright mr15"><?= $inv_name ?></td>
								<td>
									<?= $current; ?>
								</td>
							</tr>
						<? } ?>
					<? } ?>
				<? } ?>
				<!--        END        -->


			</table>
		<? } ?>
		<? if ($children_mpo) { ?>
			<div class="ml10 mt10">
				<h1 class="column_head mt10 mb10">
					<?= t('Районы') ?> <?= count($children_mpo) ?>
					<? if(count($children_mpo) > 9){ ?>
						<a style="cursor: pointer; float: right; text-transform: none; font-weight: normal;" id="show_all_mpo"><?=t("Развернуть")?> &downarrow;</a>
					<? } ?>
				</h1>

				<div class="pcontent_pane mpo_content">
					<? foreach ((array)$children_mpo as $pgroup) { ?>
						<div class="mpo_item" style="width: 33%; float: left;">
							<a href="/project<?= $pgroup['id'] ?>/<?= $pgroup['number'] ?>">
								<table style="margin: 0">
									<tr>
										<td style="width: 85%;">
											<?=str_replace("район", "р-н", $pgroup['title'])?>
										</td>
										<td style="width: 15%; font-weight: bold">
											<?=count(reform_members_peer::instance()->get_members($pgroup["id"])) ?>
										</td>
									</tr>
								</table>
							</a>
						</div>
					<? } ?>
				</div>
			</div>
			<div class="clear"></div>

			<script>
				$(document).ready(function(){
					$("#show_all_mpo").click(function(){
						if(($(".mpo_item").length > 9) && ($(".mpo_content").css("height") == "85px")){
							var height = Math.ceil($(".mpo_item").length / 3) * 29;

							$(".mpo_content").animate({
								height: height+"px"
							}, 800);

							$(this).html("<?=t("Свернуть")?> &uparrow;");
						}else{
							$(".mpo_content").animate({
								height: "85px"
							}, 800);

							$(this).html("<?=t("Развернуть")?> &downarrow;");
						}
					});
				});
			</script>
		<? } ?>
		<? if ($children_ppo) { ?>

			<div class="ml10 mt10">
				<h1 class="column_head mt10 mb10">
					<?= t('Местные общины') ?> <?= count($children_ppo) ?>
					<? if(count($children_ppo) > 9){ ?>
						<a style="cursor: pointer; float: right; text-transform: none; font-weight: normal;" id="show_all_ppo"><?=t("Развернуть")?> &downarrow;</a>
					<? } ?>
				</h1>

				<div class="pcontent_pane ppo_content">
					<? foreach ((array)$children_ppo as $pgroup) { ?>
						<div class="ppo_item" style="width: 33%; float: left;">
							<a href="/project<?= $pgroup['id'] ?>/<?= $pgroup['number'] ?>">
								<table style="margin: 0">
									<tr>
										<td style="width: 69%;">
											<?= $pgroup['title'] ?>
										</td>
										<td style="width: 30%; font-weight: bold">
											<?=count(reform_members_peer::instance()->get_members($pgroup["id"])) ?>
										</td>
									</tr>
								</table>
							</a>
						</div>
					<? } ?>
				</div>
			</div>
			<div class="clear"></div>

			<script>
				$(document).ready(function(){
					$("#show_all_ppo").click(function(){
						if(($(".ppo_item").length > 9) && ($(".ppo_content").css("height") == "85px")){
							var height = Math.ceil($(".ppo_item").length / 3) * 29;

							$(".ppo_content").animate({
								height: height+"px"
							}, 800);

							$(this).html("<?=t("Свернуть")?> &uparrow;");
						}else{
							$(".ppo_content").animate({
								height: "85px"
							}, 800);

							$(this).html("<?=t("Развернуть")?> &downarrow;");
						}
					});
				});
			</script>
		<? } ?>

		<div class="ml10 mt10">
			<h1 class="column_head mt10 mb10" style="">
				<?= t('Участники') ?> - <?= $members_cnt ?>
				<? /*if($group['location']!=''){?><?=stripslashes(htmlspecialchars($group['location']))?> - <?}?>
<?=$group['category']<3?$city['name_' . translate::get_lang()].' - ':''?> <?=$region['name_' . translate::get_lang()]*/ ?>
				<a class="right fs12" href="/projects/members?id=<?= $group['id'] ?>"
				   style="text-transform:none;font-weight:normal;">
					<?= t('Все члены') ?> &rarr;</a>
			</h1>

			<div class="left">
				<? foreach ((array)$users as $k => $v) {
					$user = user_data_peer::instance()->get_item($v);
					?>
					<div class="left" style="width: 85px; margin: 0 1px 5px 0; height: 130px;">
						<a href="/profile-<?=$v?>">
							<div style="background-image: url('<?=context::get('image_server')."p/user/{$user["user_id"]}{$user['photo_salt']}.jpg" ?>'); width: 70px; margin: 0 auto 5px auto; height: 90px; background-repeat: no-repeat; background-size: cover; background-position: center"></div>
							<div style="word-wrap: normal; text-align: center; font-size: 12px">
								<? if(is_null($user["user_id"])){ ?>
									<?=t("Неизвестный участник")?>
								<? }else{ ?>
									<?=$user["first_name"]."<br>".$user["last_name"]?>
								<? } ?>
							</div>
						</a>
					</div>

				<? } ?>
			</div>
		</div>
		<div class="clear"></div>
		<div class="ml10">
			<div class="tab_pane">
				<a rel="events" href="javascript:;"><?= t('События') ?></a>
				<a rel="posts" href="javascript:;" class="selected"><?= t('Обсуждения') ?></a>
				<a rel="files" href="javascript:;"><?= t('Библиотека') ?></a>
				<a rel="foto" href="javascript:;"><?= t('Фото') ?></a>
				<a rel="report" href="javascript:;"><?= t('Отчеты') ?></a>
				<? if ($group['category'] == 3) { ?>
					<a rel="finance" href="javascript:;"><?= t('Финансы') ?></a>
				<? } ?>
				<div class="clear"></div>
			</div>
			<div id="pane_posts" class="content_pane">
				<? if (session::is_authenticated()) { ?>
					<div class="box_content p5 mb10 fs11"><a
							href="/projects/post_edit?group_id=<?= $group['id'] ?>&add=1"><?= t('Добавить тему') ?> &rarr;</a>
					</div>
				<? } ?>
				<? if (!$posts) { ?>
					<div class="m5 acenter fs12"><?= t('Мыслей еще нет') ?></div>
				<? } else { ?>
					<? foreach ($posts as $id) { ?>
						<? $post = blogs_posts_peer::instance()->get_item($id) ?>
						<div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
							<div class="mb5 bold fs12">
								<a href="/projects/post?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(htmlspecialchars($post['title'])) ?></a>
							</div>
							<div class="fs11 pb5">
								<div class="left quiet">
									<?= user_helper::full_name($post['user_id'], true, array('class' => 'mr10'), false) ?>
									<?= t('Комментариев') ?>:
									<b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post($id) ?></b>
									<?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					<? } ?>
				<? } ?>
				<div class="box_content p5 mb10 fs11"><a
						href="/projects/posts?group_id=<?= $group['id'] ?>"><?= t('Все мысли') ?> &rarr;</a></div>
			</div>
			<div id="pane_events" class="content_pane hidden">
				<? if (session::has_credential('admin') OR
					reform_peer::instance()->is_moderator($group['id'], session::get_user_id()) OR
					user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) OR
					user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id()) OR
					reform_members_peer::instance()->is_member($group["id"], session::get_user_id())
				) { ?>
					<div class="box_content p5 mb10 fs11"><a
							href="/events/create?type=4&content_id=<?= $group['id'] ?>"><?= t('Добавить событие') ?> &rarr;</a>
					</div>
				<? } ?>
				<? if ($events) { ?>
					<div class="mb10 box_content p10 mr10">
						<? foreach ($events as $event_id) { ?>
							<? $event = events_peer::instance()->get_item($event_id); ?>
							<div class="mb5 bold fs12">
								<a class="acenter mb10 ml10"
								   href="/event<?= $event_id ?>"><?= $event['name'] ?></a><br/>
							</div>
							<div class="quiet">
								<div class="fs11 pb5 ml10">
									<? if (date('d-m-Y', $event['start']) == date('d-m-Y', $event['end']))
										$kolu = date_helper::get_format_date($event['start'], false) . ', ' . t('с') . ' ' . date('H:i', $event['start']) . ' до ' . date('H:i', $event['end']);
									else $kolu = t('с') . ' ' . date_helper::get_format_date($event['start']) . ' ' . date('H:i', $event['start']) . ' <br/>до ' .
										date_helper::get_format_date($event['end']) . ' ' . date('H:i', $event['end']); ?><?= $kolu ?>
									<br/>
									<?= t('Организатор') . ": " ?>
									<?
									switch ($event['type']) {
										case 3:
											?>
											<a href="/profile-31" style="color:black"><?= t("Секретариат МПУ") ?></a>
											<?
											break;
										default:
											?>
											<?= user_helper::full_name($event['user_id'], true, array('style' => 'color:black'), false); ?>
											<?
									}
									?>
									<br/>
									<?= t('Событие посещают') ?>:
									<b><?= $event['users1sum'] + $event['users3sum'] + $event['users1count'] + $event['users3count'] ?> <?= t('участников') ?></b>
								</div>
								<div class="clear"></div>
							</div>
						<? } ?>
					</div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?= t('Событий еще нет') ?>
					</div>
				<? } ?>
			</div>
			<div id="pane_files" class="content_pane hidden">
				<div
					class="box_content p5 mb10 fs11"><? if (groups_members_peer::instance()->is_member($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
						<a href="/projects/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?> &rarr;</a>
					<? } ?>
				</div>
				<? if ($files) { ?>
					<div class="mt5 m5 fs12 mb5 left">
						<? foreach ($files as $file_id) {
							$file = reform_files_peer::instance()->get_item($file_id);
							if (isset($file['files']))
								$arr = unserialize($file['files']);
							?>
							<div class="box_content mt5 mb10" style="border-bottom: 1px solid #f7f7f7;width:520px;">
								<div class="left">
									<div class="ml5"><a
											href="<?= (isset($file['files'])) ? context::get('file_server') . $file['id'] . '/' . $arr[0]['salt'] . "/" . $arr[0]['name'] : $file['url'] ?>"><?= stripslashes(htmlspecialchars($file['title'])) ?></a>
									</div>
									<div class="left ml5 fs12"><?= $file['author'] ?></div>
								</div>
								<? if (isset($file['files'])) {
									foreach ($arr as $f) {
										$ext = end(explode('.', $f['name']));
										?>
										<div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
											<a href="<?= context::get('file_server') . $file['id'] . '/' . $f['salt'] . "/" . $f['name'] ?>">
												<img
													src="/static/images/files/<?= reform_files_peer::instance()->get_icon($ext) ?>">
											</a></div>
									<? }
								} else { ?>
									<div class="left ml5 <? //=$file['author'] ? 'mt15' : ''?>"><img
											src="/static/images/files/IE.png"></div> <? } ?>
								<? if ($file['lang'] == 'ua' or $file['lang'] == 'en') { ?>
									<div class="left ml5"
									     style="margin-top:  1<? //=$file['author'] ? '17' : '2'?>px;"><?= tag_helper::image('icons/' . $file['lang'] . '.png', array('')) ?></div><? } ?>
								<div class="right aright mr5"
								     style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?= $file['size'] ? $file['size'] : '' //$file['exts'] ? reform_files_peer::formatBytes(filesize($file['url'])) : '' ?>
									<?= tag_helper::image('icons/1.png', array('alt' => "Інформація", 'id' => $file['id'], 'class' => "info ml5 ")) ?>
									<? if (reform_peer::instance()->is_moderator($group['id'], session::get_user_id()) || $file['user_id'] == session::get_user_id()) { ?>
										<a href="/projects/file_edit?id=<?= $file['id'] ?>"><img class="ml5"
										                                                    alt="Редагування"
										                                                    src="/static/images/icons/2.png"></a>
										<a onclick="return confirm('Ви впевнені?');"
										   href="/projects/file_delete?id=<?= $file['id'] ?>"><img class="ml5"
										                                                      alt="видалення"
										                                                      src="/static/images/icons/3.png"></a>
									<? } ?>
								</div>
								<div class="clear"></div>
								<div id="file_describe_<?= $id ?>"
								     class="ml10 fs11 hidden"><?= stripslashes(htmlspecialchars($file['describe'])) ?></div>
							</div>
						<? } ?>
					</div>
					<div class="clear mb5"></div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?= t('') ?>
						<? if (reform_peer::instance()->is_moderator($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
							<a href="/projects/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?> &rarr;</a>
							<br/>
						<? } ?>
					</div>
				<? } ?>
				<div class="box_content p5 mb10 fs11"><a
						href="/projects/file?id=<?= $group['id'] ?>"><?= t('Все материалы') ?> &rarr;</a></div>
			</div>
			<div id="pane_foto" class="content_pane hidden">
				<? if (reform_peer::instance()->is_moderator($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
					<div class="box_content p5 mb10 fs11">
						<a href="/photo/add?type=2&oid=<?= $group['id'] ?>"><?= t('Добавить фото') ?> &rarr;</a>
					</div>
				<? } ?>
				<? if ($photos) { ?>
					<div class="mt10 p10 mb15 fs12 gallery" style="text-align:center">
						<? foreach ($photos as $photo_id) { ?>
							<a href="/photo?type=2&oid=<?= $group['id'] ?>" class="ml10" rel="prettyPhoto[gallery]">
								<?= photo_helper::photo($photo_id, 'h', array()) ?>
							</a>
						<? } ?>
						<br/>
						<a class="right fs12"
						   href="/photo?type=2&oid=<?= $group['id'] ?>"><?= t('Все фото') ?> &rarr;</a><br>
					</div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?= t('Фотографий еще нет') ?>
					</div>
				<? } ?>
			</div>

			<div id="pane_report" class="content_pane hidden">
				<div class="box_content p5 mb10 fs11">
					<a href="/eventreport/show&po_id=<?= $group['id'] ?>"><?= t('Все отчеты') ?> &rarr;</a>
				</div>
				<? if ($reports) { ?>
					<div class="mb10 box_content p10 mr10">
						<? foreach ($reports as $report_id) { ?>
							<? $report = eventreport_peer::instance()->get_item($report_id); ?>
							<div class="mb5 bold fs12">
								<a class="acenter mb10 ml10"
								   href="/eventreport/view&id=<?= $report_id ?>"><?= $report['name'] ?></a><br/>
							</div>
							<div class="quiet">
								<div class="fs11 pb5 ml10">
									<? if (date('d-m-Y', $report['start']) == date('d-m-Y', $report['end']))
										$kolu = date_helper::get_format_date($report['start'], false) . ', ' . t('с') . ' ' . date('H:i', $report['start']) . ' до ' . date('H:i', $report['end']);
									else $kolu = t('с') . ' ' . date_helper::get_format_date($report['start']) . ' ' . date('H:i', $report['start']) . ' <br/>до ' .
										date_helper::get_format_date($report['end']) . ' ' . date('H:i', $report['end']); ?><?= $kolu ?>
									<br/>
									<?= t('Организатор') . ": " ?>
									<?= user_helper::full_name($report['user_id'], true, array('style' => 'color:black'), false); ?>
									<? if (session::has_credential('admin') || $is_leader) { ?>
										<? $statuses = array(
											array(t('Новый'), 'green'),
											array(t('На утверждении'), 'blue'),
											array(t('На доработке'), 'red'),
											array(t('Утвержден'), 'black'),
											array(t('Мероприятие не состоялось'), 'red')
										) ?>
										<br/>Статус: <span
											style="color:<?= $statuses[$report['status']][1] ?>"><?= $statuses[$report['status']][0] ?></span>
									<? } ?>
								</div>
							</div>
						<? } ?>
					</div>
				<? } else { ?>
					<div class="m5 acenter fs12 p10">
						<?= t('Отчетов еще нет') ?>
					</div>
				<? } ?>
			</div>

			<? if ($group['category'] == 3) { ?>
				<div id="pane_finance" class="content_pane hidden">
					<? if (session::has_credential('admin')) { ?>
						<div class="box_content p5 mb10 fs11">
							<a href="/projects/edit&id=<?= $group['id'] ?>&tab=vidatki"><?= t('Редактировать') ?> &rarr;</a>
						</div>
					<? } ?>
					<div class="box_content p5 mb10 fs12">
						<?= t('Всего собрано вступительных взносов') ?>:
						<?= intval($finvtotal) ?> грн.
					</div>
					<div class="box_content p5 mb10 fs12">
						<?= t('Всего собрано членских взносов') ?>
						<?= intval($ftotal) ?> грн.
					</div>
					<div class="box_content p5 mb10 fs12">
						<?= t('Всего собрано целевых взносов') ?>
						<?= intval($ffondtotal) ?> грн.
					</div>
					<div class="box_content p5 mb10 fs12">
						<?= t('Всего (фонд)') ?>:
						<?= intval($ftotal) + intval($ffondtotal) ?> грн.
					</div>
					<? if ($finances && (session::has_credential('admin') || ($user['status'] == 20 && $user_data['region_id'] == $group['region_id']))) { ?>
						<? foreach ($finances as $finance_id) { ?>
							<div class="mb10 box_content p10 mr10">
								<? $finance = reform_finance_peer::instance()->get_item($finance_id); ?>
								<div class="quiet">
									<div class="fs11">
										<?= date('d.m.Y', $finance['date']) ?> - <b><?= $finance['summ'] ?> грн.</b>
										- <?= stripslashes($finance['text']) ?>
										<? $fsumm += $finance['summ'] ?>
									</div>
								</div>
							</div>
						<? } ?>
					<? } ?>
					<div class="box_content p5 mb10 fs12">
						<?= t('Всего затрат') ?>: <?= intval($fsumm) ?> грн.
					</div>
					<div class="box_content p5 mb10 fs12">
						<?= t('Остаток (фонд)') ?>: <?= intval($ftotal) + intval($ffondtotal) - intval($fsumm) ?> грн.
					</div>
				</div>
			<? } ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<?
if ($_SERVER['SERVER_NAME'] == 'meritokrat.org') {
	$key = 'ABQIAAAAeJTsA7ppykO6RHwqXVTnxhRUv1QFGme1wBmmBs0G3PPf8lp1HxSLUl3FK3V4kfgdjiurxjuNdubvAg';
} else {
	//ABQIAAAAeJTsA7ppykO6RHwqXVTnxhS237pdi7AAC2Fq3Ha5pN09SYJt4xRkBNsN6wrom0qaIxq0Haiiaurq6A
	$key = 'ABQIAAAAXi7AtY5jQ4YMZS3uNqaQVhSn51_jLMmjl25B6QxLNt9bnzD_KBRpTuhouSuZjyfhXbGmAM6vx3bLFw';
}

if ($group['map_lat'] == '' || $group['map_lon'] == '') {
	$group['map_lat'] = '50.4599800';
	$group['map_lon'] = '30.4810890';
	$ow = 0;
} else $ow = 1;
if ($group['map_zoom'] == 0) $group['map_zoom'] = '8';
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $key ?>"
        type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function () {
		$("#menu_apply").click(function () {
			var groupId = $(this).attr('rel');
			$.post(
				'/projects/check_join',
				{id: groupId},
				function (results) {
					if (results.check == 0) {
						$('#menu_leave').remove();
						$.post(
							'/projects/join',
							{id: groupId},
							function () {
								$('#menu_join').hide();
								$('#menu_leave').fadeIn(150);
							},
							'json'
						);
						$('#menu_apply').hide();
						$('#text_apply').fadeIn(150);
					} else {
						Application.showInfo('why_move');
					}
				},
				'json'
			);

		});
	});
	function ppoJoin() {
		$('#menu_leave').remove();
		$.post(
			'/projects/join',
			{id: $("#menu_apply").attr('rel'), text: $("#ppo_join_text").val()},
			function () {
				$('#menu_join').hide();
				$('#menu_leave').fadeIn(150);
			},
			'json'
		);
		$('#menu_apply').hide();
		$('#text_apply').fadeIn(150);
	}
	$('.tab_menu').click(function () {
		$('.tab_menu').removeClass('quiet');
		$(this).addClass('quiet');
		$(this).blur();
		$('.tbox').hide();
		$('#' + $(this).attr('rel') + '_box').show();
	});
</script>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$("#teritory").click(function () {
			if ($('#popup_box').length)$('#popup_box').show();
			else
				reformController.showTerritory(1, <?=request::get_int('id')?>);
			return false;
		});
		$("#glava").click(function () {
			Application.showUsers('glava');
			return false;
		});
		$("#secretar").click(function () {
			Application.showUsers('secretar');
			return false;
		});
		$('#category').change(function () {
			if ($(this).val() == 2) {
				$("#sfera-tr").show();
				$("#level-tr").hide();
				$("#hidden_1").removeAttr('checked');
			}
			else if ($(this).val() == 3) {
				$("#sfera-tr").hide();
				$("#level-tr").show();
				$("#hidden_1").removeAttr('checked');
			}
			else {
				$("#sfera-tr").hide();
				$("#level-tr").hide();
				$("#hidden_1").removeAttr('checked');
				if ($(this).val() == 4) {
					$("#hidden_1").attr('checked', 'checked');
					$("#privacy_2").attr('checked', 'checked');
					$("#privacy_1").removeAttr('checked');
				}
			}
		});
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		<?if(request::get('tab')){?>
		$('.form').hide();
		$('#<?=request::get('tab')?>_form').show();
		<?}?>
	});
	$(".info").click(function () {
		if (!$("#applicant_info_" + this.id).is(":visible")) {
			$("#applicant_info_" + this.id).slideDown(100);

		}
		else {
			$("#applicant_info_" + this.id).slideUp(100);
		}
	});
	$('#city').change(function () {
		if ($(this).val() >= 700 || $('#region').val() == 13)$('#nspu').hide();
		else $('#nspu').show();
	});
	$('.ppocategory').change(function () {
		if ($(this).val() == 3)$('#scity').hide();
		else $('#scity').show();
		if ($(this).val() == 1)$('.ptype').show();
		else $('.ptype').hide();
	});

	function initialize2() {
		if (GBrowserIsCompatible()) {
			map = new GMap2(document.getElementById("Map"));

			map.setCenter(new GLatLng('<?=$group['map_lat']?>', '<?=$group['map_lon']?>'), <?=$group['map_zoom']?>);

			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			<?
	$cord_array=explode('; ', $group['coords']);
	$cord_array = array_unique(array_diff($cord_array, array('')));
	if(count($cord_array)>0) {?>
			<?
			echo "var polyline = new GPolyline([";
			foreach($cord_array as $k=>$c) {
				$coordinates = explode(", ",$c);
				?>
			<?
				echo "new GLatLng(" . $coordinates[1] . "," . $coordinates[0] . "),\n";
			}
			$firstcoordinates = explode(", ",$cord_array[0]);
			echo "new GLatLng(" . $firstcoordinates[1] . "," . $firstcoordinates[0] . ")\n";
			echo "],\"#ff0000\", 5, 1);\n";
			echo "map.addOverlay(polyline);\n\n";
		}
		?>
			map.clearOverlays();
			GEvent.addListener(map, 'click', mapClick);
		}
	}
	function showDetail(id) {
		Popup.show();
		Popup.setHtml($(id).html());
		Popup.position()
	}
	function deleteItem(id, type) {
		$.ajax({
			type: 'post',
			url: '/projects/edit',
			data: {
				submit: 1,
				delete_id: id,
				id: '<?=$group['id']?>',
				type: 'delete_inventory'
			},
			success: function (data) {
				resp = eval("(" + data + ")");
				if (resp.success == 1) {
					$('#row' + id).remove();
					$('#delete_' + type + '_link').html(resp.count);
					Popup.setHtml($('#popup_' + type).html());
				}
				else alert(resp.error)
			}
		});
	}
</script>