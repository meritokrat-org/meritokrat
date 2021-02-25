<? $user_desktop['user_id'] ? $user_desktop['user_id']=$user_desktop['user_id'] : $user_desktop['user_id']=0; ?>

<? if( user_auth_peer::instance()->get_rights($user_desktop['user_id'], 1)){ // user_auth_peer::instance()->get_rights($user_desktop['user_id'], 10)  || $user['desktop']==1 ?>

	<div class="column_head_small mt10 fs11">
		<a href="/profile/desktop?id=<?=$user_data['user_id']?>"><?=t('Рабочий стол')?></a>
	</div>
	<div class="fs11">
		<div class="ml10"><?=t('Проагитировал за Игоря Шевченка')?> <span class="bold"><?=$count_people_will_vote?> <?=t('людей')?></span></div>
		<div class="ml10 mt10"><?=t('Внес в избирательный фонд')?> <span class="bold"><?=number_format($payments_summa, 0, ",", " ")?> грн</span></div>
		<? if($task_cover_facebook){ ?>
			<div class="ml10 mt10"><?=t('Установил обложку в Facebook')?></div>
		<? } ?>
		<div class="ml10 mt10"><?=t('Зарегистрировал')?> <span class="bold"><?=db::get_scalar(
			'SELECT COUNT(creator) FROM user_data WHERE creator = :creator', [
			'creator' => $user_data['user_id']
			]);
		?> <?=t('людей')?></span></div>
		<? if(count($mini_desktop_data) > 0){ ?>
			<div class="mt10 ml10">
				<div class="bold"><?=t('Готов')?>:</div>
				<ul>
					<? if($mini_desktop_data['willVote']){ ?>
						<li><?=t('Голосовать за Игоря Шевченка')?></li>
					<? } ?>
					<? if($mini_desktop_data['financialSupport']){ ?>
						<li><?=t('Поддержать финансово')?></li>
					<? } ?>
					<? if($mini_desktop_data['agitator']){ ?>
						<li><?=t('Быть активистом')?></li>
					<? } ?>
					<? if($mini_desktop_data['wantRun']){ ?>
						<li><?=t('Баллотироваться в ВР')?></li>
					<? } ?>
					<? if($mini_desktop_data['wantToBeObserver']){ ?>
						<li><?=t('Быть наблюдателем')?></li>
					<? } ?>
					<? if($mini_desktop_data['wantToBeCommissioner']){ ?>
						<li><?=t('Быть членом комиссии')?></li>
					<? } ?>
					<? if($mini_desktop_data['wantToBeLawyer']){ ?>
						<li><?=t('Защищать голоса')?></li>
					<? } ?>
				</ul>
			</div>
		<? } ?>
		<div class="mt10"><hr /></div>
	</div>

<? } ?>