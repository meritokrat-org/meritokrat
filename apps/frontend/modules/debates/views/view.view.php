<h1 class="column_head mt10 mr10"><a href="/debates"><?=t('Дебаты')?></a> &rarr; <?=t('Просмотр')?></h1>

<div>
	<?=user_helper::photo($data['user_id'], 't', array('class' => 'border1 mr10', 'align' => 'left'))?>
	<div class="left" style="width: 590px;">
		<h4 class="mb5"><?=htmlspecialchars($data['text'])?></h4>
		<? if ( $data['tags_text'] ) { ?>
			<div class="fs11 mb5">
				<b><?=t('Метки')?>:</b>
				<? foreach ( explode(', ', $data['tags_text']) as $tag ) echo "<a href=\"/debates/hot?tag=" . htmlspecialchars($tag) . "\" class=\"mr10\">{$tag}</a>" ?>
			</div>
		<? } ?>

		<? if ( session::has_credential('moderator') ) { ?>
			<a href="/debates/hide?id=<?=$data['id']?>" class="fs11"><?=t('Скрыть')?></a>
		<? } ?>

		<div class="fs11 left">
			<span class="quiet"><?=date_helper::human($data['created_ts'], ', ')?></span><br />
			<?=user_helper::full_name($data['user_id'])?>
		</div>
		<? if ( session::is_authenticated() ) { ?>
			<?=user_helper::share_item('debate', $data['id'], array('class' => 'right'))?>
		<? } ?>
		<div class="clear"></div>
	</div>
</div>

<div class="clear mb10"></div>

<div style="border: 1px solid #EEE; background: #FAFAFA;" class="mr10"><table class="mb5"><tr><td width="50%">
<div class="right fs11 mt10">
	<div class="right mb5"><b id="votes_against"><?=$data['against']?></b> <?=t('Против')?> <?=tag_helper::image('common/down.gif', array('class' => 'vcenter'))?></div>
	<div class="clear right mt5" style="width: <?=ceil($data['against']/max($data['for'], $data['against'], 1)*285)?>px; background: #EEDDDD; border: 1px solid #CCAAAA; height: 5px;"></div>
</div>
</td><td width="50%">
<div class="fs11 mt10">
	<div class="mb5"><?=tag_helper::image('common/up.gif', array('class' => 'vcenter'))?> <b id="votes_for"><?=$data['for']?></b> <?=t('За')?></div>
	<div class="left mt5" style="width: <?=ceil($data['for']/max($data['for'], $data['against'], 1)*285)?>px; background: #DDEEDD; border: 1px solid #AACCAA; height: 5px;"></div>
</div>
</td></tr></table></div>

<br />

<h2 class="column_head mr10">
	<div class="left"><?=t('Аргументы')?></div>
	<? if ( !debates_peer::instance()->has_voted($data['id'], session::get_user_id()) ) { ?>
		<div class="right fs12"><a href="#vote_form" class="dotted"><?=t('Высказать свою позицию')?></a></div>
	<? } ?>
	<div class="clear"></div>
</h2>

<div id="arguments" class="mb10">
	<? if ( !$arguments ) { ?>
		<div class="acenter screen_message"><?=t('Аргументов еще не высказывали')?></div>
	<? } ?>
	<? foreach ( $arguments as $id ) { include 'partials/argument.php'; } ?>
</div>

<div class="clear"></div><br />

<? if ( !session::is_authenticated() ) { ?>
	<?=user_helper::login_require( t('Войдите на сайт, что-бы высказать свою позицию') )?>
<? } else { ?>
	<? if ( !debates_peer::instance()->has_voted($data['id'], session::get_user_id()) ) { ?>
	<div class="form_bg"><form id="vote_form" action="/debates/vote">
		<h3 class="column_head_small"><?=t('Высказать свою позицию')?></h3>
		<div class="p10" style="padding-top: 0px;">
			<div class="fs11 bold mb5">
				<?=tag_helper::image('common/up.gif', array('class' => 'vcenter'))?>
				<input type="radio" name="agree" value="y" checked /><label for="agree_y"><?=t('Я согласен')?></label>

				<?=tag_helper::image('common/down.gif', array('class' => 'ml10 vcenter'))?>
				<input type="radio" name="agree" value="n" /><label for="agree_n"><?=t('Я не согласен')?></label>
			</div>

			<input type="hidden" name="id" value="<?=$data['id']?>"/>
			<div class="quiet fs11"><?=t('Аргументируйте свою позицию (не обязательно)')?>:</div>
			<textarea class="mt5" rel="<?=t('Напишите текст')?>" style="width: 99%;" name="text"></textarea>

			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
			<?=tag_helper::wait_panel()?>
		</div>
	</form></div>
	<? } ?>
<? } ?>

<form id="reply_form" class="hidden mt10" action="/debates/vote?id=<?=$data['id']?>">
	<input type="hidden" name="parent_id">
	<input type="hidden" name="agree">
	<textarea rel="<?=t('Напишите текст')?>" style="width: 97%; height: 50px;" name="text" class="form-control"></textarea>
	<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
	<?=tag_helper::wait_panel('reply_wait')?>
	<input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="$('.reply > a').removeClass('bold');$('#reply_form').hide();">
</form>
