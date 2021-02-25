<div style="text-align: center; margin-top: -5px;">
    <?php if (session::has_credential('superadmin') || session::has_credential('programmer')) { ?>
        <? # if (session::get_user_id()>0) { ?>
        <a href="/help/index?rulezz" style="color:#cc6666;"><?= t('Правила та модерация') ?></a>
        <!--<a href="/help/index?moderation" style="margin-left:50px;color:#cc6666;"><?= t('Модерация') ?></a>-->
        <a href="https://meritokrat.org/messages/compose?user_id=10599" style="margin-left:50px;color:#cc6666;"><?= t(
                'Обратная связь'
            ) ?></a>
        <!--        <a href="--><? //=(session::is_authenticated() ? 'https://meritokrat.org/messages/compose?user_id=10599' : 'https://shevchenko.ua/ua/feedback/')?><!--" style="margin-left:50px;color:#cc6666;">--><? //=t('Обратная связь')?><!--</a>-->
        <a href="/help/index?looking_for_advice" style="margin-left:50px;color:#cc6666;"><?= t('Возник вопрос') ?>?</a>
        <!--	<a href="/help/index?agitation" rel="nofollow" style="margin-left:50px;color:#cc6666;"><?= t(
            'Агитматериалы'
        ) ?></a>
        <a href="https://shevchenko.ua/ua/team/support/" style="margin-left:50px;color:#cc6666;"><?= t(
            'Нам помогают'
        ) ?></a>-->
        <? #} ?>
    <?php } ?>
</div>

<div style="display: none; float:left;color:#994444;line-height:120%; margin-left: 120px; width: 250px; margin-top: 38px; position: absolute; text-align: right;">

    <? # if (session::get_user_id()>0) { ?>
    &copy; <?= t('Меритократическая Украина') ?></b><br/> 2010-<?= date('Y', time()) ?>
    <br><br>
    <? //=t('Социальная сеть "Меритократ" есть независимым частным проектом и не имеет юридического отношения к Меритократической партии Украины')?>
    <br>


</div>

<div style="display: none; float:center;color:#994444;margin-left:640px;margin-top:36px; position: absolute; "><?= t(
        'Программирование'
    ) ?> -
    <a href="/profile-29" style="color:#994444;"><?= t('Андрей Димов') ?></a>,
    <a href="/profile-1360" style="color:#994444;"><?= t('Андрій Зуєв') ?></a>,<br/>
    <a href="/profile-1337" style="color:#994444;"><?= t('Артем Леженко') ?></a>,
    <a href="/profile-5968" style="color:#994444;"><?= t("Антон Тарасенко") ?></a>,
    <a href="/profile-11752" style="color:#994444;"><?= t('Артем Морозов') ?></a>
    <br/>
    <?= t('Дизайн') ?> - <a href="https://kostukova.com" style="color:#994444;"><?= t('Юлия Костюкова') ?></a>

    <? #} ?>
</div>
<div class="clear"></div>
