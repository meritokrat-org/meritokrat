<div class="popup_header">
	<h3 class="mb5"><?=t('Ошибка')?></h3>
</div>

<div class="m10">
    <div class="fs11 mb10" id="popup_content_box"></div>
    <input type="button" class="button_gray" onclick="Popup.close();" value="Закрити">
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#popup_content_box').html(context.obj_id);
    });
</script>