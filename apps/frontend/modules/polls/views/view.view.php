<h1 class="column_head mr10 mt10"><a href="/polls"><?=t('Опросы')?></a> &rarr; <?=t('Просмотр')?></h1>
<? $has_voted = polls_votes_peer::instance()->has_voted($poll_id, session::get_user_id()); ?>

<div>
	<?=user_helper::photo($poll['user_id'], 't', array('class' => 'border1 mr10 mt10 ml5', 'align' => 'left'))?>
	<div class="left" style="width: 660px;">
            
		<? if ( session::is_authenticated() ) { ?>
        <div class="right" style="width:120px">
			<?=user_helper::share_item('poll', $poll['id'], array('class' => 'right', 'style'=>"margin-top: 13px;"))?>

                <? load::model('bookmarks/bookmarks'); ?>
                <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),5,$poll['id']); ?>

                    <a class="right bookmark mb10 ml5 b5" style="<?=($bkm)?'display:none':''?>" href="#add_bookmark" onclick="Application.bookmarkItem('5','<?=$poll['id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
                    <a class="right unbkmrk mb10 ml5 b5" style="<?=($bkm)?'':'display:none'?>" href="#del_bookmark" onclick="Application.unbookmarkItem('5','<?=$poll['id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>

                <? if(session::has_credential('admin')) { ?>
                    <a href="javascript:;" onclick="Application.inviteItem('poll', 3, <?=$poll['id']?>)" class="right mb10 ml5 b5"><span class="fs18"><?=t('Пригласить')?></span></a>
                <? } ?>
        </div>    
        <? } ?>
            <h1 class="ml5 mt10 fs18 cbrown" style="text-decoration: none; margin-bottom: 0px;"><?=user_helper::get_links(stripslashes(nl2br(htmlspecialchars($poll['question']))),false)?></h1>
		<div class="fs11 quiet ml5">
                        <?=($poll['edit'])?t('Отредактировано').': '.strip_tags(user_helper::full_name($poll['edit']),'<a>').(($poll['edit_ts'])?' '.user_helper::com_date(date($poll['edit_ts'])).'<br/>':''):''?>
			<?=user_helper::full_name($poll['user_id'],true,array())?> &nbsp;&nbsp;&nbsp;
			<span class="quiet"><?=date_helper::human($poll['created_ts'], ', ')?></span>
                        
		</div>
                <? if ( session::has_credential('moderator') ) { ?>
                <div class="mt15">
			<a href="/polls/hide?id=<?=$poll['id']?>" class="fs11 ml5 cgray"><?=t('Скрыть')?></a>
			<a href="/polls/promote?id=<?=$poll['id']?>" class="ml10 fs11 ml5 cgray"><?=t('Сделать важным')?></a>
                 <? if ( session::has_credential('moderator') ) { ?>
                        <a id="polledit" href="javascript:;" class="fs11 ml10 cgray"><?=t('Редактировать')?></a>
			<a onclick="Application.delItem('<?=$poll['id']?>','polls/delete_poll')" href="javascript:;" class="fs11 ml10 cgray"><?=t('Удалить')?></a>
		<? } ?>
                </div>
		<? } ?>
		<div class="clear"></div>
	</div>
</div>

<div class="clear mb10"></div>

