
<style type="text/css">
	.inquirer {
		vertical-align: top;
	}
	
	.box-assigned-for {
		position: absolute;
		font-size: 10px;
		border: 1px solid #660000;
		width: 384px;
		padding: 10px;
		background: #FFF;
		display: none;
		-webkit-box-shadow: 0px 0px 15px #222;
    -moz-box-shadow: 0px 0px 15px #222;
    box-shadow: 0px 0px 15px #222;
		filter:
        progid:DXImageTransform.Microsoft.Shadow(color='#042b47', Direction=45, Strength=6)
        progid:DXImageTransform.Microsoft.Shadow(color='#042b47', Direction=135, Strength=6)
        progid:DXImageTransform.Microsoft.Shadow(color='#042b47', Direction=225, Strength=6)
        progid:DXImageTransform.Microsoft.Shadow(color='#042b47', Direction=315, Strength=6);
	}
	
	.select-box {
		width: 240px;
		border: 1px solid #660000;
		overflow: auto;
		height: 200px;
		padding: 4px;
		color: black;
	}
	
	.plus {
		border: 1px solid #888;
		width: 16px;
		height:16px;
		background: #FFF;
		margin-right: 4px;
	}
	
	.assigen-for-temp {
		width: 240px;
		border: 1px solid #660000;
		overflow: auto;
		height: 96px;
		padding: 4px;
		color: black;
	}
	
	.minus {
		border: 1px solid #888;
		width: 16px;
		height:16px;
		background: #FFF;
		margin-right: 4px;
	}
</style>

