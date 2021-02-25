<style>
    li b {
        color: #660000;
    }

	#sort_buffer {
		min-height: 30px;
		list-style: none;
	}
</style>

<? if(session::has_credential('admin')){ ?>
	<div style="position: fixed; width: 266px;  box-shadow: 0 0 15px rgba(0, 0, 0, .5)" class="mt10">
		<div class="column_head" style="cursor: pointer;" onclick="Application.ShowHide('sort')">
			<div class="left">*<?=t('Сортировка')?></div>
			<div class="right mt5 icoupicon <?=($cur_status>0 || request::get_int('meritokrat') || request::get_int('expert') || request::get('offline'))?'':'hide'?>" style="cursor: pointer;" id="sort_on"></div>
			<div class="right mt5 icodownt <?=($cur_status>0 || request::get_int('meritokrat') || request::get_int('expert') || request::get('offline'))?'hide':''?>" style="cursor: pointer;" id="sort_off"></div>
		</div>
		<div class="p10 box_content" id="sort">
			<ul class="connectedSortable mb5" id="sort_buffer">
				<? foreach($sortable_list as $user){ ?>
					<li data-user-id='<?=$user?>'><?=user_helper::full_name($user, true, array('class'=>'bold'))?><a style="float: right; cursor: pointer" class="del_sort" data-user-id='<?=$user?>'>X</a></li>
				<? } ?>
			</ul>
		</div>
	</div>
	<div style="width: 266px;" class="mt10"></div>
<? } ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.areg').click(function(){
            $('.areg').removeClass('bold');
            var id = this.id;
            $(this).hide();
            if (id=='az') {
                $('#za').show(); 
                $('#za').addClass('bold');               
            } else if (id=='za') {
                $('#az').show();
                $('#az').addClass('bold');                
            } else if (id=='unrate') {
                $('#rate').show();
                $('#rate').addClass('bold');                
            } else if (id=='rate') {
                $('#unrate').show();
                $('#unrate').addClass('bold');
            }
            
            $('.dreg').hide();
            $('#ul_'+id).show();
        });
    });
</script>