<form action="/polls/vote" rel="<?=$poll_id?>" onsubmit="pollsController.vote( this ); return false;">
	<input type="hidden" name="poll_id" value="<?=$poll_id?>">
	<div class="mb5" style="line-height: 120%" id="results_<?=$poll_id?>">
		<? if ( !$has_voted && session::get_user_id() ) { ?>
			<div class="mb10 ml5 quiet fs11"><?=t('Вы сможете увидеть результаты после того, как проголосуете')?></div>
			<? if ( $poll['is_multi'] ) { ?>
				<div class="mb10 ml5 quiet fs11"><?=t('Вы можете выбрать несколько вариантов ответов')?></div>
			<? } ?>
			<? $answers = polls_answers_peer::instance()->get_by_poll($poll_id); ?>
			<? foreach ( $answers as $answer_id ) { ?>
				<? $answer = polls_answers_peer::instance()->get_item($answer_id) ?>
				<div class="p5 mr10" style="border: 1px solid #FFF;">
					<div class="left">
						<? if ( $poll['is_multi'] ) { ?>
							<input type="checkbox" name="answer[<?=$answer['id']?>]" id="answer_<?=$answer['id']?>" value="1" />
						<? } else { ?>
							<input type="radio" name="answer" id="answer_<?=$answer['id']?>" value="<?=$answer['id']?>" />
						<? } ?>
					</div>
					<label class="ml5 left" style="width: 635px;" for="answer_<?=$answer['id']?>"><?=stripslashes(htmlspecialchars($answer['answer']))?></label>
					<div class="clear"></div>
				</div>
			<? } ?>

			<? if ( $poll['is_custom'] ) { ?>
				<div class="p5 mr10" style="border: 1px solid #FFF;">
					<div class="left">
						<? if ( $poll['is_multi'] ) { ?>
							<input type="checkbox" name="answer_custom" id="answer_custom" value="1" />
						<? } else { ?>
							<input type="radio" name="answer" id="answer_custom" value="custom" />
						<? } ?>
					</div>
					<div class="ml5 left" style="width: 635px;">
						<label style="display:block;" for="answer_custom" class="mb5"><?=t('Свой вариант')?></label>
						<textarea name="custom" style="width: 500px; height: 50px;"></textarea>
					</div>
					<div class="clear"></div>
				</div>
			<? } ?>

		<? } else { ?>

			<?
			$answers_list = polls_answers_peer::instance()->get_by_poll($poll_id);
			$answers = array();
			$max_count = 0;
                        $num = 0;
			foreach ( $answers_list as $answer_id ) {
				$answer = polls_answers_peer::instance()->get_item($answer_id);
                                //$answers[] = $answer;     
                                if($answers[$answer['count']])
                                {
                                    $arr = 'zyxwvujsrqponmkhgf';
                                    $answers[$answer['count'].$arr[$num]] = $answer;
                                    $num++;
                                }
                                else
                                {
                                    $answers[$answer['count']] = $answer;
                                }
				if ( $max_count < $answer['count'] ) { $max_count = $answer['count']; };
			}
                        krsort($answers);
			?>

			<div style="border: 1px solid #EEE; background: #FAFAFA;" class="mr10 mb10 p5 quiet fs11 acenter">
				<?=t('Общее количество проголосовавших')?>: <?=$poll['count']?>,
				<?=t('голосование длиться')?> <?=floor((time() - $poll['created_ts'])/3600)?> <?=t('часов')?>
			</div>
                        <? $k = 1 ?>
			<? foreach ( $answers as $i => $answer ) { ?>
				<div class="left" style="width: 25px;"><?=$k++?>.</div>
				<div class="left fs11" style="width: 150px;">
					<div><?=stripslashes(htmlspecialchars($answer['answer']))?></div>
					<div class="fs11 mb5 quiet"><?=t('Голосов')?>: <b><?=(int)$answer['count']?> (<?=($poll['count']>0)?ceil(((int)$answer['count']/(int)$poll['count'])*100):'0'?>%)</b></div>
					<? if ( session::has_credential('admin') ) { ?>
						<a href="polls/voters?id=<?=$poll['id']?>&answer=<?=$answer['id']?>">Список голосовавших &rarr;</a>
					<? } ?>
				</div>
				<div class="left mt10">
					<div class="mb10 mr10" style="width: <?=ceil($answer['count']/max($max_count, 1)*500)?>px; background: #DDEEDD; border: 1px solid #AACCAA; height: 5px;"></div>
				</div>
				<div class="clear mb5"></div>
			<? } ?>

			<? if ( $poll['is_custom'] && $custom_list ) { ?>
				<div class="left" style="width: 25px;">&nbsp;</div>
				<div class="left fs11" style="width: 665px;">
					<br/>
					<h4 class="mb10"><?=t('Другие варианты ответов')?>:</h4>
					<ul>
						<? foreach ( $custom_list as $custom ) { ?>
							<li>
								<?=stripslashes(htmlspecialchars($custom['text']))?><br/>
								<span class="fs11 mb5 quiet"><?=t('Голосов')?>: <b><?=(int)$custom['total']?></b></span>
								<? if ( session::has_credential('admin') ) { ?>
									<a href="polls/delete_custom?id=<?=$poll['id']?>&answer=<?=urlencode($custom['text'])?>">X</a>
								<? } ?>
							</li>
						<? } ?>
					</ul>
					<? if ( session::has_credential('admin') ) { ?>
						<a href="polls/voters?id=<?=$poll['id']?>">Список голосовавших &rarr;</a>
					<? } ?>
				</div>
				<div class="clear mb5"></div>
			<? } ?>

		<? } ?>
	</div>
	<? if ( !$has_voted && session::get_user_id()) { ?>
		<input id="submit_<?=$id?>" type="submit" class="button" value=" <?=t('Голосовать')?> ">
		<?=tag_helper::wait_panel('wait_' . $poll_id)?>
	<? } ?>
