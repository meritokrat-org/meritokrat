<? //if(session::is_authenticated()) include 'partials/sub_menu.php' ?>
<!--h1 class="cbrown mb10 mt10" style="font-size:18px">
    <a href="/blogs/programs">Проект "<?=t('Успішна Україна')?>"</a>
    <? if(request::get('theme')=='mpu'){ ?>
    &rarr;&nbsp;<b style="font-size:20px"><?=t('Идеи для Идеальной Страны')?></b>
    <? }elseif(request::get('theme')=='position'){ ?>
    &rarr;&nbsp;<b style="font-size:20px"><?=t('Позиция')?></b>
    <? }elseif(request::get_int('theme')){ ?>
    &rarr;&nbsp;<b style="font-size:20px"><?=user_helper::get_program_types(request::get_int('theme'))?></b>
    <? } ?>
</h1-->

<? /*if(!request::get('theme') && !request::get('target')){ ?>
<div class="mb15 fs12 cgray">
    <p>Цей проект створений для систематизації цікавих ідей та напрацювання образу Ідеальної Країни. Долучайтесь до обговорення! Якщо Ви знаєте цікаві матеріали, статті, ідеі щодо будь-яких з перелічених ліворуч тем, розміщуйте іх у своїх блогах. Якщо розміщені Вами матеріали будуть цікавими, модератори перенесуть іх у цей розділ, щоб з ними могли ознайомитися якомога більше учасників мережі. Великі матеріали (книги, фільми або лінки на них) надсилайте на адресу <a href="mailto:idea@meritokrat.org">idea@meritokrat.org</a></p>
</div>
<? }*/ ?>

