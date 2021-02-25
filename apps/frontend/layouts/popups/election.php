<?

if($popup_flag != true){ 
	
	load::model('user/user_voter');
	$listtt = user_voter_peer::instance()->get_list(array('user_id' => session::get_user_id()));
	
}

?>
<div id="popup_content" class="hide" style="position: absolute; color: rgb(0, 0, 0); width: 500px; top: 280px; left: 45%; margin-left: -200px; background-color: white; border: 10px solid #660000; z-index: 10001; font-family: Arial,Sans-serif,Serif; -moz-border-radius: 10px 10px 10px 10px;" >
	<div id="target_loading" class="hide"><img src="/static/images/common/loaging.gif" style="margin-top: 210px; margin-left: 210px;" class="acenter"/></div>
	<div class="fs14 cbrown m5">
		<div class="left bold"><?=t('Мое участие в Выборах 2012')?></div>
		<div class="right">
			<a href="javascript:;" class="fs12 cgray" onClick="closeWindow()">Закрыть</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="hide" id="popup_content_target">
		<div id="block-user-election-data-edit" class="mt10">
			<div>
				<? if($popup_flag != true){ ?>
					<div class="mt5<? if($additional_data['birthday']){ ?> hide<? } ?>">
						<div class="left mr5" style="width: 120px; margin-left: 20px;"><?=t('Дата рождения')?></div>
						<div class="left">
							<? $range_years=range(1930,2000); ?>
							<? foreach ($range_years as $year) $years[$year]=$year; ?>
							<? $days = array(); ?>
							<? for($i = 0; $i < 31; $i++){ ?>
								<? $days[$i+1] = $i+1; ?>
							<? } ?>
							<?=tag_helper::select('day', $days, array("style"=>"width:55px", 'id' => 'day'))?> - <?=tag_helper::select('month', date_helper::get_month_name(), array("style"=>"width:115px", 'id' => 'month'))?> - <?=tag_helper::select('year', $years, array("style"=>"width:65px", 'id' => 'year'))?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt5<? if($additional_data['phone']){ ?> hide<? } ?>">
						<div class="left mr5" style="width: 120px; margin-left: 20px;"><?=t('Телефон')?></div>
						<div class="left">
							<input type="text" class="text" name="phone" style="width:250px">
						</div>
						<div class="clear"></div>
					</div>
				<? } ?>
				<? if($popup_flag || ($popup_flag != true && count($listtt) < 1)){ ?>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<input type="checkbox" id="willVote" />
							<label for="willVote"><?=t('Лично проголосую за МПУ')?></label>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<div>
								<input type="checkbox" id="financialSupport" />
								<label for="financialSupport"><?=t('Готов помочь финансово')?></label>
							</div>
							<div id="block-financialSupport" class="hide" style="margin-left: 20px">
								<div class="cgray"><?=t('Укажите, пожалуйста, сумму, которую Вы готовы внести')?></div>
								<div>
									<?=t('Сумма')?>: <input type="text" id="countFinancialSupport" />
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<div>
								<input type="checkbox" id="agitator" />
								<label for="agitator"><?=t('Готов заниматься агитацией')?></label>
							</div>
							<div id="block-agitator" class="hide" style="margin-left: 20px">
								<div class="mt5">
									<input type="checkbox" id="agitateMyFamily" />
									<label for="agitateMyFamily"><?=t('Могу проинформировать про МПУ членов моей семьи, друзей, коллег')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="agitateInInternet" />
									<label for="agitateInInternet"><?=t('Могу проводить агитацию в интернете (работа в соцсетях, на форумах)')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="agitateOnStreet" />
									<label for="agitateOnStreet"><?=t('Могу проводить агитацию среди незнакомых людей (уличная агитация в палатках, роздача листовок, газет)')?></label>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<div>
								<input type="checkbox" id="volunteer" />
								<label for="volunteer"><?=t('Готов помочь в качестве волонтера')?></label>
							</div>
							<div id="block-volunteer" class="hide" style="margin-left: 20px">
								<div class="mt5">
									<input type="checkbox" id="volunteerInKiev" />
									<label for="volunteerInKiev"><?=t('В центральном штабе (Киев)')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="volunteerInRegion" />
									<label for="volunteerInRegion"><?=t('В региональном штабе')?></label>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<div>
								<input type="checkbox" id="wantRun" />
								<label for="wantRun"><?=t('Хочу баллотироваться от МПУ в Верховную Раду')?></label>
							</div>
							<div id="block-wantRun" class="hide" style="margin-left: 20px">
								<div class="mt5">
									<input type="checkbox" id="wantRunSingle" />
									<label for="wantRunSingle"><?=t('В одномандатном мажоритарном округе')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="wantRunByList" />
									<label for="wantRunByList"><?=t('За партийным списком МПУ')?></label>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<div>
								<input type="checkbox" id="wantToBe" />
								<label for="wantToBe"><?=t('Готов быть на выборах')?></label>
							</div>
							<div id="block-wantToBe" style="margin-left: 20px">
								<div class="mt5">
									<input type="checkbox" id="wantToBeObserver" />
									<label for="wantToBeObserver"><?=t('Наблюдателем')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="wantToBeCommissioner" />
									<label for="wantToBeCommissioner"><?=t('Член комиссии')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="wantToBeLawyer" />
									<label for="wantToBeLawyer"><?=t('Юристом (помогу защитить голоса)')?></label>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				<? } ?>
				<div class="mt5 mb10 acenter">
					<input type="button" id="save" class="button" value="<?=t('Сохранить')?>" />
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(window).scroll(function(){
		$('#popup_opacity').css('top', $(this).scrollTop()+'px');
		$('#popup_content').css('top', parseInt(180 + $(this).scrollTop())+'px');
	});
	
	var windowOpen;
	var closeWindow;
	var setChecked;
	
	$(document).ready(function(){
		
		$("#block-user-election-data-edit input[type='checkbox']").change(function(){
			var id = $(this).attr("id");
			var state = $(this).attr("checked") ? true : false
			
			if(state)
				$("#block-user-election-data-edit #block-"+$(this).attr("id")).show();
			else
			{
				$("#block-user-election-data-edit #block-"+$(this).attr("id")+" input[type=text]").val(0);
				$("#block-user-election-data-edit #block-"+$(this).attr("id")).hide();
			}
			
			var id = $("#block-user-election-data-edit #"+id).parent().parent().attr('id');
			
			if(id != '')
			{
				var _c = id.split('-')[1];
				$('#'+_c)
					.attr('checked', true)
					.change();
			}
		});
		
		$("#block-user-election-data-edit #save").click(function(){
			var data = new Object();
			
			<? if($popup_flag != true){ ?>
				$.post("/profile/additional_info", {
					day: $("#day", $('#block-user-election-data-edit')).val(),
					month: $("#month", $('#block-user-election-data-edit')).val(),
					year: $("#year", $('#block-user-election-data-edit')).val(),
					phone: $("#phone", $('#block-user-election-data-edit')).val()
				}, function(response){
					console.log(response);
				}, 'json');
			<? } ?>
			
			<? if($popup_flag || ($popup_flag != true && count($listtt) < 1)){ ?>
				$("input[type='text'], input[type='checkbox']", $('#block-user-election-data-edit')).each(function(){
					var id = $(this).attr('id');

					var value = $(this).val();
					if($(this).attr('type') == 'checkbox')
						value = $(this).attr('checked') ? 1 : 0;

					data[id] = value;
				});

				$.post('/profile/user_voter', {
					'act': 'save',
					'type': 'user',
					'user_id': '<?=session::get_user_id()?>',
					'data': data
				}, function(response){
					if(response.success)
					{
						closeWindow();
					}
				}, 'json');
			<? } ?>
		});
		
		windowOpen = function(id)
		{
			$('#footer').hide();
			$('#popup_opacity').show();
			$('#popup_content').show(1000);
			$('#popup_content_target').fadeIn(200);
			
			if(typeof id != 'undefined')
				setChecked(id);
		}
		
		closeWindow = function()
		{
			$('#popup_content_target').fadeOut(100);
			$('#popup_content').hide(300);
			$('#popup_opacity').hide();
			$('#footer').show();
		}
		
		setChecked = function(id)
		{
			$("#block-user-election-data-edit #"+id)
				.attr('checked', true)
				.change();
				
			var id = $("#block-user-election-data-edit #"+id).parent().parent().attr('id');
			
			if(id != '')
			{
				var _c = id.split('-')[1];
				$('#'+_c)
					.attr('checked', true)
					.change();
			}
		}
		
		var getItem = function()
		{
			$.post('/profile/user_voter', {
				'act': 'get_item',
				'user_id': '<?=session::get_user_id()?>'
			}, function(response){
				if( ! response.success)
					return;
				
				var container = $("#block-user-election-data-edit");
				
				for(var key in response.data.admin_data)
				{
					var type = $("#"+key, container).attr("type");
					
					if(type != "checkbox")
					{
						$("#"+key, container).val(response.data.admin_data[key]);
					}
					else
					{
						$("#"+key, container).attr("checked", response.data.admin_data[key] != 0 ? true : false );
						if(response.data.admin_data[key] != 0)
							$("#"+key, container).change();
					}
				}
			}, 'json');
		}
		
		getItem();
	});
</script>