</form>

<? if(!$poll['nocomments']){ ?>
<div class="mr10 mt10">
	<div class="column_head">
        <div class="left icocomments mr5" style="margin-top:3px"></div>
        <div class="left" style="margin-top:2px"><?=t('Комментарии')?></div>
        </div>
        <? $user=user_auth_peer::instance()->get_item(session::get_user_id());
        if (session::is_authenticated() || 1) {?>
	<div class="mt10 mb10" id="comments">
		<? if ( !$comments ) { ?>
			<div id="no_comments" class="acenter fs11 quiet"><?=t('Нет комментариев')?></div>
		<? } else { ?>
			<? foreach ( $comments as $id ) { include 'partials/comment.php'; } ?>
		<? } ?>
	</div>

	 
            <?if($user['status']>1){ ?>
			<form class="form_bg" id="comment_form" action="/polls/comment">
				<h3 class="column_head_small mb5"><?=t('Добавить комментарий')?></h3>
				<div class="ml10 mr10 mb10">
					<input type="hidden" name="poll_id" value="<?=$poll_id?>"/>
					<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 75px;" name="text"></textarea>
					<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
					<?=tag_helper::wait_panel()?>
				</div>
			</form>

			<form id="comment_reply_form" class="hidden" action="/polls/comment">
				<input type="hidden" name="poll_id" value="<?=$poll_id?>"/>
				<input type="hidden" name="parent_id"/>
				<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 50px;" name="text"></textarea>
				<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
				<?=tag_helper::wait_panel()?>
				<input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="$('#comment_reply_form').hide();">
			</form>

                        <form id="comment_update_form" class="hidden" action="/polls/comment">
                                <input type="hidden" name="upd_id" id="upd_id"/>
                                <input type="hidden" name="why" id="why"/>
                                <input type="hidden" name="poll_id" value="<?=$poll_id?>"/>
                                <textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 100px;" name="text"></textarea>
                                <input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Сохранить')?> " />
                                <?=tag_helper::wait_panel()?>
                                <input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="Application.cancelComUpd()">
                        </form>
	<? }else{ ?>
            <a href="http://<?=context::get('host');?>/help/index?prava_v_merezhi">
                <?=user_helper::login_require(t('Гости не могут комментировать данный контент'))?>
            </a>
         <?}} else { ?>
		<div class="mt10 p5 acenter fs12" style="border: 1px solid #E4E4E4; background: #F7F7F7;">
                        <a href="/sign/up"><?=t('Зарегистрируйтесь')?></a>&nbsp;<?=t('или')?>&nbsp;<a href="/sign"><?=t('войдите')?></a>&nbsp;<?=t('на сайт чтобы просматривать и оставлять комментарии')?>
                </div>
	<? } ?>
</div>
<? } ?>

<script type="text/javascript">
    $('#polledit').click(function(){
        var why = prompt("Вкажiть причину редагування:", "");
        if(!why){
            alert("Ви не можете редагувати без поважної причини");
            return false;
        }
        window.location = 'http://'+window.location.hostname+'/polls/edit?id=<?=$poll['id']?>&why='+why;
    });
</script>
<?if($user['status']==1){?>
<style type="text/css">
    .comment_reply{
        display: none
    }
</style>
<?}?>