<div class="left ml10 mt10" style="width: 96%;">
	<h1 class="column_head"><?=t('Список пользователей c новой системы')?></h1>

	<div class="box_content p5 fs12 mb10">

		<div class="acenter m10">
			<form action="/admin/novasys_base">
                        <table>
			<tr>
				<td class="aright"><?=t('id в меритократе')?></td>
				<td>
					<input name="user_id" style="width:194px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars(request::get_int('user_id',0)))?>" />
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('id в ns')?></td>
				<td>
					<input name="novasys_id" style="width:194px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars(request::get_int('novasys_id',0)))?>" />
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Имя')?></td>
				<td>
					<input name="name" style="width:194px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars(request::get_string('name')))?>" />
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Фамилия')?></td>
				<td>
                                        <input name="last_name" style="width:194px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars(request::get_string('last_name')))?>" />
				</td>
			</tr>
			<tr>
				<td class="aright">E-mail</td>
				<td>
                                        <input name="email" style="width:194px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars(request::get_string('email')))?>" />
				</td>
			</tr>
			<tr>
				<td class="aright"></td>
				<td>
                                        <input name="email" style="width:194px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars(request::get_string('email')))?>" />
				</td>
			</tr>
			<tr>
				<td class="aright"></td>
				<td>
        				<input type="submit" class="button" value="<?=t('Искать')?>" />
				</td>
			</tr>
                        </table>
			</form>
		</div>

		<table>
			<tr>
				<th style="color:black;"><a href="<?=$_SERVER['REQUEST_URI']?>&sort=novasys_id">Novasys_id</a></th>
                                <th style="color:black;"><a href="<?=$_SERVER['REQUEST_URI']?>&sort=id">ID</a></th>
                                <th style="color:black;"><a href="<?=$_SERVER['REQUEST_URI']?>&sort=name">Ім’я</a></th> 
				<th style="color:black;"><a href="<?=$_SERVER['REQUEST_URI']?>&sort=lname">Прізвище</a></th>
				<th style="color:black;"><a href="<?=$_SERVER['REQUEST_URI']?>&sort=email0">E-mail</a></th>
				<th style="color:black;">DEL</th>
				<!--th style="color:black;">Данные meritokrat.org</th-->
			</tr>
			<? $offset=0;
                        foreach ( $list as $id ) { 
                            if ($id>0) 
                                        {
                                            $ns_data= user_novasys_data_peer::instance()->get_item($id);
                                            $user_data = user_data_peer::instance()->get_item($id);
                                            $user = user_auth_peer::instance()->get_item($id); 
                                        }
                                        else 
                                        {
                                            $off=request::get_int('page',1)*100+$offset-100;
                                            $ns_data= db::get_row('SELECT * FROM user_novasys_data WHERE user_id='.$id.' OFFSET '.$off);
                                            $offset++;
                                        }
                                if ($ns_data['novasys_id']>0) { ?>
				<tr id="tr_<?=$ns_data['novasys_id']?>">
					<td><?=$ns_data['novasys_id']?></td>
					<td><? if ($id==0) { ?> <a id="<?=$ns_data['novasys_id']?>" class="clk">0</a> <? } else { ?><a href="/profile-<?=$id?>" target="blank"><?=$id?></a><? } ?></td>
					<td><a href="/search?submit=1&first_name=<?=$ns_data['name']?>" target="blank"><?=$ns_data['name']?> </a></td>
					<td><a href="/search?submit=1&last_name=<?=$ns_data['lname']?>" target="blank"><?=$ns_data['lname']?> </a></td>
                                        <td><a href="/search?submit=1&email=<?=$ns_data['email0']?>" target="blank"><?=$ns_data['email0']?> </a></td>
                                        <td><a id="r_<?=$ns_data['novasys_id']?>" class="delt"><img src="/static/images/icons/3.3.png" alt="Видалити" class="ml5"></a></td>
                                        <!--td><? if ($ns_data['user_id']) { ?><a href="/profile-<?=$id?>" target="blank"><?=$user_data['first_name'].' '.$user_data['last_name'].' '.$user['email']?></a> <? } ?></td-->
				</tr>
                                <? } ?>
			<? } ?>
		</table>
	</div>

	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>

        <div id="form" style="display:none;position:absolute;">
            <form>
            <input type="hidden" id="id" name="id" value="">
            <input type="text" id="name" name="name" style="width:30px;" value="">

            </form>
        </div>

        
        <script type="text/javascript">
$("a.clk").click(function(){
	var id = this.id;
	var name = $(this).html();
	var offset = $(this).offset();
	$("#name").val(name);
	$("#id").val(id);
	$("#form").css({'left':offset.left,'top':offset.top}).show();
$("#name").focus();
	});

$("#name").keypress(function (e) {
    if(e.which == 13){
	var name = $("#name").val();
	var id = $("#id").val();
	$.post('/admin/update_ns_user',{'id':id,'name':name},function(data){
		if(data=='1'){
				$("a#"+id).html(name);
		}
		$("#form").hide();
	});
	return false;
	}
	});
       
$("a.delt").click(function(){ 
        if (confirm('Видалити?'))
            {
	var id = this.id;
        $.post('/admin/update_ns_user',{'del':id},function(data){
		if(data=='1'){
				$("#t"+id).hide();
		}
	});
        }
});
</script>
</div>
