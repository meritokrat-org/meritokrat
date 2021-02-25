<? /*<div class="ml10">
    <div class="left"  style=" width:100%">
	<h1 class="column_head" style="cursor: pointer;" onclick="Application.ShowHide('groups')">
		<span class="left"><?=t('Новые сообщества')?></span>
	</h1>
    <div class="left" id="groups">
	<? foreach ( $groups as $id ) { ?>
		<? $group = groups_peer::instance()->get_item($id) ?>
		<div class="p10 box_content mb10">

			<div class="fs10 left acenter" style="width: 60px">
                        <?=group_helper::photo($group['id'], 's', true, array('class' => 'border1'))?>
			</div>
			<div class="left ml5" style="margin-bottom: -5px; width: 145px; line-height: 130%;">
				<a href="/group<?=$id?>"><?=stripslashes(htmlspecialchars($group['title']))?></a>
                                
				<div class="fs11 quiet">
                                    <?=groups_peer::instance()->get_type($group['type'])?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
         <? } ?>

	<div class="box_content p5 mt5">
		<a class="fs11" href="/groups"><?=t('Все сообщества')?> &rarr;</a>
	</div>
    </div>
    </div>
    <div class="left"  style=" width:100%">
    <!-- ОПИТУВАННЯ -->

    	<h1 class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('polls')">
		<span class="left"><?=t('Новые опросы')?></span>
	</h1>
        <div class="left" id="polls">
            <? $num = 0 ?>
	<? foreach ( $new_polls as $id ) { ?>
                <? $num++ ?>
                <? if($num>3)break; ?>
		<? $poll = polls_peer::instance()->get_item($id) ?>

		<div class="p10 box_content mt10">
			<!--div class="fs10 left acenter" style="width: 60px">
				<?//=user_helper::photo($poll['user_id'], 's', array('class' => 'border1'))?><br />
				<?=user_helper::full_name($poll['user_id'])?>
			</div-->
			<div class="left ml5" style="margin-bottom: -5px;line-height: 130%;">
				<? $question = explode(' ', $poll['question']); ?>
				<? $question = implode(' ', array_slice($question, 0, 32)) ?>
                           <a style="text-decoration: underline; font-size:13px;" href="/poll<?=$id?>"><?=tag_helper::get_short($question)?><?//stripslashes(htmlspecialchars($question))?></a>
				<div class="fs11 quiet">
					<?//=date_helper::human($poll['created_ts'], ', ')?><!--br /-->
                                        <?//=user_helper::full_name($poll['user_id'])?><!--br /-->
					 <?=t('Количество проголосовавших')?>: <b><?=$poll['count']?></b>
				</div>
				<? if ( !polls_votes_peer::instance()->has_voted($id, session::get_user_id()) ) { ?>
					<a class="fs11 bold" href="/poll<?=$id?>"> <?=t('Голосовать')?> &rarr;</a>
				<? } else { ?>
					<a class="fs11" href="/poll<?=$id?>"> <?=t('Смотреть результаты')?> &rarr;</a>
				<? } ?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>

	<div class="box_content p5 mt5">
		<a class="fs11" href="/polls"><?=t('Все опросы')?> &rarr;</a>
	</div>
        </div>
    </div>
</div>
*/ ?>