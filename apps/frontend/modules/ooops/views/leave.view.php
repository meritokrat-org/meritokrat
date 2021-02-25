<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?=client_helper::get_title()?>
    <?=client_helper::get_meta()?>
    <?=tag_helper::css('system.css') ?>
    <link REL="SHORTCUT ICON" HREF="/favicon.ico">
</head>

<body style="background-color:white;">

<div style="background-color: white;border: 16px solid #F9E5B2;color: #000000;width: 480px;min-height: 190px;padding: 45px;position: relative; margin: auto;margin-top: 100px;">
<div id="showinfoboxtitle">
    <?=t('Вы покидаете сайт по внешней ссылке')?>
</div>
<div id="showinfoboxdata">
    <p><?=t('Вы действительно хотите покинуть сайт и перейти по внешней ссылке')?> <a class="outercontainer" href="<?=$href?>"><?=$href?></a>?</p>
</div>
<div id="showinfoboxbuttons">
    <p><a href="<?=$href?>" class="button mr15"><?=t('Перейти')?></a><a href="<?=$_SERVER['HTTP_REFERER']?>" class="button_gray"><?=t('Вернуться на сайт')?></a></p>
</div>
</div>

</body>
</html>