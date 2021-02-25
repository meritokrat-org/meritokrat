<div class="error mt10 left">
    <div class="left" style="width:710px">
        <h2 class="error mt10"><?='404 '.t('Страничка')?> <?=$error_message ? ': ' . $error_message : ''?></h2>
    </div>
    <div class="left" style="width:22px">
        <? if($error_help){ ?>
        <a onclick="Application.showInfo('<?=$error_help?>');" href="#">
            <img src="/static/images/icons/info.png" />
        </a>
        <? } ?>
    </div>
</div>

<div class="line_light"></div>

<br />

<? if ( ! $error_message ) { ?>
	<p><?=t('Документ, который Вы запрашиваете, не найден.<br/>Возможно, ошиблись с вводом ссылки или документ перенесли в другой раздел')?>.</p>
	<p><?=t('Воспользуйтесь поиском по сайту или перейдите на <a href="https://meritokrat.org">главую страничку</a>')?>.</p>
	<p><?=t('Если у Вас возникли дополнительные вопросы')?>, <a href="/messages/compose?user_id=10599"><?=t('напишите нам')?></a>.</p>
<? } ?>

<br /><br />