<? load::model('ppo/members'); ?>
<style type="text/css">
	.content_pane {
		height: 160px;
		overflow: auto;
		margin: 10px;
	}

	td {
		padding: 2px 10px 2px 5px;
	}

	a.confirmation, a.confresult {
		height: 13px;
		padding: 5px;
	}
</style>

<? include 'partials/sub_menu.php'; ?>
<div class="profile">
	<div class="left" style="width: 230px; padding-top: 10px;">
		<div style="text-align:center; margin-bottom: 5px;">
			<?
			load::view_helper('image');
			if (!$event['photo'] && $event['type'] == 1) {
				load::view_helper('group');
				load::model('groups/groups');
			}
			?>
			<?
			if ($event['type'] == 1 && !$event['photo'])
				echo group_helper::photo($event['content_id'], 'p', array('class' => 'border1', 'id' => 'photo'));
			else
				echo image_helper::photo($event['photo'], 'p', 'events', array('class' => 'border1', 'id' => 'photo'));
			?>

		</div>
		<? if (session::has_credential('admin') || ($event['type'] == 1 && groups_peer::instance()->is_moderator($event['content_id'], session::get_user_id())) || ($event['type'] == 4 && ppo_members_peer::instance()->is_ppoleader(session::get_user_id(), $event['content_id'])) || ($coordinator)) { ?>
			<? $is_enough_credentials = 1; ?>
			<div class="share left ml15" style="margin-left:40px;">
				<a href="javascript:;" onclick="Application.inviteItem('event', 1, <?= $event['id'] ?>)"
				   class="share mb5 ml15"><span class="fs18"><?= t('Пригласить') ?></span></a>
			</div>
			<div class="clear"></div>
			<div class="share left ml15" style="margin-left:40px;">
				<? //if(!db_key::i()->exists('group_mailer_id:'.$event['id'])) {?>
				<? if (in_array($event['type'], array(1, 4))) { ?>
					<a href="javascript:;"
					   id="mailer"
					   onclick="Application.sendMessages({'event_id':'<?= $event['id']; ?>'})"
					   class="share mb5 ml15">
						<span class="fs18"><?= t('Рассылка') ?></span>
					</a>
				<? } ?>
			</div>
		<? } ?>
	</div>
	<div style="width: 530px; padding-top: 10px;" class="left">
		<h1 style="height: 60px; overflow: hidden; color: rgb(102, 0, 0);line-height:1;"
		    class="mb5 left fs28"><?= stripslashes(htmlspecialchars($event['name'])) ?></h1>

		<div class="clear"></div>
		<? if (session::is_authenticated()) { ?>
			<? load::model('bookmarks/bookmarks'); ?>
			<? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 7, $event['id']); ?>
			<div class="fs11 mb10 right">
				<a href="#add_bookmark" class="bookmark mb10 ml5 right b7" <?= ($bkm) ? 'style="display:none;"' : '' ?>
				   onclick="Application.bookmarkItem('7','<?= $event['id'] ?>');return false;"><b></b><span><?= t('В закладки') ?></span></a>
				<a href="#del_bookmark" class="unbkmrk mb10 ml5 right b7" <?= ($bkm) ? '' : 'style="display:none;"' ?>
				   onclick="Application.unbookmarkItem('7','<?= $event['id'] ?>');return false;"><b></b><span><?= t('Удалить из закладок') ?></span></a>
			</div>
		<? } ?>
		<? if (session::has_credential('admin') || session::has_credential('designer') ||
			$event['user_id'] == session::get_user_id()
		) { ?>
			<div class="left fs11 quiet bold">
				<?= t('Событие') ?><a class="ml10 fs11" href="/events/edit?id=<?= $event['id'] ?>">Редагувати</a>
				<? if (session::has_credential('admin')) { ?>
					<a class="ml10 fs11" href="/events/delete_event?event_id=<?= $event['id'] ?>"
					   onclick="return confirm('Видалити цей захiд?');">Видалити</a>
				<? } ?>
			</div>
		<? } ?>
		<? load::view_helper('date') ?>
		<table class="fs12 mt10" style="width:400px;">
			<tbody>
			<tr>
				<td width="26%;" class="bold"><?= t('Вид') ?>:</td>
				<td style="color:black"><? $get_types = events_peer::get_types();
					echo $get_types[$event['section']] ?></td>
			</tr>
			<? if ($event['section'] == 9) { ?>
				<tr>
					<td width="26%;" class="bold"><?= t('Формат') ?>:</td>
					<td style="color:black">
						<? $f = events_peer::get_formats(); ?>
						<? if ($event["format"]["campaign"]) { ?>
							<div><?= $f["campaign"] ?></div>
						<? } ?>
						<? if ($event["format"]["propaganda"]) { ?>
							<div><?= $f["propaganda"] ?></div>
						<? } ?>
						<? if ($event["format"]["other"]) { ?>
							<div><?= $f["other"] ?>: <?= $event["format"]["other_text"] ?></div>
						<? } ?>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td width="26%;" class="bold"><?= t('Уровень') ?>:</td>
				<td style="color:black"><? $get_cats = events_peer::get_levels();
					echo $get_cats[$event['level']] ?></td>
			</tr>
			<tr>
				<td class="bold"><?= t('Когда') ?>:</td>
				<td style="color:black"><?= date_helper::get_date_range($event['start'], $event['end']) ?></td>
			</tr>
			<tr>
				<td class="bold"><?= t('Где') ?>:</td>
				<td style="color:black">
					<? $region = geo_peer::instance()->get_region($event['region_id']) ?><?= $region['name_' . translate::get_lang()] ?>
					,
					<? $city = geo_peer::instance()->get_city($event['city_id']) ?><?= $city['name_' . translate::get_lang()] ?>
					,
					<?= stripslashes(htmlspecialchars($event['adress'])) ?>
					<? $map = $region['name_' . translate::get_lang()] . ', ' . $city['name_' . translate::get_lang()] . ', ' . stripslashes(htmlspecialchars($event['adress'])) ?>
					<? $map = explode(' ', $map) ?>
					<? $map = implode('+', $map) ?>
					&nbsp;(<a class="map" target="_blank"
					          href="http://maps.google.com/maps?q=<?= $map ?>&hl=ru&ie=UTF8&ct=clnk&split=0"><?= t('Посмотреть на карте') ?></a>)
				</td>
			</tr>
			<tr>
				<td class="bold"><?= t('Организатор') ?>:</td>
				<td style="color:black"><?
					switch ($event['type']) {
						case 1:
							load::model('groups/groups');
							if ($event['content_id'] > 0) $group = groups_peer::instance()->get_item($event['content_id']);
							?>
							<a href="/group<?= $group['id'] ?>"
							   style="color:black"><?= $group['title'] ?></a> (<?= user_helper::full_name($event['user_id'], true, array('style' => 'color:black'), false); ?>)
							<?
							break;
						case 4:
							load::model('ppo/ppo');
							if ($event['content_id'] > 0) $ppo = ppo_peer::instance()->get_item($event['content_id']);
							?>
							<a href="/ppo<?= $ppo['id'] ?>/<?= $ppo['number'] ?>"
							   style="color:black"><?= $ppo['title'] ?></a> (<?= user_helper::full_name($event['user_id'], true, array('style' => 'color:black'), false); ?>)
							<?
							break;
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
					?></td>
			</tr>
			<tr>
				<td class="bold"><?= t('Стоимость') ?>:</td>
				<td style="color:black"><? $price = events_peer::get_price_types();
					echo $price[$event['price']] ?></td>
			</tr>
			<? if ($event['site']) { ?>
				<tr>
				<td class="bold"><?= t('Веб-сайт') ?>:</td>
				<td style="color:black"><a target="_blank"
				                           href="<?= (strpos($event['site'], "http://") === false) ? "http://" . $event['site'] : $event['site'] ?>"><?= $event['site'] ?></a>
				</td></tr><? } ?>
			<? if ($event['notes']) { ?>
				<tr>
				<td class="bold"><?= t('Примечания') ?>:</td>
				<td style="color:black"><?= stripslashes(htmlspecialchars($event['notes'])) ?></td></tr><? } ?>
			<tr>
				<td colspan="2" class="p0">
					<hr class="p0 m0"/>
				</td>
			</tr>
			<tr>
				<td class="bold"><?= t('Подтверждение участия') ?>:</td>
				<td style="color:black">
					<div><?= ($event['confirm']) ? t('Обязательное') : t('Необязательное') ?></div>
					<? if (!($event['start'] < time())) { ?>
						<? if (session::is_authenticated()) {
							// //if(!$is_member){ ?>
							<div>
								<a style="color:black"
								   class="uline promt<?= $event['id'] == 45 ? 'noleads' : '' ?> <?= ($event['status'] == 1) ? 'bold' : '' ?>"
								   rel="1" href="javascript:{};"><?= t('Да, пойду') ?></a>&nbsp;&nbsp;
								<a style="color:black"
								   class="uline nopromt <?= ($event['status'] == 2) ? 'bold' : '' ?>"
								   href="javascript:{};"><?= t('Нет, не пойду') ?></a>&nbsp;&nbsp;
								<a style="color:black" class="uline promt <?= ($event['status'] == 3) ? 'bold' : '' ?>"
								   rel="3" href="javascript:{};"><?= t('Возможно, пойду') ?></a>
							</div>
							<? //}
						} ?>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="p0">
					<hr class="p0 m0"/>
				</td>
			</tr>
			<tr>
				<td class="bold"><?= t('Описание мероприятия') ?>:</td>
				<td style="color:black"><?= stripslashes($event['description']) ?></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="clear"></div>
</div>

<? if (session::is_authenticated()) { ?>

	<div id="pans">
		<div class="tab_pane" style="font-size:90%">
			<a href="javascript:;" class="selected" rel="users1">
				<?= t('Пойдут') ?> <?= $event['users1count'] + $event['users1sum'] ?><?= ($event['users1sum']) ? '(' . $event['users1count'] . '+' . $event['users1sum'] . ')' : '' ?>
			</a>
			<a href="javascript:;"
			   rel="users2"><?= t('Не пойдут') ?> <?= $event['users2count'] + $event['users2sum'] ?></a>
			<a href="javascript:;"
			   rel="users3"><?= t('Возможно пойдут') ?> <?= $event['users3count'] + $event['users3sum'] ?></a>
			<? if (count($whos_invited) > 0) { ?>
				<a href="javascript:;" rel="users4"><?= t('Вы уже пригласили') ?> (<?= count($whos_invited) ?>)</a>
			<? } ?>
			<? if (session::has_credential('admin')) { ?>
				<a href="/events?action=view&id=<?= $event['id'] ?>&print=1" id="print_users1"
				   class="printlink">*<?= t('Печатать') ?></a>
				<a href="/events?action=view&id=<?= $event['id'] ?>&print=2" id="print_users2" class="printlink"
				   style="display:none">*<?= t('Печатать') ?></a>
				<a href="/events?action=view&id=<?= $event['id'] ?>&print=3" id="print_users3" class="printlink"
				   style="display:none">*<?= t('Печатать') ?></a>
				<a href="/events?action=view&id=<?= $event['id'] ?>&print=1&confirm=2" id="print_users5"
				   class="printlink" style="display:none">*<?= t('Печатать') ?></a>
				<a href="/events?action=view&id=<?= $event['id'] ?>&print=1&confirm=1" id="print_users6"
				   class="printlink" style="display:none">*<?= t('Печатать') ?></a>
			<? } ?>
			<? if ($event['start'] < time() && session::has_credential('admin')) { ?>
				<a href="javascript:;" style="float:right" class="mr10" rel="users5">
					*<?= t('Не были') ?> (<?= count(events_members_peer::instance()->get_members($event['id'], 0, 2)) ?>
					)
				</a>
				<a href="javascript:;" style="float:right" rel="users6">
					*<?= t('Были') ?> (<?= count(events_members_peer::instance()->get_members($event['id'], 0, 1)) ?>)
				</a>
			<? } ?>
			<div class="clear"></div>
		</div>
		<div class="content_pane" id="pane_users1">
			<? $i = 0;
			foreach (events_members_peer::instance()->get_members($event['id'], 1) as $user) { ?>
				<? include 'partials/member_short.php'; ?>
				<? ($i == 1) ? $i = 0 : $i++;
			} ?>
			<div class="clear"></div>
		</div>
		<div class="content_pane hidden" id="pane_users2">
			<? $i = 0;
			foreach (events_members_peer::instance()->get_members($event['id'], 2) as $user) { ?>
				<? include 'partials/member_short.php'; ?>
				<? ($i == 1) ? $i = 0 : $i++;
			} ?>
		</div>
		<div class="content_pane hidden" id="pane_users3">
			<? $i = 0;
			foreach (events_members_peer::instance()->get_members($event['id'], 3) as $user) { ?>
				<? include 'partials/member_short.php'; ?>
				<? ($i == 1) ? $i = 0 : $i++;
			} ?>
		</div>

		<? if (count($whos_invited) > 0) { ?>
			<div class="content_pane hidden" id="pane_users4">
				<? $num = count($whos_invited) ?>
				<? $i = 0;
				foreach ($whos_invited as $v) { ?>
					<? include 'partials/invited.php'; ?>
					<? ($i == 1) ? $i = 0 : $i++;
				} ?>
			</div>
		<? } ?>

		<? if ($event['start'] < time()) { ?>
			<div class="content_pane hidden" id="pane_users6" style="min-height:10px">
				<? $i = 0;
				foreach (events_members_peer::instance()->get_members($event['id'], 0, 1) as $user) { ?>
					<? include 'partials/member_short.php'; ?>
					<? ($i == 1) ? $i = 0 : $i++;
				} ?>
			</div>
			<div class="content_pane hidden" id="pane_users5" style="min-height:10px">
				<? $i = 0;
				foreach (events_members_peer::instance()->get_members($event['id'], 0, 2) as $user) { ?>
					<? include 'partials/member_short.php'; ?>
					<? ($i == 1) ? $i = 0 : $i++;
				} ?>
			</div>
		<? } ?>

	</div>
	<div class="column_head">
		<div class="left icocomments mr5" style="margin-top:3px"></div>
		<div class="left" style="margin-top:2px"><?= t('Комментарии') ?></div>
	</div>
	<div class="mt10 mb10" id="comments">
		<? if (!$comments) { ?>
			<div id="no_comments" class="acenter fs11 quiet"><?= t('Нет комментариев') ?></div>
		<? } else { ?>
			<? foreach ($comments as $id) {
				include 'partials/comment.php';
			} ?>
		<? } ?>
	</div>
	<? if (!$is_blacklisted) { ?>
		<form class="form_bg" id="comment_form" action="/events/comment">
			<h3 class="column_head_small mb5"><?= t('Добавить комментарий') ?></h3>

			<div class="ml10 mr10 mb10">
				<input type="hidden" name="event_id" value="<?= $event['id'] ?>"/>
				<textarea rel="<?= t('Напишите текст') ?>" style="width: 99%; height: 75px;" name="text"></textarea>
				<input type="submit" name="submit" class="mt5 mb5 button" value=" <?= t('Отправить') ?> "/>
				<?= tag_helper::wait_panel() ?>
			</div>
		</form>

		<form id="comment_reply_form" class="hidden" action="/events/comment">
			<input type="hidden" name="event_id" value="<?= $event['id'] ?>"/>
			<input type="hidden" name="parent_id"/>
			<textarea rel="<?= t('Напишите текст') ?>" style="width: 99%; height: 50px;" name="text"></textarea>
			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?= t('Отправить') ?> "/>
			<?= tag_helper::wait_panel() ?>
			<input type="button" class="button_gray" value="<?= t('Отмена') ?>"
			       onclick="$('#comment_reply_form').hide();">
		</form>

		<form id="comment_update_form" class="hidden" action="/events/comment">
			<input type="hidden" name="upd_id" id="upd_id"/>
			<input type="hidden" name="why" id="why"/>
			<input type="hidden" name="event_id" value="<?= $event['id'] ?>"/>
			<textarea rel="<?= t('Напишите текст') ?>" style="width: 99%; height: 100px;" name="text"></textarea>
			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?= t('Сохранить') ?> "/>
			<?= tag_helper::wait_panel() ?>
			<input type="button" class="button_gray" value="<?= t('Отмена') ?>" onclick="Application.cancelComUpd()">
		</form>
	<? } ?>
<? } ?>
<script type="text/javascript">
	$('.promt').click(function () {
		var leads = prompt("<?=t('Со мной идут (укажите количество цифрой)')?>:", "0");
		var bold = this;
		if (!leads) return false;
		$.post('/events/edit?id=<?=request::get_int('id')?>', {"leads": leads, "status": this.rel},
			function (result) {
				$('.promt').removeClass('bold');
				$('.nopromt').removeClass('bold');
				$(bold).addClass('bold');
				$.post('/event<?=request::get_int('id')?>', {"type": "ajax"},
					function (result) {
						$('#pans').html(result);
					});
			});

		return false;
	});
	$('.promtnoleads').click(function () {
		var bold = this;
		$.post('/events/edit?id=<?=request::get_int('id')?>', {"status": this.rel},
			function (result) {
				$('.promt').removeClass('bold');
				$('.nopromt').removeClass('bold');
				$(bold).addClass('bold');
				$.post('/event<?=request::get_int('id')?>', {"type": "ajax"},
					function (result) {
						$('#pans').html(result);
					});
			});

		return false;
	});

	$('.confirmation').click(function () {
		var confirm = 0;
		if ($(this).hasClass('button_gray')) {
			confirm = 2;
		}
		$.post('/events/confirm', {
			"event_id": '<?=request::get_int('id')?>',
			'user_id': $(this).attr('rel'),
			'confirm': confirm
		});
		$(this).parent().find('.confresult').html($(this).html()).show();
		$(this).parent().find('.confirmation').hide();
	});

	$('.confresult').click(function () {
		$(this).parent().find('.confirmation').show();
		$(this).hide();
	});

	$('.nopromt').click(function () {
		var bold = this;
		$.post('/events/edit?id=<?=request::get_int('id')?>', {"status": 2},
			function (result) {
				$('.promt').removeClass('bold');
				$('.nopromt').removeClass('bold');
				$(bold).addClass('bold');
				$.post('/event<?=request::get_int('id')?>', {"type": "ajax"},
					function (result) {
						$('#pans').html(result);
					});
				tabclick();
			});
		return false;
	});
	tabclick();
	function tabclick() {
		$('.tab_pane > a').click(function () {
			if ($(this).attr('rel')) {
				$('.tab_pane > a').removeClass('selected');
				$(this).addClass('selected');
				$('.content_pane').hide();
				$('#pane_' + $(this).attr('rel')).show();
				$(this).blur();
				$('.printlink').hide();
				$('#print_' + $(this).attr('rel')).show();
			}
		});
	}
</script>
