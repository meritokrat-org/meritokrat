<?
    $msg = array(
                    '10'=>'Прошу рекомендувати мене в "Мерітократы"',
                    '20'=>'Прошу рекомендувати мене в члени МПУ'
                );
?>
<div class="popup_header" rel="<?=t('Спасибо, Ваше сообщение было отправлено!')?>">
	<h2><?=t('Получить рекомендацию')?></h2>
</div>

<form rel="<?=t('Выберите получателей сообщения')?>" id="share_form" action="/help/send_message" method="post" onsubmit="return Application.doRecommendation();">

    <div class="m10 fs11 aleft" style="width: 550px;">
        <div class="left">
            <div id="friends_checker">
            </div>
                    <input type="hidden" name="uid" value="<?=$uid?>">
                    <?
                        if($status>=status_helper::MPU_MEMBER && status_helper::get_status(session::get_user_id())<status_helper::MERITOCRAT) {
                            $message = 'Прошу рекомендувати мене у "Мерітократи"';
                    ?>
                    <input type="radio" checked name="ustat[]" value="<?=  status_helper::MERITOCRAT?>"><span><?=t('в меритократы')?></span>
                        <input type="radio" name="ustat[]" value="<?=  status_helper::MPU_MEMBER?>"><span><?=t('в члены МПУ')?></span>
                    <? }elseif($status>=status_helper::MPU_MEMBER && status_helper::check_status(session::get_user_id(),status_helper::MERITOCRAT)) { 
                        $message = 'Прошу рекомендувати мене у члени МПУ';
                    ?>
                        <input type="hidden" name="ustat" value="<?=$status?>">
                    <? }elseif($status>=status_helper::MERITOCRAT && $status<status_helper::MPU_MEMBER) { 
                        $message = 'Прошу рекомендувати мене у "Мерітократи"';
                        ?>
                        <input type="hidden" name="ustat" value="<?=$status?>">
                    <? } ?>
                        
                    <textarea name="message" style="height: 100px; width: 545px;"><?=$message?> <?=$name?></textarea>

                    <div class="mt10">
                            <input type="submit" class="button" value=" <?=t('Отправить')?> ">
                            <input type="button" class="button_gray" onclick="Application.invitedFriends=[];Popup.close();" value=" <?=t('Отмена')?> ">
                            <? if (session::has_credential('admin') && $item_type==2) { ?>
                                    <input type="button"  name="jusers" id="jusers" class="button" value=" Приєднати " onclick="return Application.dotoGroup();">
                                    <!--a id="join_users" class="button ml15" style="padding:2px;"> Приєднати </a-->
                            <? } ?>
                    </div>
        </div>
        
        <div class="clear pb5"></div>
    </div>
</form>
<script>
    $msg = {'10':'Прошу рекомендувати мене у "Мерітократи"','20':'Прошу рекомендувати мене у члени МПУ'};
    $('input[type="radio"]').change(function (){
        $('textarea').val($msg[$(this).val()]);
    })
</script>