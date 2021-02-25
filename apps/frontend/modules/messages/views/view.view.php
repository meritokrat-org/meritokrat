<h1 class="column_head mr10 mt10"><a href="/messages"><?=t('Мои сообщения')?></a> &rarr; <?=request::get_int('sender_id') ? t('История сообщений') : t('Просмотр сообщения')?></h1>

<div>
	<div class="aright fs11 mr10 mb10 box_content p5">
		<? if ( $thread['sender_id'] != session::get_user_id() and !request::get_int('sender_id') ) { ?>
			<a href="/messages/delete?id=<?=$thread['id']?>&spam=1" class="quiet"><?=t('Это спам')?></a>
		<? } ?>
		<? if ( !request::get_int('sender_id') ) { ?>
                        <a href="/messages/compose?resend_all=<?=$thread['id']?>" class="ml10 maroon"><?=t('Переслать')?></a>
                        <a href="/messages/delete?id=<?=$thread['id']?>" class="ml10 maroon"><?=t('Удалить')?></a>
                <? }else{ ?>
                        <a href="/messages/compose?resend_history=<?=request::get_int('sender_id')?>" class="ml10 maroon"><?=t('Переслать')?></a>
                <? } ?>
	</div>
	<div id="messages"><? foreach ( $list as $message ) { include 'partials/message.php'; } ?></div>

	<div class="form_bg"><form id="reply_form" name="reply_form" action="/messages/reply" class="m10">
		<h3 class="column_head_small"><?=t('Написать сообщение')?></h3>
                <input type="hidden" name="thread_id" value="<?=$thread['id']?>"/>
                <input type="hidden" name="sender_id" value="<?=request::get_int('sender_id')?>"/>
		<textarea rel="<?=t('Напишите текст сообщения')?>" style="width: 99%;" name="body" class="form-control"></textarea>
                <table  style="width: 500px;">
                                        <tr>
                                            <? $smiles=messages_peer::instance()->get_smiles();
                                            foreach ($smiles as $code=>$value) { ?>
                                            <td style="cursor: pointer;" onclick='InsertSmile("<?=$code?>")'><?=$value?></td>
                                            <? } ?>
                                        </tr>
                 </table>
		<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
		<?=tag_helper::wait_panel()?>
	</form></div>
	
</div>

<script language="javascript" type="text/javascript">
<!--
var ie=document.all?1:0;
var ns=document.getElementById&&!document.all?1:0;

function InsertSmile(SmileId)
{
    if(ie)
    {
    document.all.body.focus();
    document.all.body.value+=" "+SmileId+" ";
    }

    else if(ns)
    {
    document.forms['reply_form'].elements['body'].focus();
    document.forms['reply_form'].elements['body'].value+=" "+SmileId+" ";
    }
}
// -->
</script>