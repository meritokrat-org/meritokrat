<style>
    th, td, caption {
        padding:1px 0 1px 1px;
    }
    input.text
    {
        width:127px;
}
.mode_pane{margin: 15px 0 10px 0;}
span.mls_left{display:block;float:left;width: 225px;text-align: left;}
span.mls_right{display:block;float:right;width: 225px;text-align: left;}
</style>
<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Списки розсилок')?></h1>

        <div class="box_content left acenter p10 fs12" style="width:100%">
<?if($send){?>
<b>Додано <?=$count?> email.</b>
<?if(!is_array($er_mails)){?>
		<? }else{?>
<br/><b>Цi email вже є в базi:</b><br/>
<?foreach($er_mails as $e){?>
<?=$e?><br/>
                    <?}}}else{  ?>
				<div class="fs11">
					<a href="javascript:;" onclick="adminController.chageMailMode('mail');" id="mode_mail" class="mail_mode bold dotted">Додати e-mail</a> &nbsp;
					<a href="javascript:;" onclick="adminController.chageMailMode('list');" id="mode_list" class="mail_mode dotted">Списки</a> &nbsp;
                                        <a href="javascript:;" onclick="adminController.chageMailMode('find');" id="mode_find" class="mail_mode dotted">Пошук</a> &nbsp;
                                </div>
                                <div id="pane_mail" class="mode_pane">
                                <form id="send_form" name="send_form" method="post">
				<input type="hidden" name="send" value="1">
				<input type="hidden" id="mail_mode" name="mail_mode" value="add_mail">
                                    <div class="mlst"><?
                                    load::model('mailing');
                                    $lists=mailing_peer::instance()->get_maillists();
                                    foreach($lists as $k=>$l){
                                    ?>
<span class="mls_<?=($i==1)?'right':'left'?>"><input type="checkbox" name="lists[]" value="<?=$k?>"/><?=$l?> (<?=mailing_peer::instance()->get_maillists_users_count($k)?>)</span>
                                    <?if($i==1)$i=0;else$i++;}?>
                                    </div>
                                <div class="clear">
				<table class="fs11">
					<tr>
						<th>Ім'я</th>
                                                <th>Призвiще</th>
						<th>Email</th>
					</tr>
					<tr class="maillist_item">
						<td><input type="text" class="text" name="first_name[]" /></td>
                                                <td><input type="text" class="text" name="last_name[]" /></td>
						<td>
							<input type="text" class="text" name="email[]" />
							<input type="button" class="button" value="+" onclick="adminController.maillistAdd(this);" />
							<input type="button" class="button_gray" value="&nbsp;-&nbsp;" onclick="adminController.maillistRemove(this);" />
						</td>
					</tr>
                                </table></div>
                                <input type="submit" class="button" value="<?=t('Добавить')?>" />
                                </form>
                                </div>
<div id="pane_list" class="mode_pane hidden">
    <?if(!request::get_int('id')){ ?>
 <div class="mlst"><?
                                    foreach($lists as $k=>$l){
                                    ?>
<span class="mls_<?=($i==1)?'right':'left'?>"><a href="/admin/editlists?id=<?=$k?>"><?=$l?></a>&nbsp;<a href="javascript:deleteList('<?=$k?>');" onclick="return confirm('<?=t('Удалить')?> <?=$l?>?');">[x]</a></span>
                                    <?if($i==1)$i=0;else$i++;}?>
</div>
 <div class="clear">
				<table class="fs11">
					<tr>
						<th>Назва</th>
					</tr>
					<tr class="maillist_item">
                                            <td><input type="text" class="text" style="width:380px;" id="listname" />&nbsp;<input type="button" id="adlstbut" onclick="addList('');" class="button" value="Додати" /></td>
					</tr>
                                </table></div>
    <?}else{?>
    <script type="text/javascript">
    $(function(){
 adminController.chageMailMode('list');
    });
</script>
    <h2><a href="/admin/editlists?mode=list">Списки</a> / <?=$listname?></h2>
    <?foreach ( $users as $u ) { ?>
    <span class="mls_<?=($i==1)?'right':'left'?>"><a href="/admin/editlists?id=<?=request::get_int('id')?>&findmail=<?=$u['email']?>&mail_mode=find_mail"><?=$u['email']?></a>&nbsp;<a href="/admin/editlists?id=<?=request::get_int('id')?>&deluser=<?=$u['id']?>" onclick="return confirm('<?=t('Удалить')?> <?=$u['email']?>?');">[x]</a></span>
             <? }?>
<div class="clear right pager"><?=pager_helper::get_full($pager)?></div>
 <?}?>
</div>
<?if(request::get('mail_mode') != 'find_mail' || $error==1){ ?>
<?if($error==1){?>
<script type="text/javascript">
    $(function(){
 adminController.chageMailMode('find');
    });
</script>
<div id="error">Нiчого не знайдено.</div><?}?>
<div id="pane_find" class="mode_pane hidden">
                                    <form id="find_form" name="find_form" method="post">
				<input type="hidden" name="send" value="1">
				<input type="hidden" id="mail_mode" name="mail_mode" value="find_mail">
<table class="fs11">
					<tr>
						<th>E-mail</th>
					</tr>
					<tr class="maillist_item">
                                            <td><input type="text" class="text" style="width:380px;" name="findmail" />&nbsp;<input type="submit" class="button" value="Редагувати" /></td>
					</tr>
</table></form>
</div>
<?}else{?>
<script type="text/javascript">
    $(function(){
 adminController.chageMailMode('find');
    });
</script>
<div id="pane_find" class="mode_pane">
                                    <form id="find_form" name="find_form" method="post">
				<input type="hidden" name="send" value="1">
				<input type="hidden" id="mail_mode" name="mail_mode" value="save_mail">
                                <input type="hidden" name="mail_userid" value="<?=$mail['id']?>">
                                                                    <div class="mlst"><?
                                    load::model('mailing');
                                    $lists=mailing_peer::instance()->get_maillists();
                                    foreach($lists as $k=>$l){
                                    ?>
<span class="mls_<?=($i==1)?'right':'left'?>"><input type="checkbox" <?=(in_array($k, $user_lists))?'checked':''?> name="userlists[]" value="<?=$k?>"/><?=$l?></span>
                                    <?if($i==1)$i=0;else$i++;}?>
                                    </div>
                                <div class="clear">
<table class="fs11">
					<tr>
						<th>Ім'я</th>
                                                <th>Призвiще</th>
					</tr>
					<tr >
                                            <td><input style="width: 220px" type="text" class="text" value="<?=$mail['first_name']?>" name="first_name" /></td>
                                            <td><input style="width: 220px" type="text" class="text" value="<?=$mail['last_name']?>" name="last_name" /></td></tr>
                                        <tr><td colspan="2" align="left"><b>
                                                    Black List<input <?=($mail['blacklisted']==1)?'checked':''?> value="1" type="checkbox" name="blacklisted"/></b>
						</td>
                                        <tr><td colspan="2">
<input type="submit" class="button" value="зберегти" />
						</td>
					</tr>
</table></div></form>
</div>
<?} } ?>
	</div>
</div>
<?if(request::get('mode')){ ?>
<script type="text/javascript">
    $(function(){
 adminController.chageMailMode('<?=request::get('mode')?>');
    });
</script>
<?}?>
<script type="text/javascript">
function deleteList(id)
{ 
  var urlt = "/admin/editlists";
		$.post(urlt,{"list":id},
			function (result) {
$(location).attr('href','');
                        });
}
function addList()
{
  var urlt = "/admin/editlists";
$.post(urlt,{"listname":$('#listname').val()},
			function (result) {
$(location).attr('href','');
                        });
}
</script>