<div class="left mt10" style="width: 35%;"><? include 'partials/program_left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">

    <h1 class="column_head" style="height: auto; padding: 5px 15px"><a href="/blogs/programs">Проект "<?=t('Успішна Україна')?>"</a></h1>
    <div class="box_content mb5 p10 fs18 cbrown acenter" style="background-color:#fbf4e1;text-transform:uppercase;text-decoration:none">
        <? if(request::get('theme')=='mpu'){ ?>
        <?=t('Идеи для Идеальной Страны')?>
        <? }elseif(request::get('theme')=='position'){ ?>
        <?=t('Позиция')?>
        <? }elseif(request::get_int('theme')){ ?>
        <?=user_helper::get_program_types(request::get_int('theme'))?>
        <? }else{ ?>
        <p class="fs12" style="color:black;margin-bottom:0;text-transform:none;text-align:left">Цей проект створений для систематизації цікавих ідей та напрацювання образу Ідеальної Країни. Долучайтесь до обговорення! Якщо Ви знаєте цікаві матеріали, статті, ідеі щодо будь-яких з перелічених ліворуч тем, розміщуйте іх у своїх блогах. Якщо розміщені Вами матеріали будуть цікавими, модератори перенесуть іх у цей розділ, щоб з ними могли ознайомитися якомога більше учасників мережі. Великі матеріали (книги, фільми або лінки на них) надсилайте на адресу <a href="mailto:idea@meritokrat.org">idea@meritokrat.org</a></p>
        <? } ?>
    </div>

    <? if(request::get('theme')=='mpu' || request::get('theme')=='position' || request::get_int('theme') || request::get_int('target')){ ?>

        <? if(request::get('theme')!='mpu' && request::get('theme')!='position' && !request::get_int('page')){ ?>
            <? if(count($position)>0){ ?>
                <h1 class="column_head" style="height: auto; padding: 5px 15px"><a href="/blogs/programs&theme=position"><?=t('Позиция')?></a></h1>
                <? foreach ( $position as $id ) { ?>
                        <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                        <? include 'partials/program_post.php'; ?>
                <? } ?>
            <? } ?>
            <? if(count($mpu)>0){ ?>
                <h1 class="column_head" style="height: auto; padding: 5px 15px"><a href="/blogs/programs&theme=mpu"><?=t('Идеи для Идеальной Страны')?></a></h1>
                <? foreach ( $mpu as $id ) { ?>
                        <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                        <? include 'partials/program_post.php'; ?>
                <? } ?>
            <? } ?>
        <? } ?>

        <? if(request::get('theme')=='mpu'){ ?>
            <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Идеи для Идеальной Страны')?><a href="/blogs/programs" class="right"><?=t('Все')?></a></h1>
        <? }elseif(request::get('theme')=='position'){ ?>
            <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Позиция')?><a href="/blogs/programs" class="right" style="text-transform: none; color: white"><?=t('Все')?></a></h1>
        <? }elseif(request::get_int('target')){ ?>
            <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('По целевой группе')?> &rarr; <?=user_helper::get_target_groups(request::get_int('target'))?><a href="/blogs/programs" class="right" style="text-transform: none; color: white"><?=t('Все')?></a></h1>
        <? }else{ ?>
            <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Статьи')?><a href="/blogs/programs" class="right" style="text-transform: none; color: white"><?=t('Все')?></a></h1>
        <? } ?>
        <? if(count($list)>0){ ?>
            <? foreach ( $list as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? } ?>
	<div class="right pager"><?=pager_helper::get_short($pager)?></div>

        
        <? if($group_id && !request::get_int('page') && count($group)>0){ ?>
            <h1 class="column_head mb5">
                <a href="/group<?=$group_id?>"><?=t('Рабочая группа')?></a>
            </h1>
            <div class="box_content p10 fs16 mb5">
                <a href="/group<?=$group_id?>"><?=user_helper::get_program_types($group_data['type'])?></a>
            </div>
            <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Обсуждение в рабочей группе')?></h1>
            <? if(count($group)>0){ ?>
                <? foreach ( $group as $id ) { ?>
                        <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                        <? include 'partials/program_post.php'; ?>
                <? } ?>
            <? }else{ ?>
                <div class="fs12 p10" style="text-align:center;color:grey;">
                    <?=t('Не найдено ни одной публикации')?>.
                </div>
            <? } ?>
            <a href="/group<?=$group_id?>" class="fs12 quiet"><?=t('На страницу рабочей группы')?> &rArr;</a>
         <? } ?>

    <? }elseif(request::get('type')=='populars'){ ?>

        <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Популярные статьи')?><a href="/blogs/programs" class="right"><?=t('Все')?></a></h1>
        <? if(count($list)>0){ ?>
            <? foreach ( $list as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? } ?>

    <? }elseif(request::get('type')=='last'){ ?>

        <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Последние статьи')?><a href="/blogs/programs" class="right"><?=t('Все')?></a></h1>
        <? if(count($list)>0){ ?>
            <? foreach ( $list as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? } ?>

    <? }else{ ?>

        <? if(count($position)>0){ ?>
        <h1 class="column_head" style="height: auto; padding: 5px 15px"><a href="/blogs/programs&theme=position"><?=t('Позиция')?></a><a href="/blogs/programs&theme=position" class="right"><?=t('Все')?></a></h1>
        <? if(count($position)>0){ ?>
            <? foreach ( $position as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? }} ?>

        <? if(count($list)>0){ ?>
        <h1 class="column_head" style="height: auto; padding: 5px 15px"><a href="/blogs/programs&theme=mpu"><?=t('Идеи для Идеальной Страны')?></a><a href="/blogs/programs&theme=mpu" class="right"><?=t('Все')?></a></h1>
        <? if(count($list)>0){ ?>
            <? foreach ( $list as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? }} ?>

        <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Популярные статьи')?><a href="/blogs/programs?type=populars" class="right"><?=t('Все')?></a></h1>
        <? if(count($popular)>0){ ?>
            <? foreach ( $popular as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? } ?>

        <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Последние статьи')?><a href="/blogs/programs&type=last" class="right"><?=t('Все')?></a></h1>
        <? if(count($last)>0){ ?>
            <? foreach ( $last as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? } ?>

        <? if(count($group)>0){ ?>
        <h1 class="column_head" style="height: auto; padding: 5px 15px"><?=t('Обсуждения в рабочих группах')?></h1>
        <? if(count($group)>0){ ?>
            <? foreach ( $group as $id ) { ?>
                    <? if ( !$post_data = blogs_posts_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/program_post.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>.
            </div>
        <? } ?>
        <a href="/groups/index?category=2" class="fs12 quiet"><?=t('К списку рабочих групп')?> &rArr;</a>
        <? } ?>

    <? } ?>

</div>
