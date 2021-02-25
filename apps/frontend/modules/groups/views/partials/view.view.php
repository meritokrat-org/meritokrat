<? load::model('user/user_auth'); ?>
<? load::model('groups/members'); ?>
<? load::model('groups/news'); ?>

<div class="profile">
	<div class="left" style="width: 230px; padding-top: 10px;">
		<div style="text-align:center; margin-bottom: 5px;">
			<?=group_helper::photo($group['id'], 'p', false, array())?>
		</div>

		<div style="width: 227px; margin: auto;">
			<?  /*$rate_offset = ceil( $group['rate'] ) + 2 ?>
			<div class="rate"><div style="background-position: <?=$rate_offset?>px 0px"><?=t('Рейтинг')?>: <?=number_format($group['rate'], 2)?></div></div>
*/  ?>
			<div class="ml5 profile_menu">
				<? if ( session::is_authenticated() ) { ?>
					<? if ( $group['privacy'] == groups_peer::PRIVACY_PUBLIC ) { ?>
						<a id="menu_join" href="javascript:;" style="<?=$is_member ? 'display:none;' : ''?>" onclick="groupsController.join(<?=$group['id']?>);"><?=tag_helper::image('icons/check.png', array('class' => 'vcenter mr5'))?><?=t('Вступить')?></a>
					<? } ?>
                                        <? if ( !$privacy_closed) { ?>
                                                <? if ( session::is_authenticated() ) { ?>
                                                        <?=user_helper::share_item('group', $group['id'], array('class' => 'left ml15'))?>
                                                <? } ?>
                                        <? } ?>
				<? } ?>
			</div>

			<? if ( !$privacy_closed ) { ?>
				<br /><div class="column_head_small mt15"><?=t('Координаторы')?></div>
				<div class="fs11 p10 mb5 box_content">
                                    <div style="height: 70px;">
                                        <div class="left">
                                            <?=user_helper::photo($group['user_id'], 's', array('class' => 'border1'))?>
                                        </div>
                                        <div class="left ml15">
                                            <? $username=user_helper::full_name($group['user_id']);
                                            if($group['user_id']==31) $username=str_replace("Оргкомітету","<br/>Оргкомітету",$username); ?>
                                            <?=$username ?>
                                            <br/>
                                            <?=t('Руководитель')?><br/>
                                            <? $udata = user_auth_peer::instance()->get_item($group['user_id']) ?>
                                            <span class="cgray"><?=user_auth_peer::get_status($udata['status'])?></span>
                                       </div>
                                    </div>
                              </div>
                      <? if ( $moderators ) { ?>
                             <? foreach ( $moderators as $id ) { ?>
				<div class="fs11 p10 mb5 box_content">
                                    <div style="height: 70px;">
                                        <div class="left">
                                            <?=user_helper::photo($id, 's', array('class' => 'border1'))?>
                                        </div>
                                        <div class="left ml15">
                                            <?=user_helper::full_name($id)?><br/>
                                            <?=t('Модератор')?><br/>
                                            <? $udata = user_auth_peer::instance()->get_item($id) ?>
                                            <span class="cgray"><?=user_auth_peer::get_status($udata['status'])?> </span>
                                       </div>
                                    </div>
                              </div>
                            <? } ?>
                      <? } ?>

					<br />
					<div class="column_head_small">
						<span class="left"><a href="/groups/news?id=<?=$group['id']?>" class="fs11 right"><?=t('Новости')?></a></span>
						<!--a href="/groups/news?id=<?=$group['id']?>" class="fs11 right"><?=t('Все')?></a-->
						<div class="clear"></div>
					</div>

				<? if ( $news ) { ?>
                                        <? foreach ( $news as $id ) { ?>
                                        <? $new =group_news_peer::instance()->get_item($id) ?>
					<div class="fs11 mb5 pb5 clear" style="background: #F7F7F7;">
						<div class="fs11 ml5 white bold"></div>
						<div class="fs11 p5">
							<div class="mb5 quiet"><?=date_helper::human($new['created_ts'], ', ')?></div>
                                                        <a href="groups/news?id=<?=$group['id']?>" class="fs12 bold"><?=stripslashes(nl2br(htmlspecialchars($new['title'])))?></a>
						</div>
					</div>
                                        <? } ?>
				<? } else { ?>

					<div class="fs11 pb5" style="background: #F7F7F7;">
						<div class="fs11 ml5 white bold"></div>
						<div class="fs11 p5">
                                                <?=t('Новостей еще нет')?>
                                                </div>
					</div>
                                <? } ?>
                                <? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
                                        <div class="box_content mt5 fs11"><a href="/groups/add_news?id=<?=$group['id']?>" class="ml10 fs11"><?=t('Добавить новость')?> &rarr;</a></div>
                                <? } ?>					<br />
					<div class="column_head_small">
                                            <a href="/groups/members?id=<?=$group['id']?>" class="fs11 left"><?=t('Участники')?>  &nbsp; <?=count($councilMembers)?></a>
						<!--a href="/groups/members?id=<?=$group['id']?>" class="fs11 right"><?=t('Все')?></a-->
						<div class="clear"></div>
					</div>
                                        <?  if ( !$users ) { ?>                                            
                                        <div class="fs11 p10 box_content"><?=t('Участников еще нет')?></div>
                                        <? } else {?>
                                        <? foreach ( $users as $id ) { ?>
                                            <div class="fs11 p10 mb5 box_content">
                                                <div style="height: 70px;">
                                                        <div class="left">
                                                            <?=user_helper::photo($id, 's', array('class' => 'border1'))?>
                                                        </div>
                                                        <div class="left ml15">
                                                            <?=user_helper::full_name($id)?><br/>
                                                            <? $udata = user_auth_peer::instance()->get_item($id) ?>
                                                            <?=user_auth_peer::get_status($udata['status'])?>
                                                       </div>
                                                    </div>
                                              </div>
                                                <? }  ?>
                                              <div class="box_content p5 mb10 fs11"><a href="/groups/members?id=<?=$group['id']?>"><?=t('Все участники')?> &rarr;</a></div>
                                        <? } ?>
                            <? } ?>
                            <div class="profile_menu">
                                <? if ( session::is_authenticated() ) { ?>
                                        <? if ( $group['user_id'] != session::get_user_id() ) { ?>
                                                <a id="menu_leave" href="javascript:;" style="<?=!$is_member ? 'display:none;' : ''?>" onclick="groupsController.leave(<?=$group['id']?>);"><?=tag_helper::image('icons/delete.png', array('class' => 'vcenter mr5'))?><?=t('Покинуть')?></a>
                                        <? } ?>
                                <? } ?>
                            </div>
		</div>
	</div>

	<div class="left" style="width: 530px; padding-top: 10px;">
		<h1 class="mb5" style="height: 60px; overflow: hidden; color: rgb(102, 0, 0);">
			<?=stripslashes(htmlspecialchars($group['title']))?>
		</h1><div style="margin-top: -28px;" class="left"><span style="font-size: 12px;"><?=$group['active']==1 ? t('Одобрено') : t('Не одобрено')?></span></div>
		<div class="left fs11 quiet bold">
			<?=t('Сообщество')?>
			<? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
				<a href="/groups/edit?id=<?=$group['id']?>" class="ml10 fs11"><?=t('Редактировать')?></a>
			<? } ?>

			<? if ( session::has_credential('admin') ) { ?>
				<a href="/admin/groups?key=<?= $group['id'] ?>" class="ml10 fs11"><?=t('Администрирование')?></a>
                                <?=$group['active']!=1 ? '<a href="/groups/approve_group?group_id='.$group['id'].'" class="ml10 fs11">'.t('Одобрить').'</a>' : ''?>
                                 <a onclick="return confirm('Видалити цю спільноту?');" href="/groups/delete_group?group_id=<?=$group['id']?>" class="ml10 fs11"><?=t('Удалить')?></a>
			<? } ?>
		</div>
		<div class="clear"></div>
		<? if ( !$privacy_closed ) { ?>
			<table class="fs12 mt10">
				<tr><td width="35%;" class="bold aright"><?=t('Сфера')?></td><td><?=groups_peer::get_type($group['type'])?></td></tr>
				<!--tr><td width="35%;" class="bold aright"><?=t('Территория')?></td><td><?=groups_peer::get_teritory($group['teritory'])?></td></tr-->
				<? /* if ( $group['url'] ) { ?>
					<tr><td class="bold aright"><?=t('Web сайт')?></td><td><a rel="nofollow" target="_blank" href="http://<?=$group['url']?>"><?=htmlspecialchars($group['url'])?></a></td></tr>
				<? } */?>
                                <tr><td width="35%;" class="bold aright"><?=t('Краткое описание')?></td><td><?=stripslashes(htmlspecialchars($group['aims']))?></td></tr>				<!--tr><td width="35%;" class="bold aright"><?=t('Территория')?></td><td><?=groups_peer::get_teritory($group['teritory'])?></td></tr-->
				<? if (count($councilMembers)) { ?>
					<tr><td class="bold aright"><?=t('Количество участников')?></td><td><a href="/groups/members?id=<?=$group['id']?>"><?=count($councilMembers)?></a></td></tr>
				<? }?>

			</table>
			<br />
			<div class="tab_pane">
                            <a rel="description" href="javascript:;"><?=t('Описание')?></a>
                            <? if ( count($talks)>0 ) { ?><a rel="talk" href="javascript:;" class="selected"><?=t('Мысли')?></a><? } ?>
                            <? if ( count($proposals)>0 or session::has_credential('admin') ) { ?><a rel="proposal" href="javascript:;"<?=count($proposals) ? '' : ' style="font-weight:normal"'?>><?=t('Предложения')?></a><? } ?>
                            <? if ( count($positions)>0 or session::has_credential('admin')) { ?><a rel="position" href="javascript:;"<?=count($positions) ? '' : ' style="font-weight:normal"'?>><?=t('Позиция МПУ')?></a><? } ?>
                            <? if ( count($files)>0 or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?><a rel="files" href="javascript:;"<?=count($files) ? '' : ' style="font-weight:normal"'?>><?=t('Библиотека')?></a><? } ?>
                            <?/* if ( count($links)>0 or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?><a rel="links" href="javascript:;"<?=count($links) ? '' : ' style="font-weight:normal"'?>><?=t('Ссылки')?></a><? } */?>
				<!--a rel="aims" href="javascript:;"><?=t('Цели')?></a-->
                            <? if ( $photos  or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?><a rel="photo" href="javascript:;"<?=count($photos) ? '' : ' style="font-weight:normal"'?>><?=t('Фото')?></a><? } ?>
				<div class="clear"></div>
			</div>

			<div id="pane_description" class="content_pane hidden">
				<? if ( $group['description'] ) { ?>
					<div class="m5 fs12"><?=nl2br(htmlspecialchars($group['description']))?></div>
				<? } else { ?>
					<div class="m5 acenter fs12"><?=t('Описания еще нет')?></div>
				<? } ?>
			</div>

			<div id="pane_aims" class="content_pane hidden">
				<? if ( $group['aims'] ) { ?>
					<div class="m5 fs12"><?=nl2br(htmlspecialchars($group['aims']))?></div>
				<? } else { ?>
					<div class="m5 acenter fs12"><?=t('Цели еще не определены')?></div>
				<? } ?>
			</div>

			<div id="pane_photo" class="content_pane hidden">
                            <div class="box_content p5 mb10 fs11"><a href="/groups/photo?id=<?=$group['id']?>"><?=t('Фотоальбомы сообщества')?> &rarr;</a></div>
				<? if ( $photos ) { ?>
					<div class="m5 fs12">
						<? foreach ( $photos as $photo_id ) { ?>
							<a class="left acenter mb10 ml10" href="/groups/photo_view?id=<?=$photo_id?>"><?=group_helper::media_photo($photo_id, 't')?></a>
						<? } ?>
					</div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?=t('Фотографий еще нет')?>
						<? if ( $is_member or session::has_credential('admin') ) { ?>
							<br />
							<a href="/groups/photo_add?id=<?=$group['id']?>"><?=t('Добавить фото')?></a>
						<? } ?>
					</div>
				<? } ?>
			</div>
<? /*
			<div id="pane_links" class="content_pane hidden">
                            <div class="box_content p5 mb10 fs11"><a href="/groups/link?id=<?=$group['id']?>"><?=t('Ссылки сообщества')?> &rarr;</a></div>
				<? if ( $links ) { ?>
					<div class="m5 fs12">
						<? foreach ( $links as $link_id ) {
                                                        $link=groups_links_peer::instance()->get_item($link_id);?>
                                                        <div class="left mr15 bold"><a href="<?=$link['url']?>"><?=$link['title']?></a><!--br/><span class="fs10"><?=$link['title']?></span--></div>
                                                        <div class="left mr15 fs11" style="width:200px;"><?=user_helper::full_name($link['user_id'], true)?></div>
                                                        <div class="clear mb5"></div>
						<? } ?>
					</div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?=t('')?>
						<? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
							<br />
							<a href="/groups/link?id=<?=$group['id']?>&add=1"><?=t('Добавить')?></a>
						<? } ?>
					</div>
				<? } ?>
                            <div class="box_content p5 mb10 fs11"><a href="/groups/link?id=<?=$group['id']?>"><?=t('Все ссылки')?> &rarr;</a></div>
			</div>
                        */ ?>
			<div id="pane_files" class="content_pane hidden">
                            <div class="box_content p5 mb10 fs11"><a href="/groups/file?id=<?=$group['id']?>"><?=t('Материалы')?> &rarr;</a></div>
				<? if ( $files ) { ?>
					<div class="box_content mt5 m5 fs12 mb5 left">
						<? foreach ( $files as $file_id ) {
                                                       $file=groups_files_peer::instance()->get_item($file_id); ?>
                                                        <div class="left mr15" style="width:220px;margin-right: 20px"><a href="/download/groups/<?=$group['id']?>/<?=$file['id']."-".$file['salt'].".".$file['ext']?>"><?=$file['title']?></a><!--br/><span class="fs10"><?=$file['title']?></span--> [<?=$file['size']?>]<br/>
                                                        <?=user_helper::full_name($file['user_id'], true)?></div>
                                       <? } ?>
					</div>
					<div class="clear mb5"></div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?=t('')?>
						<? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
							<a href="/groups/file?id=<?=$group['id']?>&add=1"><?=t('Добавить материал')?></a><br/>
						<? } ?>
					</div>
				<? } ?>
                                <div class="box_content p5 mb10 fs11"><a href="/groups/file?id=<?=$group['id']?>"><?=t('Все материалы')?> &rarr;</a></div>
			</div>

			<div id="pane_talk" class="content_pane">
			<div class="box_content p5 mb10 fs11"><a href="/groups/talk?id=<?=$group['id']?>&add=1"><?=t('Добавить мысль')?> &rarr;</a></div>
			<? if ( !$talks ) { ?>
				<div class="m5 acenter fs12"><?=t('Мыслей еще нет')?></div>
			<? } else {?>
				<? foreach ( $talks as $id ) { ?>
					<? $topic = groups_topics_peer::instance()->get_item($id) ?>
					<div class="mb10 box_content p10 mr10" id="comment<?=$id?>">
						<div class="mb5 bold fs12">
							<a href="/groups/talk_topic?id=<?=$id?>"><?=stripslashes(htmlspecialchars($topic['topic']))?></a>
						</div>
						<div class="fs11 pb5">
							<div class="left quiet">
								<?=t('Всего сообщений')?>:
								<b><?=$topic['messages_count']?></b>,
								<?=t('Последнее')?>:
								<a href="/groups/talk_topic?id=<?=$id?>&last=1"><?=date_helper::human($topic['updated_ts'], ', ')?> &rarr;</a>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				<? } ?>
			<? } ?>
                                <div class="box_content p5 mb10 fs11"><a href="/groups/talk?id=<?=$group['id']?>"><?=t('Все мысли')?> &rarr;</a></div>
			</div>

			<div id="pane_position" class="content_pane hidden">
			<? if (session::has_credential('admin')) { ?> <div class="box_content p5 mb10 fs11"><a href="/groups/position?id=<?=$group['id']?>&add=1"><?=t('Добавить')?> &rarr;</a></div> <? } ?>
			<? if ( !$positions ) { ?>
				<div class="m5 acenter fs12"><?=t('')?></div>
			<? } else {?>
				<? foreach ( $positions as $id ) { ?>
					<? $topic = groups_positions_peer::instance()->get_item($id) ?>
					<div class="mb10 box_content p10 mr10" id="comment<?=$id?>">
						<div class="mb5 bold fs12">
							<a href="/groups/position_topic?id=<?=$id?>"><?=stripslashes(htmlspecialchars($topic['topic']))?></a>
						</div>
						<div class="fs11 pb5">
							<div class="left quiet">
								<?=t('Всего сообщений')?>:
								<b><?=$topic['messages_count']?></b>,
								<?=t('Последнее')?>:
								<a href="/groups/position_topic?id=<?=$id?>&last=1"><?=date_helper::human($topic['updated_ts'], ', ')?> &rarr;</a>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				<? } ?>
			<? } ?>
                                <div class="box_content p5 mb10 fs11"><a href="/groups/position?id=<?=$group['id']?>"><?=t('Все')?> &rarr;</a></div>
			</div>

			<div id="pane_proposal" class="content_pane hidden">
			<div class="box_content p5 mb10 fs11"><a href="/groups/proposal?id=<?=$group['id']?>&add=1"><?=t('Добавить предложение')?> &rarr;</a></div>
			<? if ( !$proposals ) { ?>
				<div class="m5 acenter fs12"><?=t('')?></div>
			<? } else {?>
				<? foreach ( $proposals as $id ) { ?>
					<? $topic = groups_proposal_peer::instance()->get_item($id) ?>
					<div class="mb10 box_content p10 mr10" id="comment<?=$id?>">
						<div class="mb5 bold fs12">
							<a href="/groups/proposal_topic?id=<?=$id?>"><?=stripslashes(htmlspecialchars($topic['topic']))?></a>
						</div>
						<div class="fs11 pb5">
							<div class="left quiet">
								<?=t('Всего сообщений')?>:
								<b><?=$topic['messages_count']?></b>,
								<?=t('Последнее')?>:
								<a href="/groups/proposal_topic?id=<?=$id?>&last=1"><?=date_helper::human($topic['updated_ts'], ', ')?> &rarr;</a>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				<? } ?>
			<? } ?>
                                <div class="box_content p5 mb10 fs11"><a href="/groups/proposal?id=<?=$group['id']?>"><?=t('Все предложения')?> &rarr;</a></div>
			</div>
		<? } elseif(session::is_authenticated()) { ?>
			<div class="screen_message acenter bold">
				<br/><br />
                                        <?=t('Это закрытое сообщество, для доступа к содержимому Вам необходимо вступить в нее')?>
					<? if ( !groups_applicants_peer::instance()->is_applicant($group['id'], session::get_user_id()) ) { ?>
						<input id="menu_apply" type="button" class="button" value="<?=t('Вступить')?>" rel="<?=$group['id']?>" /> <?// <!--onclick="groupsController.apply(<?=$group['id'] ?>
						<div id="text_apply" class="hidden quiet normal"><?=t('Заявка на вступление отправлена')?></div>
					<? } else { ?>
						<div class="quiet normal"><?=t('Заявка на вступление отправлена')?></div>
					<? } ?>
			</div>
		<? }elseif(!session::is_authenticated()) { ?>
                        <div class="screen_message acenter bold">
				<br/><br />
                                        <?=t('Это закрытое сообщество. Для просмотра Вам необходимо зарегистрироваться в сети и отправить заявку на вступление в это сообщество. ')?>
			</div>
                <? } ?>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
                $("#menu_apply").click(function() {
                var groupId = $(this).attr('rel');
                $('#menu_leave').remove();
		$.post(
			'/groups/join',
			{id: groupId},
			function () { $('#menu_join').hide(); $('#menu_leave').fadeIn(150); },
			'json'
		);
		$('#menu_apply').hide();
		$('#text_apply').fadeIn(150);
});
});

</script>