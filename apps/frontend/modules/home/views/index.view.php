<?php
load::view_helper('image'); ?>
<?php
//if (session::has_credential('admin')) { ?>
<!--<div class="mt5" style='width:755px;height:135px; background: url("https://meritokrat.org/static/images/common/pl_bg1.png") repeat scroll 3% 0% transparent;'>
        <div style="margin-left:11px;" class="left">
            &nbsp;
        </div>
        <?php
/*if (session::get_user_id()==29) {
       $photos=db::get_cols("SELECT id FROM photo_competition");

       foreach ($photos as $photo_id) {
           $photo=photo_competition_peer::instance()->get_item($photo_id); ?>
       <div style="margin-left:6px;margin-top:20px" class="left">
               <?=photo_competition_peer::photo($photo['id'], 'pb3', array())?>
       </div>
       <? } ?>
       <? } */ ?>
        <?php
$ids = array(8, 9, 12, 14, 16, 17);
shuffle($ids);
$ids = array_slice($ids, 0, 4);
//foreach( $ids as $photo_id) { ?>
    		<?php
$photos_qwerty = array('16' => '1', '62' => '2', '17' => '3', '21' => '4'); ?>
    		<?php
foreach ($photos_qwerty as $kzz => $zzz) { ?>
        <div style="margin-left:6px;margin-top:20px" class="left">
            <a href="/photocompetition/<?php
    //=$kzz?>" class="pointer">
                <img src="https://meritokrat.org/static/images/tmp/finish/<?= $zzz ?>.jpg" width="138" height="93" />
            </a>
        </div>
        <?php
} ?>
        <div class="left" style="margin-left: 6px; margin-top: 20px;">
            <a class="pointer" href="/photocompetition/">
                <img src="https://meritokrat.org/static/images/tmp/finish/photo.gif" />
            </a>
        </div>
    </div>-->
<?php
//} ?>

<div class="mt10 hide">
    <a href="/profile/desktop?id=<?= session::get_user_id() ?>"><img
                src="/static/images/vnesok<?php
                if (translate::get_lang() == 'ru') { ?>_ru<?php
                } ?>.gif"/></a>
</div>

