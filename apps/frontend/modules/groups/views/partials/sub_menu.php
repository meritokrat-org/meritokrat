<div class="sub_menu mt5 mb10">
	<a href="/groups" <?=($sub_menu == '/groups' && !request::get_string('req')) ? 'class="bold"' : ''?>><?=t('Все')?></a>
	<a href="/groups/new" <?=$sub_menu == '/groups/new' ? 'class="bold"' : ''?>><?=t('Новые')?></a>
	<? if ( session::is_authenticated()  && user_auth_peer::instance()->get_rights(session::get_user_id(),10) ) { ?>
            <a href="/groups/create" <?=$sub_menu == '/groups/create' ? 'class="bold"' : ''?>><b><?=t('Предложить сообщество')?></b></a>
            <a href="/groups/mine" <?=$sub_menu == '/groups/mine' ? 'class="bold"' : ''?>><?=t('Мои сообщества')?></a><span class="fs11 bold cbrown"><?=db::get_scalar("SELECT count(*) FROM groups_members WHERE user_id = :user_id AND group_id in (SELECT id FROM groups WHERE active=:active)",array('user_id'=>session::get_user_id(),'active'=>1));?></span>
        <? } ?>
        <? /* <a href="/groups/?user_id=<?=session::get_user_id()?>" <?=$sub_menu == '/groups/?user_id='.session::get_user_id() ? 'class="bold"' : ''?>><?=t('Мои сообщества')?></a> */ ?>
        <div class="right mr15">
            <form action="/groups/search" method="GET">
                <input name="req" type="text" class="text" value="<?=request::get_string('req')?>"/>
                <input name="submit" type="submit" class="button" value="<?=t('Поиск')?>"/>
            </form>
        </div>
</div>