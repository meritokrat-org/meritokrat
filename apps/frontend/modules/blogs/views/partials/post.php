<? if($post_data){ ?>
	<div
		class="box_content"
		style="
			<? if( ! $without_photo){ ?>
				padding-top: 10px;
				padding-bottom: 3px;
			<? } else { ?>
				padding-top: 3px;
				padding-bottom: 3px;
			<? } ?>
			padding-left: 10px;
			padding-right: 10px;
			margin-bottom: 5px;
		">

		<table style="padding: 0px; margin: 0px;">
			<tr>
				<? if(1 != 1){ ?>
				<?// if(! $without_photo){ ?>
					<td
						style="
							width: 60px;
							padding: 0px
						">
						<? if($post_data['mission_type'] == 3 || $post_data['mission_type'] == 2){ ?>
							<? if( ! $post_data['photo']){ ?>
								<?=user_helper::photo(31, 's', array('class' => 'border1', 'id' => 'photo'))?>
							<? } else { ?>
								<?=image_helper::photo($post_data['photo'], 's', 'blogs', array('class' => 'border1', 'id' => 'photo'))?>
							<? } ?>
						<? } else { ?>
							<?=user_helper::photo($post_data['user_id'], 's', array('class' => 'border1', 'without-stats' => true))?>
						<? } ?>
						<!--<div class="mb5 acenter">
								<span class="green"><?//=$post_data['for']?></span>
								<span class="red ml5"><?//=$post_data['against']?></span>
						</div>
						<div class="quiet fs11 mb10 acenter" style="line-height: 1.2;">
							<?//=date_helper::human($post_data['created_ts'])?>
						</div>-->
					</td>
				<? } ?>

				<td
					style="
						padding: 0px;
						padding-left: 5px
					">
					<? if(request::get('bookmark')){ ?>
						<? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id() , 1, $post_data['id']); ?>
						<a
							class="bookmark mb10 ml5 right"
							style="<?= ($bkm) ? 'display:none;' : 'display:block;' ?>"
							href="#add_bookmark"
							onclick="
								Application.bookmarkThisItem(this, '1', '<?=$post_data['id']?>');
								return false;"
						>
							<span><?=t('В закладки')?></span>
						</a>
						<a
							class="unbkmrk mb10 ml5 right"
							style="<?= ($bkm) ? 'display:block;' : 'display:none;' ?>"
							href="#del_bookmark"
							onclick="
								Application.unbookmarkThisItem(this, '1', '<?=$post_data['id']?>');
								return false;
							">
							<span><?=t('Удалить из закладок')?></span>
						</a>
					<? } ?>

					<div style="margin-top: -3px">
						<a
							href="/blogpost<?=$post_data['id']?>"
							style="line-height: normal;"
							class="fs18">
							<?=stripslashes(htmlspecialchars($post_data['title']))?>
						</a>
						<? if($post_data['group_id'] && $post_data['type']==5){ ?>
							<? $group = groups_peer::instance()->get_item($post_data['group_id']); ?>
							<br />
							<a
								href="/group<?=$post_data['group_id']?>"
								class="fs10"
							>
								<?=stripslashes(htmlspecialchars($group['title']))?>
							</a>
						<? } ?>
					</div>

					<? if( ! $without_photo){ ?>
						<div
							class="fs12 cgray"
							style="text-align: justify;">
							<?=stripslashes(htmlspecialchars($post_data['anounces']))?>
							<?// if( ! in_array($post_data['mission_type'],array(2,3))){ ?>
								<?// if(strlen($post_data['anounces']) > 6){ ?>
									<?//=stripslashes(htmlspecialchars($post_data['anounces']))?>
								<?// } else { ?>
									<?//=tag_helper::get_short(stripslashes(htmlspecialchars(strip_tags($post_data['body']))),250)?>
								<?// } ?>
							<?// } ?>
						</div>
					<? } ?>

					<div>
						<? load::model('user/user_data'); ?>
						<? $post_user = user_data_peer::instance()->get_item($post_data['user_id']); ?>
						<table style="margin: 0px; padding: 0px;">
							<tr>
								<td
									class="fs11"
									style="
										width: 128px;
										padding: 0px;
										vertical-align: middle
									">
									<? if($post_data['mpu']>0){ ?>
                                                                        <a href="/profile-31">
										<?=t('Секретариат МПУ')?>
									</a>
                                                                        <? }else{ ?>
									<a href="/profile-<?=$post_data['user_id']?>">
										<?=$post_user['first_name'].' '.$post_user['last_name']?>
									</a>
                                                                        <? } ?>
								</td>
								<td
									class="fs11 cgray"
									style="
										padding: 0px;
										vertical-align: middle
									">
									<?=date("d/m/y", $post_data['created_ts'])?>
								</td>
								<td class="fs11 cgray" style="padding: 0px; width: 128px">

									<div class="right ml5">
										<a href="/blogpost<?=$post_data['id']?>#comments" style="color: #aaa; border: 0px">
											<div
												class="left"
												style="padding-top: 4px;">
												<img src="/static/images/icons/comment.png" width="19" height="19" />
											</div>
											<div
												class="left"
												style="
													height: 19px;
													padding-top: 4px;
													padding-left: 2px;
												">
												<?=blogs_comments_peer::instance()->get_count_by_post($post_data['id'])?>
											</div>
											<div class="clear"></div>
										</a>
									</div>
									
									<div class="right ml5">
										<a href="/blogpost<?=$post_data['id']?>" style="color: #aaa; border: 0px">
											<div
												class="left"
												style="padding-top: 2px;">
												<img src="/static/images/icons/views.png" width="19" height="19" />
											</div>
											<div
												class="left"
												style="
													height: 19px;
													padding-top: 4px;
													padding-left: 2px;
												">
												<?=(int)$post_data['views']?>
											</div>
											<div class="clear"></div>
										</a>
									</div>

									<? if(session::has_credential('admin')){ ?>
	                                    <? if(!$post_data['novotes']){ ?>
											<div class="right">
												<a href="/blogpost<?=$post_data['id']?>" style="color: #aaa; border: 0px">
													<div class="left">
														<img src="/static/images/icons/hand.png" width="19" height="19" />
													</div>
													<div
														class="left"
														style="
															height: 19px;
															padding-top: 4px;
															padding-left: 2px;
														">
														<?=(int) $post_data['for']?>
													</div>
													<div class="clear"></div>
												</a>
											</div>
	                                    <? } ?>
                                    <? } ?>

									<div class="clear"></div>

								</td>
							</tr>
						</table>
						<!--<a href="/blogpost<?=$post_data['id']?>" class="fs11 cgray mr15"><?=t('Читать дальше')?> &rarr;</a>-->
					</div>
				</td>
			</tr>
		</table>
	</div>
<? } ?>