<?php
if (session::is_authenticated()) { ?>
    <div class="left" style="width: 35%;">
        <!--<img class="mt10" src="/static/images/im_golodomor.png" />-->
        <?php
        load::view_helper('party') ?>
        <?php
        load::view_helper('group') ?>
        <?php
        if (session::is_authenticated()) { ?>
            <!--<div class="column_head mt10" >
	            <b class="left" style="cursor: pointer;" onclick="Application.ShowHide('declarations')"><?= t(
                'Объявления'
            ) ?></b>
	        </div>
	        <?php
            if (session::has_credential('admin')) { ?>
	            <div class="box_content p5">
	                <a class="fs11" href="/blogs/edit?type=<?= blogs_posts_peer::TYPE_DECLARATION_POST ?>"><?= t(
                'Добавить объявление'
            ) ?> &rarr;</a>
	            </div>
	        <?php
            } ?>
	        <div class="mb5 mt5" id="declarations">
	            <?php
            if ($declarations) { ?>
	                <?php
                foreach ($declarations as $id) { ?>
	                    <?php
                    $data = blogs_posts_peer::instance()->get_item($id) ?>

	                    <div class="mb5 p5 box_content">
	                        <span class="quiet fs11"><?= date_helper::human($data['created_ts'], ', ') ?></span><br>
	                        <a href="/blogpost<?= $id ?>" class="bold a_left"><?= stripslashes(
                        htmlspecialchars($data['title'])
                    ) ?></a>
	                    </div>
	                <?php
                } ?>
	            <?php
            } ?>
	            <div class="box_content p5">
	                <a class="fs11" rel="nofollow" href="/blogs/declarations"><?= t('Все объявления') ?> &rarr;</a>
	            </div>
	        </div>-->
            <?php
        } ?>

        <?php
        if ($new_events) {
            $cats = events_peer::get_cats();
            $sections = events_peer::get_types();
            load::view_helper('image');
            load::view_helper('date');
            ?>

            <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('new_events')">
                <b class="left" style="margin-top:3px;"><?= t('Ближайшие события') ?></b>
                <a class="right" style="margin-top:3px;" rel="nofollow" href="/events"><?= t('Все') ?> &rarr;</a>
            </div>
            <div class="mb5 mt5" id="new_events">
                <?php
                foreach ($new_events as $id) { ?>
                    <?php
                    $event = events_peer::instance()->get_item($id) ?>
                    <div class="mb5 p5 box_content">
                        <a href="/event<?= $event['id'] ?>" style="font-size:17px;"
                           class="a_left bold"><?= stripslashes(htmlspecialchars($event['name'])) ?></a>

                        <div class="fs11"><?= date_helper::get_date_range($event['start'], $event['end']) ?></div>
                        <div class="fs11">
                            <?= t('Организатор') . ': ' ?>
                            <?php
                            switch ($event['type']) {
                                case 1:
                                    load::model('groups/groups');

                                    if ($event['content_id'] > 0) {
                                        $group = groups_peer::instance()->get_item($event['content_id']);
                                    }
                                    ?>

                                    <a href="/group<?= $group['id'] ?>"
                                       style="color:black"><?= $group['title'] ?></a> (<?= user_helper::full_name(
                                    $event['user_id'],
                                    true,
                                    array('style' => 'color:black'),
                                    false
                                ); ?>)

                                    <?php
                                    break;

                                case 4:
                                    load::model('ppo/ppo');

                                    if ($event['content_id'] > 0) {
                                        $ppo = ppo_peer::instance()->get_item($event['content_id']);
                                    }
                                    ?>

                                    <a href="/ppo<?= $ppo['id'] ?>/<?= $ppo['number'] ?>"
                                       style="color:black"><?= $ppo['title'] ?></a> (<?= user_helper::full_name(
                                    $event['user_id'],
                                    true,
                                    array('style' => 'color:black'),
                                    false
                                ); ?>)

                                    <?php
                                    break;

                                case 3:
                                    ?>

                                    <a href="/profile-31" style="color:black"><?= t('Секретариат МПУ') ?></a>

                                    <?php
                                    break;

                                default:
                                    echo user_helper::full_name(
                                        $event['user_id'],
                                        true,
                                        array('style' => 'color:black'),
                                        false
                                    );
                                    break;
                            } ?>

                            <br/>
                            <?= t('Место проведения') . ': ' ?>
                            <?php
                            $region = geo_peer::instance()->get_region(
                                $event['region_id']
                            ) ?><?= $region['name_' . translate::get_lang()] ?>
                            ,
                            <?php
                            $city = geo_peer::instance()->get_city(
                                $event['city_id']
                            ) ?><?= $city['name_' . translate::get_lang()] ?>
                        </div>
                        <?php
                        if ($event['status'] == 1 || $event['status'] == 3) { ?>
                            <span
                                    class="fs11 green"><?= ($event['status'] == 1) ? t(
                                    'Вы посещаете это мероприятие'
                                ) : t(
                                    'Вы возможно посещаете это мероприятие'
                                ) ?></span>
                            <?php
                        } ?>
                    </div>
                    <div class="clear"></div>
                    <?php
                } ?>
            </div>
            <?php
        } ?>

        <!--<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('group_news')">
    		<b class="left" style="margin-top:3px;"><?= t('Новости сообществ') ?></b>
    	</div>
        <div class="mb5 mt5" id="group_news">
            <?php
        if ($group_news) { ?>
                <?php
            foreach ($group_news as $id) { ?>
                    <?php
                $data = groups_news_peer::instance()->get_item($id) ?>
                    <div class="mb10 p10 box_content">
                        <span class="quiet fs11"><?= date_helper::human($data['created_ts'], ', ') ?></span><br>
                        <a href="/groups/newsread?id=<?= $id ?>" class="bold a_left"><?= stripslashes(
                    htmlspecialchars($data['title'])
                ) ?></a>
                    </div>
                <?php
            } ?>
            <?php
        } ?>
        </div>-->

        <?php if (session::has_credential('admin')) { ?>
            <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('team')">
                <b class="left" style="margin-top:3px;">
                    <a href="/team3/1"><?= t('Команда Игоря Шевченка') ?></a>
                </b>
                <a class="right" style="margin-top:3px;" rel="nofollow" href="/team"><?= t('Все') ?> &rarr;</a>
            </div>
            <div class="mb5 mt5" id="team">
                <?php
                if (count($team) > 0) { ?>
                    <?php
                    foreach ($team as $member) {
                        if ($member['id'] == 3) {
                            ?>
                            <div class="p10 box_content mb10">
                                <div class="fs10 left acenter" style="width: 60px">
                                    <?php
                                    if ($member['photo_salt']) { ?>
                                        <?= user_helper::team_photo(
                                            user_helper::team_photo_path($member['id'], 'p', $member['photo_salt']),
                                            ['width' => '60px']
                                        ) ?>
                                        <?php
                                    } else {
                                        load::view_helper('group');
                                        load::model('groups/groups'); ?>
                                        <?= group_helper::photo(0, 'p', false, ['width' => '60px']) ?>
                                        <?php
                                    } ?>
                                </div>
                                <div class="left ml5" style="margin-bottom: -5px; width: 145px; line-height: 130%;">
                                    <a href="/team3/1"><?= stripslashes(htmlspecialchars($member['title'])) ?></a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <?php
                        } ?>
                        <?php
                    } ?>
                    <?php
                } else { ?>
                    <div class="p10 box_content mb10"
                         style="text-align: center; color: #888; text-transform: uppercase; font-size: 11px">
                        <?= t('Тут еще ничего нет') ?>
                    </div>
                    <?php
                } ?>
            </div>
        <?php } ?>


        <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('projects')">
            <b class="left" style="margin-top:3px;"><?= t('Проекты') ?></b>
            <a class="right" style="margin-top:3px;" rel="nofollow" href="/projects"><?= t('Все') ?> &rarr;</a>
        </div>
        <div class="mb5 mt5" id="projects">
            <?php
            if (count($projects) > 0) { ?>
                <?php
                foreach ($projects as $project) {
                    ?>
                    <div class="p10 box_content mb10">
                        <div class="fs10 left acenter" style="width: 60px">
                            <?php
                            if (!empty($project['photo_salt'])) { ?>
                                <?= user_helper::reform_photo(
                                    user_helper::reform_photo_path($project['id'], 'p', $project['photo_salt']),
                                    ['width' => '60px']
                                ) ?>
                                <?php
                            } else {
                                load::view_helper('group');
                                load::model('groups/groups'); ?>
                                <?= group_helper::photo(0, 'p', false, ['width' => '60px']) ?>
                                <?php
                            } ?>
                        </div>
                        <div class="left ml5" style="margin-bottom: -5px; width: 145px; line-height: 130%;">
                            <?php
                            if (!empty($project['symlink'])) { ?>
                                <a href="/project/<?= $project['symlink'] ?>"><?= stripslashes(
                                        htmlspecialchars($project['title'])
                                    ) ?></a>
                                <?php
                            } else { ?>
                                <a href="/project<?= $project['id'] ?>/<?= $project['number'] ?>"><?= stripslashes(
                                        htmlspecialchars($project['title'])
                                    ) ?></a>
                                <?php
                            } ?>

                            <div class="fs11 quiet">
                                <?= reform_peer::instance()->get_level($project['category']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php
                } ?>
                <?php
            } else { ?>
                <div class="p10 box_content mb10"
                     style="text-align: center; color: #888; text-transform: uppercase; font-size: 11px">
                    <?= t('Тут еще ничего нет') ?>
                </div>
                <?php
            } ?>
        </div>

        <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('groups')">
            <b class="left" style="margin-top:3px;"><?= t('Сообщества') ?></b>
            <!--	        <a class="right" style="margin-top:3px;" rel="nofollow" href="/groups">-->
            <?php
            //=t('Все')?><!-- &rarr;</a>-->
        </div>
        <div class="mb5 mt5" id="groups">
            <?php
            foreach ($groups as $id) { ?>
                <?php
                $group = groups_peer::instance()->get_item($id) ?>
                <div class="p10 box_content mb10">
                    <div class="fs10 left acenter" style="width: 60px">
                        <?= group_helper::photo($group['id'], 's', true, array('class' => 'border1')) ?>
                    </div>
                    <div class="left ml5" style="margin-bottom: -5px; width: 145px; line-height: 130%;">
                        <a href="/group<?= $id ?>"><?= stripslashes(htmlspecialchars($group['title'])) ?></a>

                        <div class="fs11 quiet">
                            <?= ($group['category'] != 2) ? groups_peer::instance()->get_type($group['type']) : t(
                                'Рабочая группа'
                            ) ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
            } ?>
        </div>

        <!-- ОПИТУВАННЯ -->

        <!--<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('polls')">
                    <b class="left" style="margin-top:3px;"><?= t('Новые опросы') ?></b>
                    <a class="right" style="margin-top:3px;" rel="nofollow" href="/polls"><?= t('Все') ?> &rarr;</a>
                </div>
                <div  class="mb5 mt5" id="polls">
                    <?php
        // $num = 0 ?>
	                <?php
        // foreach ( $new_polls as $id ) { ?>
                        <?php
        // $num++ ?>
                        <?php
        // if($num>3)break; ?>
                        <?php
        // $poll = polls_peer::instance()->get_item($id) ?>

                        <div class="p10 box_content mt10">
                            <div class="fs10 left acenter" style="width: 60px">
                                    <?php
        //=user_helper::photo($poll['user_id'], 's', array('class' => 'border1'))?><br />
                                   <?php
        //=user_helper::full_name($poll['user_id'])?>
                            </div-->
        <!--<div class="left ml5" style="margin-bottom: -5px;line-height: 130%;">
                                    <?php
        // $question = explode(' ', $poll['question']); ?>
                                    <?php
        // $question = implode(' ', array_slice($question, 0, 32)) ?>
                               <a style="text-decoration: underline; font-size:13px;" href="/poll<?php
        //=$id?>"><?php
        //=tag_helper::get_short($question)?><?php
        //stripslashes(htmlspecialchars($question))?></a>
                                    <div class="fs11 quiet">
                                            <?php //=date_helper::human($poll['created_ts'], ', ')?><!--br /-->
        <?php
        //=user_helper::full_name($poll['user_id'])?><!--br /-->
        <?php
        //=t('Количество проголосовавших')?><!--: <b>--><?php
        //=$poll['count']?></b>
        <!--</div>
                                    <?php
        /* if ( !polls_votes_peer::instance()->has_voted($id, session::get_user_id()) ) { ?>
                                                   <a class="fs11 bold" href="/poll<?=$id?>"> <?=t('Голосовать')?> &rarr;</a>
                                           <? } else { ?>
                                                   <a class="fs11" href="/poll<?=$id?>"> <?=t('Смотреть результаты')?> &rarr;</a>
                                           <? }*/ ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php
        // } ?>
                </div>-->

        <?php
        if (session::is_authenticated()) { /*?>
	<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('news')">
		<b class="left" style="margin-top:3px;"><?= t('Новости МПУ') ?></b>
		<a class="right" style="margin-top:3px;" href="/blogs/news"><?= t('Все') ?> &rarr;</a>
	</div>
	<? if (session::has_credential('admin')) { ?>
	<div class="box_content p5">
		<a class="fs11" class="bold a_left"
		   href="/blogs/edit?type=<?= blogs_posts_peer::TYPE_NEWS_POST ?>"><?= t('Добавить новость') ?> &rarr;</a>
	</div>
	<? } ?>
	<div class="mb5 mt5" id="news">
		<? if ($news) { ?>
                            <? foreach ($news as $id) { ?>
                                    <? $data = blogs_posts_peer::instance()->get_item($id) ?>

		<div class="mb10 p10 box_content">
			<span class="quiet fs11"><?= date_helper::human($data['created_ts'], ', ') ?></span><br>
			<a href="/blogpost<?= $id ?>" class="bold a_left"><?= stripslashes(htmlspecialchars($data['title'])) ?></a>
		</div>
		<? } ?>
                    <? } ?>
	</div>
	<? */
        } ?>
        <?php
        /*
                   <h1 class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('left_blogposts')">
                       <span class="left"><?=t('Новые мысли')?></span>
                   </h1>
                           <div class="mb5 mt5" id="left_blogposts">
                           <? if ( $left_blogposts ) { ?>
                                   <? foreach ( $left_blogposts as $id ) { ?>
                                           <? $data = blogs_posts_peer::instance()->get_item($id) ?>

                                           <div class="mb10 p10 box_content fs12">
                                                   <span class="quiet fs11"><?=date_helper::human($data['created_ts'], ', ')?><br></span>
                                                   <a href="/blogpost<?=$id?>"  class="a_left"><?=stripslashes(htmlspecialchars($data['title']))?></a><br>
                                                  <?=user_helper::full_name($data['user_id'],true,array('class'=>'fs11 cgray'))?>
                                           </div>
                                   <? } ?>
                           <? } ?>
                           <div class="box_content p5">
                                   <a class="fs11" href="/blogs"><?=t('Все мысли')?> &rarr;</a>
                           </div>
                           </div>
           */ ?>

        <div class="clear"></div>
    </div>
    <!-- END LEFT COLUMN -->
    <?php
} ?>


<!-- START CENTER COLUMN -->
<?php
/*
   <div class="left ml10" style="width: 62%;">
       <h1 class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('ideas')">
           <span class="left" style="margin-top:3px;"><?=t('Обсуждаем идеологию')?> <?//=t('Сейчас обсуждается')?></span>
           <div class="clear"></div>
       </h1>
           <div class="left" id="ideas">
       <?
           load::model('ideas/comments');
           $counter=0;
           foreach ( $ideas as $id ) {
               $counter++;
               $idea = ideas_peer::instance()->get_item($id); ?>
                   <div id="item<?=$counter?>" class="<?=$counter>1 ? 'hide' : ''?>">
                       <? if ($counter>1) { ?><div class="left" style="margin-top:50px;"><a onclick="Application.ShowPrev('<?=$counter?>')" class="next" href="#"><img src="/static/images/common/btn_arrow_left_gray.jpg"></a></div><? } ?>
                       <? if ($counter!=count($ideas)) { ?><div class="right" style="margin-top:50px;"><a href="#" class="next" onclick="Application.ShowNext('<?=$counter?>')"><img src="/static/images/common/btn_arrow_right_gray.jpg"></a></div><? } ?>
                          <!--div class="left"><?=user_helper::photo($idea['user_id'], 's', array('class' => 'border1'))?></div-->
                     <div class="ml10 mt10" style="width: 85%;">
                           <a class="fs18" href="/idea<?=$id?>"><?=stripslashes(htmlspecialchars($idea['title']))?></a>
                           <div class="fs11 quiet mb5 mt5">
                               <div class="left mb5" style="margin-top:-5px;"><?=user_helper::full_name($idea['user_id'])?> &nbsp;<br/></div>
                               <div class="mb5" style="color:black;font-size: 12px;"><br/><?=stripslashes(htmlspecialchars($idea['anounces']))?></div>
                               <div class="mb5">
                                       <?=tag_helper::image('common/up.png', array('class' => 'vcenter','width'=>'16', 'height'=>'16','style'=>'margin-bottom:2px;'))?> <?=t('Идею поддерживают')?>: <b><?=$idea['rate']?></b>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>" class="fs11 cgray"><?=t('Читать далее')?> &rarr;</a>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>#comments" class="fs11 cgray"><?=t('Комментариев')?>: <?=ideas_comments_peer::instance()->get_count_by_idea($idea['id'])?></a>
                                       <?//=date_helper::human($idea['created_ts'], ', ')?>
                               </div>
                                   <div style="background-color: #CCCCCC;height:1px;"></div>
                          </div>
                           <!--div class="left" id="showhideall" style="position:absolute; margin-left:30%"><a href="#" class="next" onclick="Application.ShowHideAll()"><img border="0" src="/static/images/common/btn_show_all_gray.jpg"></a></div-->
                       </div>
                  </div>
       <? } ?>
           <div class="clear"></div>
       <div class="box_content p5">
           <a class="fs11" href="/ideas"><?=t('Следующие записи')?> &rarr;</a>
       </div>

       </div>
   </div>

   */ ?>
<div <?php
     if (session::is_authenticated()) { ?>class="left" style="width: 63%;" <?php } else { ?>class="mr-2 mb-2"<?php } ?>>
    <?php
    if ($attention && session::is_authenticated()) { ?>
        <div id="attention" class="left ml10" style="width: 100%;">
            <div class="column_head mt10">
                <b class="left" style="margin-top:3px;"><?= t('Важная информация') ?></b>

                <a class="right fs10 mr5" id="close_attention"
                   href="javascript:"><?= t('Закрыть окно') ?>&nbsp;<?= tag_helper::image(
                        'icons/x.png',
                        array('style' => 'cursor: pointer;')
                    ) ?></a>
            </div>
            <div class="left" style="background-color:#FAE1A8;width:100%;font-size: 13px;line-height:1.2;">
                <div
                        class="ml10 mr5 mt10"><?= session::get(
                        'language'
                    ) == 'ru' ? $attention['anounces_ru'] : $attention['anounces'] ?>
                    <div class="right mr5">
                        <a class="fs11 cgray"
                           href="/home/attention?id=<?= $attention['id'] ?>"><?= t('Читать дальше') ?> &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } /*?>
        
    <div class="left ml10" style="width: 100%;">
    	<div class="column_head mt10 mb10" style="cursor: pointer;" onclick="Application.ShowHide('popular_blogs')">
    		<b class="left" style="margin-top:3px;"><?=t('Популярные публикации')//.t('Интересная информация')?></b>
    		<a class="right" style="margin-top:3px;" rel="nofollow" href="/blogs?type=populars"><?=t('Все')?> &rarr;</a>
    	</div>
    	<div id="popular_blogs" style="width:100%">
    		<? $without_photo = true; ?>
    		<? foreach ( $pop_posts as $post_data ) { ?>
    			<? include dirname(__FILE__) . '/../../blogs/views/partials/post.php'; ?>
    		<? } ?>
    		<? $without_photo = 0; ?>
    	</div>
    </div>
    */ ?>
    <div class="left ml10" style="width: 100%;">
        <div class="column_head mt10 mb10" style="cursor: pointer;" onclick="Application.ShowHide('blogs')">
            <b class="left" style="margin-top:3px;"><?= t('Публикации')//.t('Интересная информация')       ?></b>
            <?php
            if (session::is_authenticated()) { ?>
                <a class="right" style="margin-top:3px;" rel="nofollow" href="/blogs"><?= t('Все') ?> &rarr;</a>
                <?php
            } ?>
        </div>

        <div class="left" id="blogs" style="width:100%">
            <?php
            foreach ($rated_posts as $id) { ?>
                <?php
                if (!$post_data = blogs_posts_peer::instance()->get_item($id)) {
                    continue;
                } ?>
                <?php
                include dirname(__FILE__) . '/../../blogs/views/partials/post.php'; ?>
                <?php
            } ?>
        </div>
    </div>

    <?php
    if (is_array($reports) && count($reports) > 0 && user_auth_peer::instance()->get_rights(
            session::get_user_id(),
            20
        )) { ?>
        <div class="left ml10" style="width: 100%; display: none">
            <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('reports')">
                <b class="left" style="margin-top:3px;"><?= t('Наша агитация') ?></b>
                <a class="right" style="margin-top:3px;" rel="nofollow" href="/eventreport/show"><?= t('Все') ?>
                    &rarr;</a>
            </div>
            <?php
            if (session::has_credential('admin')) { ?>
                <div
                        style="
    			background: #fff;
    			background: -moz-linear-gradient(top,  #eee,  #fff); /* Firefox 3.6+ */
    			background: -webkit-gradient(linear, left top, left bottom, from(#eee), to(#fff)); /* Webkit */
    			background: -o-linear-gradient(top,  #eee,  #fff); /* Opera 11.10+ */
    			background: -ms-linear-gradient(top,  #eee,  #fff); /* IE10+ */ 
    			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eee', endColorstr='#fff'); /* IE */
    		">
                    <?php
                    foreach ($photos_ids as $photo_id) { ?>
                        <div class="left m10">
                            <?= photo_competition_peer::photo($photo_id, 'mp', array('width' => '98')) ?>
                        </div>
                        <?php
                    } ?>
                    <div class="clear"></div>
                </div>
                <?php
            } ?>
            <div id="reports" style="width:100%">
                <?php
                foreach ($reports as $id) { ?>
                    <?php
                    if (!$post_data = eventreport_peer::instance()->get_item($id)) {
                        continue;
                    } ?>
                    <?php
                    include dirname(__FILE__) . '/../../eventreport/views/partials/post.php'; ?>
                    <?php
                } ?>
            </div>
        </div>
        <?php
    } ?>

    <?php
    if (user_auth_peer::instance()->get_rights(session::get_user_id(), 20)) { ?>
        <div class="left ml10 d-none" style="width: 100%;">
            <div class="column_head mt10 mb10" style="cursor: pointer;" onclick="Application.ShowHide('blogs_last_wr')">
                <b class="left"
                   style="margin-top:3px;"><?= t('Последние записи в блогах')//.t('Интересная информация')       ?></b>
                <a class="right" style="margin-top:3px;" href="/blogs?type=1"><?= t('Все') ?> &rarr;</a>
            </div>

            <div id="blogs_last_wr" style="width:100%">
                <?php
                foreach ($blogs_posts as $id) { ?>
                    <?php
                    if (!$post_data = blogs_posts_peer::instance()->get_item($id)) {
                        continue;
                    } ?>
                    <?php
                    include dirname(__FILE__) . '/../../blogs/views/partials/post.php'; ?>
                    <?php
                } ?>

            </div>
        </div>
        <?php
    }
    /*
   <div class="left ml10" style="width: 100%;">
       <h1 class="column_head mt10 mb10" style="cursor: pointer;" onclick="Application.ShowHide('groups_blogs')">
           <span class="left" style="margin-top:3px;"><?=t('Обсуждение в функциональных группах')//.t('Интересная информация')?></span>
       </h1>

           <div class="left" id="groups_blogs" style="width:100%">
                   <? //айдишники сообществ для вывода последних постов

                      $center_groups=array(
                           199=>t('ГРУППА ПО ИДЕОЛОГИИ'),
                           200=>t('ГРУППА ПО АГИТАЦИИ И ИНФОРМАЦИОННОЙ РАБОТЕ'),
                           201=>t('ГРУППА ПО ИЗБИРАТЕЛЬНОМУ ПРОЦЕССУ'),
                           202=>t('ГРУППА ПО ПАРТИЙНОМУ СТРОИТЕЛЬСТВУ'),
                           203=>t('ГРУППА ПО ПРОЕКТАМ И КАМПАНИЯМ')
                          );
                      foreach ($center_groups  as $center_group_id=>$title ) { ?>
                           <? if ( !$post_data = blogs_posts_peer::instance()->get_last_form_group($center_group_id)) continue; ?>
                          <div style="border: 1px solid #760006;" class="box_content mb5 bold fs12">
                                   <? if ($post_data['group_id']) {
                                           //$group=groups_peer::instance()->get_item($post_data['group_id']); ?>
                                           <a href="/group<?=$post_data['group_id']?>" class="ml10"><?=$title?></a>
                                   <? } ?>
                           </div>
                           <div class="box_content mb5">
                               <div class="left ml5" style="width: 60px;">
                                   <?=user_helper::photo($post_data['user_id'], 's', array('class' => 'mt5 border1'))?>
                               </div>
                           <div class="mt5" style="margin-left: 70px;">
                                   <!--div class="quiet fs11"><?=date_helper::human($post_data['created_ts'], ', ')?></div-->
                                   <h5 class="mb5">
                                       <a href="/blogpost<?=$post_data['id']?>" style="font-weight:normal;" class="fs18"><?=stripslashes(htmlspecialchars($post_data['title']))?></a>
                                   </h5>
                                   <div class="fs12">
                                           <div class="mb5 ml10"><?=strlen($post_data['anounces'])>6 ? stripslashes($post_data['anounces']) : tag_helper::get_short(stripslashes(strip_tags($post_data['body'])),150)?></div>
                                           <div class="p5" style="background: #F7F7F7;">
                                           <?
                                           load::model('user/user_data');
                                           $post_user = user_data_peer::instance()->get_item($post_data['user_id']);
                                           ?>
                                               <a class="fs11 mr15" href="/profile-<?=$post_data['user_id']?>"><?=$post_user['first_name'] . ' ' . $post_user['last_name']?></a>
                                                   <a href="/blogpost<?=$post_data['id']?>" class="fs11 cgray mr15"><?=t('Читать дальше')?> &rarr;</a>
                                                   <a href="/blogpost<?=$post_data['id']?>#comments" class="fs11 cgray mr15"><?=t('Комментариев')?>: <?=blogs_comments_peer::instance()->get_count_by_post($post_data['id'])?></a>
                                                   <a href="/blogpost<?=$post_data['id']?>" class="fs11 cgray"><?=t('Просмотров')?>: <?=(int)$post_data['views']?></a>
                                           </div>
                                   </div>
                           </div>
                           <div class="clear"></div>
                           </div>

                   <? } ?>
           </div>
   </div>
   */
    ?>
</div>
<?php
/*
   <div class="left ml10 mt10" style="width: 62%;background: transparent url(/static/images/common/bg-lines.png) repeat scroll 0% 0%; color:#6B0D17">
   <div class="right mt10 mb10 mr15 fs11"><a href="/people"><?=t('Все участники')?> &rarr;</a></div>
       <div style="text-transform: uppercase; font-size: 14px; font-weight: bold;" class="mt10 ml10 mb10"><?=t('Новые участники')?></div>
       <div class="left ml5">.</div>
           <? foreach ( $new_people as $id ) { ?>
               <? $user_data = user_data_peer::instance()->get_item($id) ?>
               <? $udata = user_auth_peer::instance()->get_item($id) ?>
                   <div style="width:18%; height:210px; line-height: 100%;  text-align: center; background-color: white; dislay: inline;" class="left ml10 mb10">
                     <br/>  <?=user_helper::photo($user_data['user_id'], 'r', array('class' => 'mt5 border1'))?>
                     <div class="mt5 mb5">
                       <a href="/profile-<?=$id ?>" class="fs12" style="text-decoration: none;text-align: center; "><?=$user_data['first_name'] ?><br/>
                       <?=$user_data['last_name']?></a></div>
                       <span style="color:gray;font-size: 11px;"><?=user_auth_peer::get_type($udata['type']).user_auth_peer::get_hidden_type($udata['hidden_type'],$udata['type'])?></span><br/>
                       <br/>
                   </div>
       <? } ?>
   <div></div>



   </div>
   <div class="left ml10 mt10" style="width: 97%;background: transparent url(/static/images/common/bg-lines.png) repeat scroll 0% 0%; color:#6B0D17">
       <div style="text-transform: uppercase; font-size: 14px; font-weight: bold;" class="mt10 ml10 mb10"><?=t('Известные люди')?></div>
       <div class="left ml5">.</div>
           <? foreach ( $famous_people as $id ) { ?>
               <? $user_data = user_data_peer::instance()->get_item($id) ?>
               <? $udata = user_auth_peer::instance()->get_item($id) ?>
                   <div style="width:18%;  height:210px; line-height: 100%; text-align: center; background-color: white;" class="left ml10 mb10">
                     <br/>  <?=user_helper::photo($user_data['user_id'], 'r', array('class' => 'mt5 border1'))?>
                     <div class="mt5 mb5">
                       <a href="/profile-<?=$id ?>" class="fs12" style="text-decoration: none;text-align: center; "><?=$user_data['first_name'] ?><br/>
                       <?=$user_data['last_name']?></a></div>
                       <span style="color:gray;font-size: 11px;"><?=user_auth_peer::get_type($udata['type']).user_auth_peer::get_hidden_type($udata['hidden_type'],$udata['type'])?></span><br/>
                       <br/>
                   </div>
       <? } ?>
   <div></div>


   </div>

   <script type="text/javascript">
   $(document).ready(function(){
           $('#slides1').bxSlider({
               prev_image: 'images/btn_arrow_left.jpg',
               next_image: 'images/btn_arrow_right.jpg',
               wrapper_class: 'slides1_wrap',
               margin: 70,
               auto: true,
               auto_controls: true
           });
       });
   $(document).ready(function(){
   // ---- News Slider -----
   $(".misc_news").accessNews({
       newsHeadline: "<?=t('Обсуждаем идеологию')?>",
       newsSpeed: "normal"
   });
   // ---- News Slider -----
   });
   </script>
    *  */ ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#close_attention').click(function () {
            if (confirm('Точно?')) {
                $.get('/home/hide_attention', {attention_id: '<?=$attention['id']?>'});
                $('#attention').hide();
            }
        });

        $('#close_board').click(function () {
            $('#board').hide();
        });
    });
</script>
<?php
//} ?>
