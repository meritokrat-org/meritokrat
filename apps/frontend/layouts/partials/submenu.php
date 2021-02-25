<div class="submenu" style="font-size: 12px">
    <?php if (session::is_authenticated()) { ?>
        <div class="left"><a href="/profile/desktop?id=<?= session::get_user_id() ?>"
                             class="dib icoprofile"></a></div>
        <a href="/profile/desktop?id=<?= session::get_user_id() ?>"
           class="ml5 left"><?= t('Рабочий стол') ?></a>
        <?php if (session::is_authenticated() && session::has_credential("admin")) { ?>
            <div class="left mlx"><a href="/admin/rating"><img src="/static/images/icons/rating_icon.png"
                                                               style="width: 20px; height: 20px;"></a></div>
            <a href="/admin/rating" class="ml5 left"><?= t('Рейтинги') ?></a>
            <div class="left mlx"><a href="/results" class="dib icolinegraph"></a></div>
            <a href="/results" rel="nofollow" class="ml5 left"><?= t('Результаты') ?></a>
        <?php } ?>
        <div class="left mlx"><a href="/groups" class="dib icopeople"></a></div>
        <a href="/groups" class="ml5 left"><?= t('Сообщества') ?></a>
        <div class="left mlx"><a href="/polls" class="dib icoopros"></a></div>
        <a href="/polls" class="ml5 left"><?= t('Опросы') ?></a>
        <div class="left mlx"><a href="/search" class="dib icosearch"></a></div>
        <a href="/search" rel="nofollow" class="ml5 left"><?= t('Поиск людей') ?></a>
    <?php } ?>
    <form id="blogsearch" action="/blogs" method="post">
        <input type="text" name="fast" class="text left" style="width: 150px; border: 1px solid #999999"
               value="<?= request::get('fast'); ?>">
        <input type="hidden" name="submit" class="text left" value="1">
        <input type="button" class="ml10 left icosearch" style="border:none;"
               onClick="$('#blogsearch').submit();">
    </form>
</div>