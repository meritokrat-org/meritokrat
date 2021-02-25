<? if($post_data){ ?>
	<div class="box_content mt10 p10">
		
		<table style="padding: 0px; margin: 0px;">
			<tr>

				<td style="padding: 0px;padding-left: 5px">
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

					<div>
						<a
							href="/blogpost<?=$post_data['id']?>"
							style="line-height: normal;"
							class="fs16">
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

					<div>
						<table style="margin: 0px; padding: 0px;">
							<tr>
								<td
									class="fs11 cgray"
									style="
										padding: 0px;
										vertical-align: middle
									">
									<?=date("H:i, d/m/y", $post_data['created_ts'])?>
								</td>
								<td
									class="fs11 cgray"
									style="
										padding: 0px;
										width: 128px;
									">
									<div class="right">
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
                                                                        <div class="right mr5">
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
                                                                        <? if(!$post_data['novotes']){ ?>
									<div class="right mr5">
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
