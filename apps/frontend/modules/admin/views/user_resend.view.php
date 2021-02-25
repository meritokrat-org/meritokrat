<div class="popup_header">
	<h3 class="mb5"><?=t('Ошибка')?></h3>
</div>

<div class="m10">
    <div class="fs11 mb10">
        <?=t('Невозможно отправить письмо на текущий email.<br/> Перейдите в профиль и проверьте коррекность<br/> адреса электронной почты')?>
    </div>
    <input type="button" class="button" id="goprofile" value=" <?=t('Перейти в профиль')?> ">
    <input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('Закрыть')?> ">
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#goprofile').click(function(){
            window.location = 'http://<?=context::get('server')?>/profile/edit?id=<?=intval($user)?>&tab=settings';
        });
    });
</script>