<table>
	<tr>
		<td width="164px">
			<div class="column_head">
				Навигация
			</div>
			<div class="box_content p10">
				<div style="font-size: 14px"><a href="/inquirer/edit">Создать</a></div>
			</div>
		</td>
		<td>
			<div class="column_head">
				Список опросов: <?=count($list)?>
			</div>
			<div class="box_content p10">
				<div>
					<? load::model("user/user_data"); ?>
					<? foreach($list as $id){ ?>
						<? $c++; ?>
						<? $inquirer = inquirer_peer::instance()->get($id); ?>
						<? $user_data = user_data_peer::instance()->get_item($inquirer["creator"]); ?>
						<? $count_assigned = count($inquirer["assigned_for"]); ?>
						<? $count_answers = count($inquirer["answers"]); ?>
						<? $count_gen = $count_assigned+$count_answers; ?>
						<div id="inquirer-delbox-<?=$inquirer["id"]?>" class="hide" style="position: absolute; border: 1px solid #660000; background: #660000; width: 544px; height: 110px; filter:alpha(opacity=25); opacity:0.25;">
							<div style="text-align: center; padding-top: 40px">
								<a id="recover-<?=$inquirer["id"]?>" href="javascript:;" style="color: #FFF; font-weight: bold;">Восстановить</a>
							</div>
						</div>
						<div id="inquirer-<?=$inquirer["id"]?>" style="background: #EEE; padding: 8px;">
							<div class="left acenter" style="font-size: 14px; width: 32px; color: #888">
								<!--<div><?=$c?></div>-->
								<div><input type="checkbox" /></div>
							</div>
							<div class="left">
								<div style="font-size: 14px; font-weight: bold; width: 364px">
									<a href="/inquirer/edit?id=<?=$inquirer["id"]?>"><?=$inquirer["name"]?></a>
								</div>
								<div style="font-size: 10px; color: #888">
									Создал: <a href="/profile-<?=$user_data["user_id"]?>">
										<?=$user_data["first_name"]." ".$user_data["last_name"]?>
									</a>
								</div>
								<div style="font-size: 10px; color: #888">
									<? $time = $inquirer["created"]; ?>
									<? $time = mktime(0, 0, 0, $time[1], $time[2], $time[0]);?>
									Дата создания: <?=date("d.m.Y", $time)?>
								</div>
								<div style="font-size: 10px; color: #888">
									Статус: <span id="status-<?=$inquirer["id"]?>" style="color: black;">
										<? if($count_gen > 0 && $inquirer["published"] > 0){ ?>
											Опубликованный
										<? } else { ?>
											Неопубликованный
										<? } ?>
									</span>
								</div>
								<div class="box-assigned-for" id="boxassignedfor-<?=$inquirer["id"]?>">
									<!--<div class="aright">
										<a href="javascript:;" id="">Close</a>
									</div>-->
									<div style="height: 4px"></div>
									<div>
										<div class="left" style="margin-right: 8px; width: 126px;">
											<input type="radio" name="filter" value="common" id="common" alt="Усім" />Усім<br />
											<input type="radio" name="filter" value="group" alt="Спiльноти" />Спiльноти<br />
											<input type="radio" name="filter" value="status" alt="Статус" />Статус<br />
											<input type="radio" name="filter" value="func" alt="Функції" />Функції<br />
											<input type="radio" name="filter" value="region" alt="Регіони" />Регіони<br />
											<!--<input type="radio" name="filter" value="district" />Регіон/район<br />-->
											<input type="radio" name="filter" value="sferas" alt="Сфера діяльності" />Сфера діяльності<br />
											<input type="radio" name="filter" value="targets" alt="Цiльова группа" />Цiльова группа<br />
											<!--<input type="radio" name="filter" value="visit" />Останнє відвідування<br />-->
											<? load::model('lists/lists'); ?>
											<? $list_data = lists_peer::instance()->get_list_data(session::get_user_id()); ?>
											<? if(count($list_data) != 0){ ?>
												<input onclick="" type="radio" name="filter" value="lists" alt="Списки" />Списки<br />
											<? } ?>
										</div>
										<div class="left">
											<div class="mfilter" id="common_filter">
												<div class="select-box">
												</div>
											</div>
											
											<div class="mfilter hidden" id="group_filter">
												<? load::model('groups/groups') ?>
												<? $groups = groups_peer::instance()->get_select_list(); ?>
												<div class="select-box">
													<? foreach($groups as $id => $value){ ?>
														<? $flag = $flag ? false : true; ?>
														<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
															<div class="left acenter plus" id="<?=$id?>">+</div>
															<div class="left p2" style="width: 190px"><?=$value?></div>
															<div class="clear"></div>
														</div>
													<? } ?>
												</div>
											</div>
											
											<div class="mfilter hidden" id="status_filter">
												<? $status = user_auth_peer::instance()->get_statuses(); ?>
												<div class="select-box">
													<? foreach($status as $id => $value){ ?>
														<? $flag = $flag ? false : true; ?>
														<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
															<div class="left acenter plus" id="<?=$id?>">+</div>
															<div class="left p2" style="width: 190px"><?=$value?></div>
															<div class="clear"></div>
														</div>
													<? } ?>
												</div>
											</div>

											<div class="mfilter hidden" id="func_filter">
												<? $function = user_auth_peer::get_functions(); ?>
												<div class="select-box">
													<? foreach($function as $id => $value){ ?>
														<? $flag = $flag ? false : true; ?>
														<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
															<div class="left acenter plus" id="<?=$id?>">+</div>
															<div class="left p2" style="width: 190px"><?=$value?></div>
															<div class="clear"></div>
														</div>
													<? } ?>
												</div>
											</div>

											<div class="mfilter hidden" id="region_filter">
												<? $reg = geo_peer::instance()->get_regions(1); ?>
												<div class="select-box">
													<? foreach($reg as $id => $value){ ?>
														<? $flag = $flag ? false : true; ?>
														<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
															<div class="left acenter plus" id="<?=$id?>">+</div>
															<div class="left p2" style="width: 190px"><?=$value?></div>
															<div class="clear"></div>
														</div>
													<? } ?>
												</div>
											</div>

											<? if (count($list_data) != 0) { ?>
												<div class="mfilter hidden" id="lists_filter">
													<div class="select-box">
														<? foreach($list_data as $id => $value){ ?>
															<? $flag = $flag ? false : true; ?>
															<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
																<div class="left acenter plus" id="<?=$id?>">+</div>
																<div class="left p2" style="width: 190px"><?=$value?></div>
																<div class="clear"></div>
															</div>
														<? } ?>
													</div>
												</div>
											<? } ?>

											<div class="mfilter hidden" id="district_filter">
												<input name="region_id" type="hidden" value="13" />
												<? $regns = geo_peer::instance()->get_regions(1); ?>
												<? $regns[0] = "Виберіть регiон"; ?>
												<? ksort($regns); ?>
												<?=tag_helper::select('regionc', $regns, array('use_values' => false, 'value' => 999, 'id' => 'region', 'rel' => t('Выберите регион')))?><br />
												<? if ($user_data['region_id'] > 0 and $user_data['region_id'] != 9999){ ?>
													<? $cities = geo_peer::instance()->get_cities($user_data['region_id']); ?>
												<? } elseif ($user_data['country_id'] > 1){ ?>
													<? $cities['9999'] = 'закордон'; ?>
												<? } else { ?>
													<? $cities = array("" => t('Выберите город/район')); ?>
												<? } ?>
												<?= tag_helper::select('city[]', $cities, array('use_values' => false, 'value' => $user_data['city_id'], 'id' => 'city', 'multiple' => 'multiple', 'rel' => t('Выберите город/район'))); ?>
											</div>
											
											<div class="mfilter hidden" id="sferas_filter">
												<? $segments = user_auth_peer::instance()->get_segments(); ?>
												<div class="select-box">
													<? foreach($segments as $id => $value){ ?>
														<? $flag = $flag ? false : true; ?>
														<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
															<div class="left acenter plus" id="<?=$id?>">+</div>
															<div class="left p2" style="width: 190px"><?=$value?></div>
															<div class="clear"></div>
														</div>
													<? } ?>
												</div>
											</div>
											
											<div class="mfilter hidden" id="targets_filter">
												<? $target = user_helper::get_targets(); ?>
												<div class="select-box">
													<? foreach($target as $id => $value){ ?>
														<? $flag = $flag ? false : true; ?>
														<div class="p2" style="background: <?= $flag ? "#EEE" : "#FFF"; ?>">
															<div class="left acenter plus" id="<?=$id?>">+</div>
															<div class="left p2" style="width: 190px"><?=$value?></div>
															<div class="clear"></div>
														</div>
													<? } ?>
												</div>
											</div>
											
											<div class="mfilter hidden" id="visit_filter">
												<select name="visit" value="" use_values="">
													<option value="1" <?=request::get('visit_ts') == 1 ? 'selected' : '' ?>><?= t('сегодня') ?></option>
													<option value="3" <?=request::get('visit_ts') == 3 ? 'selected' : '' ?>><?= t('3 дня') ?></option>
													<option value="7" <?=request::get('visit_ts') == 7 ? 'selected' : '' ?>><?= t('неделя') ?></option>
													<option value="30" <?=request::get('visit_ts') == 30 ? 'selected' : '' ?>><?= t('месяц') ?></option>
													<option value="-7" <?=request::get('visit_ts') == '-7' ? 'selected' : '' ?>><?= t('больше недели') ?></option>
													<option value="-30" <?=request::get('visit_ts') == '-30' ? 'selected' : '' ?>><?= t('больше месяца') ?></option>
													<option value="-163" <?=request::get('visit_ts') == '163' ? 'selected' : '' ?>><?= t('больше полгода') ?></option>
													<option value="-365" <?=request::get('visit_ts') == '-365' ? 'selected' : '' ?>><?= t('больше года') ?></option>
												</select>
											</div>
										</div>
										<div class="clear"></div>
									</div>
									<div style="height: 8px">&nbsp;</div>
									<div style="height: 8px"><hr /></div>
									<div>
										<div class="left" style="width: 126px; margin-right: 8px">Назначено:</div>
										<div class="left assigen-for-temp" id="assigen-for-temp">
											<div class="p2" style="background: <?= !$flag ? "#EEE" : "#FFF"; ?>">
											</div>
										</div>
										<div class="clear"></div>
									</div>
									<div style="height: 8px">&nbsp;</div>
									<div class="aright">
										<input type="button" class="button" id="boxassignedforassign-<?=$inquirer["id"]?>" value=" Назначити " />
										<input type="button" class="button button_gray" id="boxassignedforclose-<?=$inquirer["id"]?>" value=" Відміна " />
									</div>
								</div>
								<div style="font-size: 10px; color: #888">
									Назначено: <a id="linkassignedfor-<?=$inquirer["id"]?>" href="javascript:;">
										<?= $count_gen > 0 ? $count_gen : "Никому" ?>
									</a>
								</div>
								<div style="font-size: 10px; color: #888">
									<? ; ?>
									<? if($count_answers > 0){ ?>
										Ответов: <a href="#"><?=$count_answers?></a>
									<? } else { ?>
										Ответов: <?=$count_answers?>
									<? } ?>
								</div>
							</div>
							<div class="left aright" style="font-size: 10px; width: 128px">
								<div><a id="start-<?=$inquirer["id"]?>" <?= $inquirer["published"] > 0 ? "class=\"hide\"" : "" ?> href="javascript:;">Запустить</a></div>
								<div><a id="stop-<?=$inquirer["id"]?>" <?= ! $inquirer["published"] ? "class=\"hide\"" : "" ?> href="javascript:;">Остановить</a></div>
								<div><a href="/inquirer/edit?id=<?=$inquirer["id"]?>">Изменить</a></div>
								<div><a id="delete-<?=$inquirer["id"]?>" href="javascript:;">Удалить</a></div>
								<? if($count_answers > 0){ ?>
									<div><a href="/inquirer/stat?id=<?=$inquirer["id"]?>">Статистика</a></div>
								<? } ?>
							</div>
							<div class="clear"></div>
						</div>
						<div style="height: 8px;"></div>
					<? } ?>
				</div>
			</div>
		</td>
	</tr>
