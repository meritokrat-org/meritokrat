<? /* if ( $similar ) { ?>
	<h1 class="ml10 mt10 column_head"><?=t('Записи по теме')?></h1>
	<? foreach ( $similar as $id ) { ?>
		<? if ( !$post = blogs_posts_peer::instance()->get_item($id) ) { continue; } ?>
		<? if ( $post['id'] == $post_data['id'] ) { continue; } ?>
			<div style="background: #F7F7F7;" class="ml10 p5 mb10">
				<div class="left fs11" style="margin-top: 3px;"><?=date('H:i', $post['created_ts'])?></div>
				<div style="margin-left: 45px;">
					<a class="<?=$post['rate'] > 5 ? 'bold' : ''?> fs12" href="/blogpost<?=$id?>"><?=stripslashes(htmlspecialchars($post['title']))?></a>
					<div class="fs11"><?=user_helper::full_name($post['user_id'], true, array('class' => 'quiet'))?></div>
				</div>
				<div class="clear"></div>
			</div>
	<? } ?>
<? } */ ?>