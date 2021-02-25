<div class="left ml10"  style="width:98%">
    <h1 class="column_head mt10">
        <a href="/people/not_viewed?mode=new" style="<?=request::get('mode')=='new' ? '' : 'color:white;'?>"><?=t('Новые пользователи')?> <?=$new_users_count?> </a> &nbsp;&nbsp;| &nbsp;&nbsp;
        <a href="/people/not_viewed" style="<?=request::get('mode')=='new' ? 'color:white;' : ''?>"><?=t('Пользователи з доп.инфо')?> <?=$additional_count?></a>
    </h1>
	<? if ( !$users) { ?>
		<div class="acenter fs11 quiet m10 p10"><?=t('Новых пользователей еще нет')?></div>
	<? } else { ?>
        <div style="width:200px; font-size: 90%;" class="ml10 left mt10 mr5">
			<? foreach ( $users as $id ) { ?>
                                <? include dirname(__FILE__) . '/../../search/views/partials/outlook.php' ?>
			<? } ?>
		</div>
		<div style="width:500px; font-size: 80%;" class="ml10 left mr10">
                <table width="60%" height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="middle" align="center" id="user_outlook_data">
					<- <?=t('выберите участника')?>
				</td>
			</tr>
		</table>
		</div>
		<div class="clear"></div>
		<div class="right mr10 pager"><?=pager_helper::get_full($pager)?></div>
	<? } ?>

</div>
<script type="text/javascript"> 
jQuery(document).ready(function($){
$('.outlook_user').click(function(){
    var id = $(this).attr('rel');
    $('#user_outlook_data').html("<img src='https://s1.meritokrat.org/common/loaging.gif' style='margin-top:200px;'>");
    $('.outlook_user').removeClass('lgray');
    $(this).addClass('lgray');
   $.get('/people/one_not_viewed', { id: id },
            function(data) {
            $('#user_outlook_data').html(data);
            }); 
});
});
</script>