</table>

<script type="text/javascript">
	$(document).ready(function(){
		
		var status = {
			"deleted" : "Удален",
			"recovered" : "Восстановлен",
			"published" : "Опубликованный",
			"not_published": "Неопубликованный"
		};
		
		var criterion = {};
		
		$("a").click(function(){
			var tmp = $(this).attr("id").split("-");
			
			var event = tmp[0];
			var id = tmp[1];
			
			switch(event){
				case "stop":
					$.post("/inquirer/edit",
						{
							"act": "stop",
							"id": id
						},
						function(data){
							$("#start-"+id).show();
							$("#stop-"+id).hide();
							$("#status-"+id).html(status.not_published);
						},
						"json"
					);
					break;
					
				case "start":
					$.post("/inquirer/edit",
						{
							"act": "start",
							"id": id
						},
						function(data){
							$("#start-"+id).hide();
							$("#stop-"+id).show();
							if(parseInt($("#linkassignedfor-"+data.id).html()) > 0)
								$("#status-"+id).html(status.published);
						},
						"json"
					);
					break;
					
				case "recover":
					$.post("/inquirer/edit",
						{
							"act": "recover",
							"id" : id
						},
						function(data){
							$("#inquirer-delbox-"+data.id).hide();
							$("#status-"+data.id).html(status.recovered);
						},
						"json"
					);
					break;
					
				case "delete":
					$.post("/inquirer/edit",
						{
							"act": "delete",
							"id": id
						},
						function(data){
							$("#inquirer-delbox-"+data.id).show();
							$("#status-"+data.id).html(status.deleted);
						},
						"json"
					);
					break;
				
				case "linkassignedfor":
					$.each($(".box-assigned-for"), function(){
						$(this).hide();
					});
					$("#boxassignedfor-"+id).show();
					$("#boxassignedfor-"+id+" #common").click();
					break;
					
				case "boxassignedforclose":
					$("#boxassignedfor-"+id).hide()
					break;
			}
		});
		
		$(".plus").click(function(){
			
			var tmp = $(this).parent().parent().parent().attr("id").split("_");
			var key = tmp[0];
			var id = $(this).attr("id");
			var value = $(this).parent().find(".p2").html();
			
			if(typeof criterion[key] == "undefined")
				criterion[key] = new Object();
			
			criterion[key][id] = value;
			var box_id = $(this).parent().parent().parent().parent().parent().parent().attr("id");
			check_assigned_box(box_id);
			
		});
		
		$("input").click(function(){
			
			if($(this).attr("name") == "filter"){
				$.each($(".mfilter"), function(){
					$(this).hide();
				});
				
				var id = $(this).parent().parent().parent().attr("id");
				
				$("#"+id+" #"+$(this).val()+"_filter").show();
				if($(this).val() == "common"){
					criterion = new Object();
					criterion["common"] = new Object();
					criterion["common"][0] = "Без вийнятку";
					check_assigned_box(id);
				} else if(typeof criterion["common"] != "undefined"){
					criterion = new Object();
				}
			}
			
			var tmp = $(this).parent().parent().attr("id").split("-");
			if($(this).attr("id") == "boxassignedforassign-"+tmp[1]){
				$.post("/inquirer/edit", 
					{
						"act": "assign",
						"id": tmp[1],
						"criterion": criterion
					},
					function(data){
						$("#linkassignedfor-"+data.id).html(data.count);
						$("#boxassignedfor-"+data.id).hide();
						if( ! $("#start-"+data.id).is(":visible"))
							$("#status-"+data.id).html(status.published);
					},
					"json"
				);
			}
			if($(this).attr("id") == "boxassignedforclose-"+tmp[1]){
				$(this).parent().parent().hide();
			}
			
		});
		
		var check_assigned_box = function(box_id){
			
			var assigned = $("#"+box_id+" #assigen-for-temp")
			
			$(assigned).html("");
			var flag = false;
			$.each(criterion, function(key, crit){
				var capt = "";
				$.each($("input"), function(){
					if($(this).attr("type") == "radio" && $(this).val() == key)
						capt = $(this).attr("alt");
				});
				$.each(crit, function(id, value){
					flag = flag ? false : true;
					$(assigned).append(
						$("<div />").attr("class", "p2")
							.css("background", flag ? "#EEE" : "#FFF")
							.append(
								$("<div />").attr("class", "left acenter minus")
									.attr("id", key+"-"+id)
									.bind("click", function(){
										var tmp = $(this).attr("id").split("-");
										delete criterion[tmp[0]][tmp[1]];
										var box_id = $(this).parent().parent().parent().parent().attr("id");
										check_assigned_box(box_id);
									})
									.html("-")
							)
							.append(
								$("<div />").attr("class", "left p2")
									.css("width", "190px")
									.html(capt+" - "+value)
							)
							.append(
								$("<div />").attr("class", "clear")
							)
					);
				});
			})
		}
		
	});
</script>
