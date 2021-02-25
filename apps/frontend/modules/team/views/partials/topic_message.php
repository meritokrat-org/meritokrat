<? $topic_message = ppo_topics_messages_peer::instance()->get_item($id) ?>
<div class="mb10 comment_bg mr10" id="talk_message<?=$id?>">
    
    <? if ($counter==1 && request::get_int('page')<2) { ?>
	<div class="left acenter mt5" style="width: 80px;">
		<?=user_helper::photo($topic_message['user_id'], 't', array('class' => 'border1'))?>
		<div class="fs11 mb10">
			<?=user_helper::full_name($topic_message['user_id'],true,array("style"=>"font-weight:bold;"))?>
			<br /><br />
			<a href="/blog-<?=$topic_message['user_id']?>"><?=t('Мысли пользователя')?></a>
		</div>
		
        <? if ( session::is_authenticated() ){ ?>
        <? load::model('bookmarks/bookmarks'); ?>
        <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),6,$topic_message['user_id']); ?>
        <div class="fs11 mb10">
            <a href="#add_bookmark" class="b6 <?=($bkm)?'hide':''?>" onclick="Application.bookmarkItem('6','<?=$topic_message['user_id']?>');return false;"><?=t('Добавить в любимые авторы')?></a>
		    <a href="#del_bookmark" class="b6 <?=($bkm)?'':'hide'?>" onclick="Application.unbookmarkItem('6','<?=$topic_message['user_id']?>');return false;"><?=t('Удалить из любимых авторов')?></a>  
        </div>
        <? } ?>
        
        <br />
		<div class="fs11 mb5 quiet"><?=t('Оценка')?></div>

		<div class="mb5 acenter" id="vote_value">
			<span class="green"><?=$topic_message['rate']?></span>
			<span class="red ml5"><?=$post_data['against']?></span>
		</div>


		<div id="vote_pane">
				
			<?  if ( session::is_authenticated() && !ppo_topics_messages_peer::instance()->has_rated($topic_message['id'], session::get_user_id()) ) { ?>
					<a href="javascript:;" onclick="ppoController.rateMessage(this, <?=$topic_message['id']?>, true);"><?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
					<a href="javascript:;" onclick="ppoController.rateMessage(this, <?=$topic_message['id']?>, false);"><?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
			<? }  ?>
                </div>

		<div class="fs11 mb5 quiet"><?=t('Просмотров')?></div>
		<div class="acenter bold"><?=(int)$topic['views']?></div>
	</div>

    <? } else { ?>
	<div class="left"><?=user_helper::photo($topic_message['user_id'], 's', array('class' => 'border1'))?>
        <? /* if (!$flag) {  ?>
            <br/>
		<div class="fs11 mb5 quiet"><?=t('Просмотров')?></div>
		<div class="acenter bold"><?=(int)$topic['views']?></div>
       <? } $flag=1;*/ ?>
        </div>
    <? } ?>
	<div class="left ml10" style="width: 660px;">
		<div class="fs11 pb5">
			<?=user_helper::full_name($topic_message['user_id'])?>
			<span class="quiet ml10"><?=date_helper::human($topic_message['created_ts'], ', ')?></span>

		<div class="right">
			<?  if ( session::is_authenticated() && !ppo_topics_messages_peer::instance()->has_rated($topic_message['id'], session::get_user_id()) ) { ?>
				<span>
					<a href="javascript:;" onclick="ppoController.rateMessage(this, <?=$topic_message['id']?>, true);"><?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
					<a href="javascript:;" onclick="ppoController.rateMessage(this, <?=$topic_message['id']?>, false);"><?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
				</span>
			<? }  ?>
			<span class="bold mr10" id="message_rate" style="color:<?=$topic_message['rate'] >= 0 ? $topic_message['rate'] > 0 ? 'green' : '#999' : 'red' ?>"><?=$topic_message['rate'] > 0 ? '+' : ''?><?=$topic_message['rate']?></span>
		</div>
                </div>

		<div class="fs12 mt5"><?=$counter==1 ? stripslashes($topic_message['text']) : stripslashes(nl2br($topic_message['text']))?></div>

		<? if ( ( $topic_message['user_id'] == session::get_user_id() ) || ppo_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
			<div class="fs10 mt10">

                                <a href="/ppo/talk_message_edit?id=<?=$id?><?=$counter==1 ? '&tinymce=1' : ''?>" class="maroon dotted"><?=t('Редактировать')?></a>
                                
				<a href="javascript:;" onclick="ppoController.deleteTalkMessage(<?=$id?>);" class="ml10 maroon dotted"><?=t('Удалить')?></a>
                                
			</div>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>