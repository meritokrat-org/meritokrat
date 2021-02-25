<style type="text/css">
	div.pane_issus_head {
		background: -moz-linear-gradient(top,  #aaa,  #eee);
		background: -webkit-gradient(linear, left top, left bottom, from(#aaa), to(#eee));
		background: -o-linear-gradient(#aaa, #eee);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#aaaaaa', endColorstr='#eee');
	}
	div.checkBox {
		width: 16px;
		height: 16px;
		background: url('/static/images/icons/galochka.png');
	}
	div.checkBoxSelected {
		background: url('/static/images/icons/galochka2.png');
	}
</style>

<div id="pane_issus" class="content_pane mt10 fs12">
	<div>
		<div class="pane_issus_head p5">
			<div class="left bold cbrown"><?=t('Готов быть на выборах')?></div>
			<div class="right"><a href="javascript:;" id="link_vuboryEdit"><?=t('Редактировать')?></a></div>
			<div class="clear"></div>
		</div>
		<div id="block_vubory" class="mt10">
			<div style="margin-left: 20px;">
				<div id="info_issus_samgolosuju" class="left checkBox"></div>
				<div class="left ml10"><?=t('Лично проголосую за Игоря Шевченка')?></div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="info_issus_moigolosujut" class="left checkBox"></div>
				<div class="left ml10"><?=t('Члены моей семьи проголосуют за Игоря Шевченка')?></div>
				<div id="info_issus_moigolosujut_count" class="left ml10 hide">(<label>0</label> <?=t('чел')?>.)</div>
				<div class="clear"></div>
			</div>
			<div class="mt10<? if( ! session::has_credential('admin') || session::get_user_id() != request::get_int('id')){ ?> hide<? } ?>" style="margin-left: 20px;">
				<div id="info_issus_finansist" class="left checkBox"></div>
				<div class="left ml10"><?=t('Готов финансово поддержать Игоря Шевченка на выборах')?></div>
				<div id="info_issus_finansist_summa" class="left ml10 hide">(<label>0</label> грн.)</div>
				<div class="clear"></div>
			</div>
		</div>
		<div id="block_vuboryEdit" class="mt10 hide">
			<div>
				<div>
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="issus_agitator" />
							<label for="issus_agitator"><?=t('Агитатором')?></label>
						</div>
						<div id="block_issus_agitator" class="hide" style="margin-left: 20px">
							<div class="mt5">
								<input type="checkbox" id="issus_agitator_ulica" />
								<label for="issus_agitator_ulica"><?=t('Уличная агитация')?> (<?=t('в палатке, роздача листовок и газет')?>)</label>
							</div>
							<div class="mt5" style="width: 480px">
								<input type="checkbox" id="issus_agitator_phone" />
								<label for="issus_agitator_phone"><?=t('По телефону')?> (<?=t('позвоню друзьям и знакомым, розкажу про Игоря Шевченка и предложу проголосовать за Игоря Шевченка')?>)</label>
							</div>
							<div class="mt5" style="width: 480px">
								<input type="checkbox" id="issus_agitator_vstrechi" />
								<label for="issus_agitator_vstrechi"><?=t('Личные встречи')?> (<?=t('Проведу встречу с друзьями и знакомыми, розкажу про Игоря Шевченка и предложу проголосовать за Игоря Шевченка')?>)</label>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="issus_observer" />
						<label for="issus_observer"><?=t('Наблюдателем')?></label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="issus_comission" />
						<label for="issus_comission"><?=t('Член комиссии')?></label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="issus_urist" />
						<label for="issus_urist"><?=t('Юристом')?> (<?=t('помогу защитить голоса')?>)</label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="issus_samgolosuju" />
						<label for="issus_samgolosuju"><?=t('Лично проголосую за Игоря Шевченка')?></label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="issus_moigolosujut" />
							<label for="issus_moigolosujut"><?=t('Члены моей семьи проголосуют за Игоря Шевченка')?></label>
						</div>
						<div id="block_issus_moigolosujut" class="hide" style="margin-left: 20px">
							<div class="cgray"><?=t('Укажите, пожалуйста, количество человек')?></div>
							<div>
								<input type="text" id="issus_moigolosujut_count" />
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="issus_finansist" />
							<label for="issus_finansist"><?=t('Готов финансово поддержать Игоря Шевченка на выборах')?></label>
						</div>
						<div id="block_issus_finansist" class="hide" style="margin-left: 20px">
							<div class="cgray"><?=t('Укажите, пожалуйста, сумму, которую Вы готовы внести')?></div>
							<div>
								<?=t('Сумма')?>: <input type="text" id="issus_finansist_summa" />
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="button" id="vuboryEdit_submit" value="<?=t('Сохранить')?>" />
						<input type="button" id="vuboryEdit_cancel" value="<?=t('Отмена')?>" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="mt10<? if( ! session::has_credential('admin')){ ?> hide<? } ?>">
		<div class="pane_issus_head p5">
			<div class="left bold cbrown">*<?=t('Готов быть на выборах')?></div>
			<div class="right"><a href="javascript:;" id="link_adminVuboryEdit"><?=t('Редактировать')?></a></div>
			<div class="clear"></div>
		</div>
		<div id="block_adminVubory" class="mt10">
			<div style="margin-left: 20px;">
				<div id="info_adminissus_samgolosuju" class="left checkBox"></div>
				<div class="left ml10"><?=t('Лично проголосую за Игоря Шевченка')?></div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="info_adminissus_moigolosujut" class="left checkBox"></div>
				<div class="left ml10"><?=t('Члены моей семьи проголосуют за Игоря Шевченка')?></div>
				<div id="info_adminissus_moigolosujut_count" class="left ml10 hide">(<label>0</label> <?=t('чел')?>.)</div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="info_adminissus_finansist" class="left checkBox"></div>
				<div class="left ml10"><?=t('Готов финансово поддержать Игоря Шевченка на выборах')?></div>
				<div id="info_adminissus_finansist_summa" class="left ml10 hide">(<label>0</label> грн.)</div>
				<div class="clear"></div>
			</div>
		</div>
		<div id="block_adminVuboryEdit" class="mt10 hide">
			<div>
				<div>
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="adminissus_agitator" />
							<label for="adminissus_agitator"><?=t('Агитатором')?></label>
						</div>
						<div id="block_adminissus_agitator" class="hide" style="margin-left: 20px">
							<div class="mt5">
								<input type="checkbox" id="adminissus_agitator_ulica" />
								<label for="adminissus_agitator_ulica"><?=t('Уличная агитация')?> (<?=t('в палатке, роздача листовок и газет')?>)</label>
							</div>
							<div class="mt5" style="width: 480px">
								<input type="checkbox" id="adminissus_agitator_phone" />
								<label for="adminissus_agitator_phone"><?=t('По телефону')?> (<?=t('позвоню друзьям и знакомым, розкажу про Игоря Шевченка и предложу проголосовать за Игоря Шевченка')?>)</label>
							</div>
							<div class="mt5" style="width: 480px">
								<input type="checkbox" id="adminissus_agitator_vstrechi" />
								<label for="adminissus_agitator_vstrechi"><?=t('Личные встречи')?> (<?=t('Проведу встречу с друзьями и знакомыми, розкажу про Игоря Шевченка и предложу проголосовать за Игоря Шевченка')?>)</label>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="adminissus_observer" />
						<label for="adminissus_observer"><?=t('Наблюдателем')?></label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="adminissus_comission" />
						<label for="adminissus_comission"><?=t('Член комиссии')?></label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="adminissus_urist" />
						<label for="adminissus_urist"><?=t('Юристом')?> (<?=t('помогу защитить голоса')?>)</label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="adminissus_samgolosuju" />
						<label for="adminissus_samgolosuju"><?=t('Лично проголосую за Игоря Шевченка')?></label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="adminissus_moigolosujut" />
							<label for="adminissus_moigolosujut"><?=t('Члены моей семьи проголосуют за Игоря Шевченка')?></label>
						</div>
						<div id="block_adminissus_moigolosujut" class="hide" style="margin-left: 20px">
							<div class="cgray"><?=t('Укажите, пожалуйста, количество человек')?></div>
							<div>
								<input type="text" id="adminissus_moigolosujut_count" />
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="adminissus_finansist" />
							<label for="adminissus_finansist"><?=t('Готов финансово поддержать Игоря Шевченка на выборах')?></label>
						</div>
						<div id="block_adminissus_finansist" class="hide" style="margin-left: 20px">
							<div class="cgray"><?=t('Укажите, пожалуйста, сумму, которую Вы готовы внести')?></div>
							<div>
								<?=t('Сумма')?>: <input type="text" id="adminissus_finansist_summa" />
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="button" id="adminVuboryEdit_submit" value="<?=t('Сохранить')?>" />
						<input type="button" id="adminVuboryEdit_cancel" value="<?=t('Отмена')?>" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="mt10">
		<div class="pane_issus_head p5">
			<div class="left bold cbrown"><?=t('Проинформировано людей')?></div>
			<div class="clear"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#vuboryEdit_submit, #adminVuboryEdit_submit").click(function(){
			var data = new Object();
			
			var admin = "v";
			if($(this).attr('id') == "adminVuboryEdit_submit")
				admin = "adminV";
			
			$("#block_"+admin+"uboryEdit input").each(function(){
				var id = $(this).attr("id")
				
				if(id == "" || id == admin+"uboryEdit_submit")
					return;
				
				if($(this).attr("type") == "checkbox")
					data[id] = $(this).attr("checked") ? 1 : 0;
				else
					data[id] = $(this).val();
			});
			
			$.post("/profile/user_voter", {
				"act": "save",
				"user_id": "<?=session::get_user_id()?>",
				"data": data,
				"admin": admin
			}, function(response){
				if( ! response.success)
					return;
				
				getItem();
				$("#"+admin+"uboryEdit_cancel").click();
			}, "json");
		});
		
		$("input[id^='issus_']").change(function(){
			var id = $(this).attr("id").split("_")[1];
			var state = $(this).attr("checked") ? true : false
			
			if(state)
				$("#block_"+$(this).attr("id")).show();
			else
			{
				$("#block_"+$(this).attr("id")+" input[type=text]").val(0);
				$("#block_"+$(this).attr("id")).hide();
			}
		});
		
		$("input[id^='adminissus_']").change(function(){
			var id = $(this).attr("id").split("_")[1];
			var state = $(this).attr("checked") ? true : false
			
			if(state)
				$("#block_"+$(this).attr("id")).show();
			else
			{
				$("#block_"+$(this).attr("id")+" input[type=text]").val(0);
				$("#block_"+$(this).attr("id")).hide();
			}
		});
		
		$("#link_vuboryEdit").click(function(){
			$('#block_vubory').hide();
			$('#block_vuboryEdit').show();
		});
		
		$("#vuboryEdit_cancel").click(function(){
			$('#block_vubory').show();
			$('#block_vuboryEdit').hide();
		});
		
		$("#link_adminVuboryEdit").click(function(){
			$('#block_adminVubory').hide();
			$('#block_adminVuboryEdit').show();
		});
		
		$("#adminVuboryEdit_cancel").click(function(){
			$('#block_adminVubory').show();
			$('#block_adminVuboryEdit').hide();
		});
		
		var getItem = function()
		{
			$.post('/profile/user_voter', {
				'act': 'get_item',
				'user_id': '<?=session::get_user_id()?>'
			}, function(response){
				if( ! response.success)
					return;
				
				for(var attr in response.data.attr)
				{
					if(attr == "issus_moigolosujut_count" || attr == "issus_finansist_summa")
					{
						$('#'+attr).val(parseInt(response.data.attr[attr]) > 0 ? parseInt(response.data.attr[attr]) : 0);
						if(parseInt(response.data.attr[attr]) > 0)
							$('#info_'+attr)
								.show()
								.find('label').html(parseInt(response.data.attr[attr]));
						else
							$('#info_'+attr).hide();
						continue;
					}
					
					$('#'+attr)
						.attr('checked', response.data.attr[attr] != 1 ? false : true)
						.change();
						
					if(response.data.attr[attr] != 1)
						$('#info_'+attr).removeClass('checkBoxSelected');
					else
						$('#info_'+attr).addClass('checkBoxSelected');
				}
				
				for(var attr in response.data.adminattr)
				{
					if(attr == "adminissus_moigolosujut_count" || attr == "adminissus_finansist_summa")
					{
						$('#'+attr).val(parseInt(response.data.adminattr[attr]) > 0 ? parseInt(response.data.adminattr[attr]) : 0);
						if(parseInt(response.data.adminattr[attr]) > 0)
							$('#info_'+attr)
								.show()
								.find('label').html(parseInt(response.data.adminattr[attr]));
						else
							$('#info_'+attr).hide();
						continue;
					}
					
					$('#'+attr)
						.attr('checked', response.data.adminattr[attr] != 1 ? false : true)
						.change();
						
					if(response.data.adminattr[attr] != 1)
						$('#info_'+attr).removeClass('checkBoxSelected');
					else
						$('#info_'+attr).addClass('checkBoxSelected');
				}
			}, 'json');
		}
		
		getItem();
	});
</script>