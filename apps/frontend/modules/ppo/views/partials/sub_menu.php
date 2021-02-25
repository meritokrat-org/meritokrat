<div class="sub_menu mt5 mb10">
	<a href="/ppo" <?=($sub_menu == '/ppo' && !request::get_string('req')) ? 'class="bold"' : ''?>><?=t('Все')?></a>
	<a href="/ppo/new" <?=$sub_menu == '/ppo/new' ? 'class="bold"' : ''?>><?=t('Новые')?></a>
	<? if ($allow_create) { ?>
        <a href="/ppo/create" <?=$sub_menu == '/ppo/create' ? 'class="bold"' : ''?>><b><?=t('Создать')?> ППО</b></a>
        <? } ?>
        <?$is_memeber=db::get_scalar("SELECT count(*) 
                FROM ppo_members 
                WHERE user_id = :user_id 
                AND group_id in (SELECT id FROM ppo WHERE active=:active)",array('user_id'=>session::get_user_id(),'active'=>1));
        if($is_memeber){
 $ppo=db::get_row("SELECT id,number FROM ppo WHERE active=:active AND category=:category
            AND id in(SELECT group_id FROM ppo_members WHERE user_id = :user_id)",array('user_id'=>session::get_user_id(),
                'active'=>1,'category'=>1));           
            ?>
        <a href="/ppo<?=$ppo['id']?>/<?=$ppo['number']?>"><?=t('Моя партийная организация')?></a>
        <?}?>
        <div class="right mr15">
            <form action="/ppo/search" method="GET">
                <input name="req" type="text" class="text" value="<?=request::get_string('req')?>"/>
                <input name="submit" type="submit" class="button" value="<?=t('Поиск')?>"/>
            </form>
        </div>
</div>