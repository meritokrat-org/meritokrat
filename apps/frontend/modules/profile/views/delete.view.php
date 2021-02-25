<div class="popup_header">
	<h3 class="mb5"><?=t('Удаление профиля')?></h3>
</div>

<div class="m10">
    <div class="fs11 mb10">
    <? if (session::has_credential('admin')) { ?>
        <?=t('Укажите причину удаления')?>:
        <br/>
        <input type="text" class="text" id="why" />
    <? }else{ ?>
        <input type="hidden" id="why" value="selfdelete"/>
    <? } ?>
    </div>
    <input type="button" class="button" id="delsubm" value=" <?=t('Да, удалить')?> ">
    <input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('Нет')?> ">
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#delsubm').click(function(){
            //var passwd = $('#passwd').val();
            var why = $('#why').val();
						//alert(passwd+"; "+why);
            //$.post('/profile/check_password',{'password':passwd},function(data){
                //if(data=='ok'){
                    if(why != ''){
                        document.location='/profile/delete_process?hash=<?=$hash?>&why='+why+'&type=<?=$type?>';
                    }else{
                        alert('<?=t('Укажите причину удаления')?>');
                    }
                //}else{
                //    alert('<?=t('Неправильный пароль')?>');
                //}
            //});
        });
    });
</script>