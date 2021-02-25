<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
<style type="text/css">
	div.pane_election_head {
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
	
	#info_list thead tr th {
		background: #fff;
	}
	
	#info_list tbody tr td {
		color: black;
		text-align: center;
	}
</style>

<div id="pane_election" class="content_pane mt10 fs12 <?=(request::get_bool('tab') && request::get_string('tab') != 'election') ? 'hide' : '' ?>">
	<div id="block-user-election-data">
		<div class="pane_election_head p5">
			<div class="left bold cbrown"><?=t('Мое участие в избирательной компании Игоря Шевченка')?></div>
			<div class="clear"></div>
		</div>
		<div id="block-admin-election-data-empty" class="mt10 hide">
			<div class="acenter"><?=t('Еще нет информации')?></div>
		</div>
		<div id="block-user-election-data-show" class="mt10">
			<div class="mt10" style="margin-left: 20px;">
				<div id="willVote" class="left checkBox"></div>
				<div class="left ml10"><?=t('Лично проголосую за Игоря Шевченка')?></div>
				<div class="clear"></div>
			</div>
			<div class="mt10<? if( ! session::has_credential('admin') && session::get_user_id() != request::get_int('id')){ ?> hide<? } ?>" style="margin-left: 20px;">
				<div id="financialSupport" class="left checkBox"></div>
				<div class="left ml10"><?=t('Готов финансово поддержать Игоря Шевченка на выборах')?></div>
				<div id="countFinancialSupport" class="left ml10 hide">(<label>0</label> грн.)</div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="agitator" class="left checkBox"></div>
				<div class="left ml10">
					<div><?=t('Готов быть активистом')?></div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="agitateMyFamily" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('Могу проинформировать про Игоря Шевченка членов моей семьи, друзей, коллег')?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="agitateInInternet" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('Могу проводить агитацию в интернете (работа в соцсетях, на форумах)')?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="agitateOnStreet" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('Могу проводить агитацию среди незнакомых людей (уличная агитация в палатках, роздача листовок, газет)')?></div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="volunteer" class="left checkBox"></div>
				<div class="left ml10">
					<div><?=t('Готов помочь в качестве волонтера')?></div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="volunteerInKiev" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('В центральном штабе (Киев)')?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="volunteerInRegion" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('В региональном штабе')?></div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="wantRun" class="left checkBox"></div>
				<div class="left ml10">
					<div><?=t('Хочу баллотироваться от Игоря Шевченка в Верховную Раду')?></div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="wantRunSingle" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('В одномандатном мажоритарном округе')?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="wantRunByList" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('За партийным списком МПУ')?></div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10" style="margin-left: 20px;">
				<div id="wantToBe" class="left checkBox"></div>
				<div class="left ml10">
					<div><?=t('Готов быть на выборах')?></div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="wantToBeObserver" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('Наблюдателем')?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="wantToBeCommissioner" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('Член комиссии')?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mt10" style="margin-left: 10px;">
						<div id="wantToBeLawyer" class="left mt5 checkBox" style="background: #444; border-radius: 10px; width: 5px; height: 5px;"></div>
						<div class="left ml10" style="width: 400px;">
							<div><?=t('Юристом (помогу защитить голоса)')?></div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<? if(session::has_credential('admin') || request::get_int('id') == session::get_user_id()){ ?>
				<div class="mt10 ml10 fs12"><a href="javascript:;" id="edit" style="text-decoration: underline"><?=t('Редактировать')?> &rarr;</a></div>
			<? } ?>
		</div>
		<div id="block-user-election-data-edit" class="mt10 hide">
			<div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="checkbox" id="willVote" />
						<label for="willVote"><?=t('Лично проголосую за Игоря Шевченка')?></label>
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
							<label for="agitator"><?=t('Готов быть активистом')?></label>
						</div>
						<div id="block-agitator" class="hide" style="margin-left: 20px">
							<div class="mt5">
								<input type="checkbox" id="agitateMyFamily" />
								<label for="agitateMyFamily"><?=t('Могу проинформировать про Игоря Шевченка членов моей семьи, друзей, коллег')?></label>
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
							<label for="wantRun"><?=t('Хочу баллотироваться от Игоря Шевченка в Верховную Раду')?></label>
						</div>
						<div id="block-wantRun" class="hide" style="margin-left: 20px">
							<div class="mt5">
								<input type="checkbox" id="wantRunSingle" />
								<label for="wantRunSingle"><?=t('В одномандатном мажоритарном округе')?></label>
							</div>
							<div class="mt5" style="width: 450px">
								<input type="checkbox" id="wantRunByList" />
								<label for="wantRunByList"><?=t('За партийным списком Игоря Шевченка')?></label>
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
						<div id="block-wantToBe" class="hide" style="margin-left: 20px">
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
				
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="button" id="save" value="<?=t('Сохранить')?>" />
						<input type="button" id="cancel" value="<?=t('Отмена')?>" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="block-admin-election-data" class="mt10<? if( ! session::has_credential('admin')){ ?> hide<? } ?>">
		<div class="pane_election_head p5">
			<div class="left bold cbrown"><a href="javascript:;" id="expander">*<?=t('Участие в выборах')?></a></div>
			<div class="clear"></div>
		</div>
		<div id="block-admin-election-data-empty-group" class="hide">
			<div id="block-admin-election-data-empty" class="mt10 hide">
				<div class="acenter"><?=t('Еще нет информации')?></div>
			</div>
			<div id="block-admin-election-data-show" class="mt10">
				<div class="mt10" style="margin-left: 20px;">
					<div id="willVote" class="left checkBox"></div>
					<div class="left ml10"><?=t('Лично проголосую за Игоря Шевченка')?></div>
					<div class="clear"></div>
				</div>
				<div class="mt10<? if( ! session::has_credential('admin') && session::get_user_id() != request::get_int('id')){ ?> hide<? } ?>" style="margin-left: 20px;">
					<div id="financialSupport" class="left checkBox"></div>
					<div class="left ml10"><?=t('Готов финансово поддержать Игоря Шевченка на выборах')?></div>
					<div id="countFinancialSupport" class="left ml10 hide">(<label>0</label> грн.)</div>
					<div class="clear"></div>
				</div>
				<div class="mt10" style="margin-left: 20px;">
					<div id="agitator" class="left checkBox"></div>
					<div class="left ml10">
						<div><?=t('Готов быть активистом')?></div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="agitateMyFamily" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('Могу проинформировать про Игоря Шевченка членов моей семьи, друзей, коллег')?></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="agitateInInternet" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('Могу проводить агитацию в интернете (работа в соцсетях, на форумах)')?></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="agitateOnStreet" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('Могу проводить агитацию среди незнакомых людей (уличная агитация в палатках, роздача листовок, газет)')?></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10" style="margin-left: 20px;">
					<div id="volunteer" class="left checkBox"></div>
					<div class="left ml10">
						<div><?=t('Готов помочь в качестве волонтера')?></div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="volunteerInKiev" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('В центральном штабе (Киев)')?></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="volunteerInRegion" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('В региональном штабе')?></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10" style="margin-left: 20px;">
					<div id="wantRun" class="left checkBox"></div>
					<div class="left ml10">
						<div><?=t('Хочу баллотироваться от Игоря Шевченка в Верховную Раду')?></div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="wantRunSingle" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('В одномандатном мажоритарном округе')?></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="wantRunByList" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('За партийным списком Игоря Шевченка')?></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10" style="margin-left: 20px;">
					<div id="wantToBe" class="left checkBox"></div>
					<div class="left ml10">
						<div><?=t('Готов быть на выборах')?></div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="wantToBeObserver" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('Наблюдателем')?></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="wantToBeCommissioner" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('Член комиссии')?></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="mt10" style="margin-left: 10px;">
							<div id="wantToBeLawyer" class="left checkBox"></div>
							<div class="left ml10" style="width: 400px;">
								<div><?=t('Юристом (помогу защитить голоса)')?></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10 ml10 fs12"><a href="javascript:;" id="edit" style="text-decoration: underline"><?=t('Редактировать')?> &rarr;</a></div>
			</div>
			<div id="block-admin-election-data-edit" class="mt10 hide">
				<div>
					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<input type="checkbox" id="willVote" />
							<label for="willVote"><?=t('Лично проголосую за Игоря Шевченка')?></label>
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
								<label for="agitator"><?=t('Готов быть активистом')?></label>
							</div>
							<div id="block-agitator" class="hide" style="margin-left: 20px">
								<div class="mt5">
									<input type="checkbox" id="agitateMyFamily" />
									<label for="agitateMyFamily"><?=t('Могу проинформировать про Игоря Шевченка членов моей семьи, друзей, коллег')?></label>
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
								<label for="wantRun"><?=t('Хочу баллотироваться от Игоря Шевченка в Верховную Раду')?></label>
							</div>
							<div id="block-wantRun" class="hide" style="margin-left: 20px">
								<div class="mt5">
									<input type="checkbox" id="wantRunSingle" />
									<label for="wantRunSingle"><?=t('В одномандатном мажоритарном округе')?></label>
								</div>
								<div class="mt5" style="width: 450px">
									<input type="checkbox" id="wantRunByList" />
									<label for="wantRunByList"><?=t('За партийным списком Игоря Шевченка')?></label>
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
							<div id="block-wantToBe" class="hide" style="margin-left: 20px">
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

					<div class="mt5">
						<div class="left" style="width: 20px;">&nbsp;</div>
						<div class="left">
							<input type="button" id="save" value="<?=t('Сохранить')?>" />
							<input type="button" id="cancel" value="<?=t('Отмена')?>" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="block-user-election-informator" class="mt10">
		<div class="pane_election_head p5">
			<div id="info_head" class="left bold cbrown"><?=t('Проагитировал за Игоря Шевченка')?> <label onclick="$('#block-user-election-informator-list #info_list').show()" class="hide">0</label></div>
			<div class="clear"></div>
		</div>
		
		<div id="block-user-election-informator-add" class="mt10 hide" style="margin-left: 100px">
			<div>
				<div class="left mt5" style="width: 100px;"><?=t('Фамилия')?>: </div>
				<div class="left"><input type="text" id="last_name" class="text" /></div>
				<div class="clear"></div>
			</div>
			<div>
				<div class="left mt5" style="width: 100px;"><?=t('Имя')?>: </div>
				<div class="left"><input type="text" id="first_name" class="text" /></div>
				<div class="clear"></div>
			</div>
			<div>
				<div class="left mt5" style="width: 100px;"><?=t('Отчество')?>: </div>
				<div class="left"><input type="text" id="middle_name" class="text" /></div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Страна')?>: </div>
				<div class="left">
					<input name="country_id" type="hidden" value="0" />
					<? load::model('geo') ?>
					<? $сountries = geo_peer::instance()->get_countries(); ?>
					<? ksort($сountries); ?>
					<?=tag_helper::select('country', $сountries, array('use_values' => false, 'value' => 0, 'id'=>'country', 'rel'=>t('Выберите страну'), "style" => "width: 150px")) ?>
				</div>
				<div class="clear"></div>
			</div>
			<div id="region_row" class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Регион')?>: </div>
				<div class="left">
					<input name="region_id" type="hidden" value="0" />
					<?=tag_helper::select('region', array(0 => "&mdash;"), array('use_values' => false, 'value' => 0, 'id'=>'region', 'rel'=>t('Выберите регион'), "style" => "width: 150px")); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Город/Район')?>: </div>
				<div class="left">
					<input name="city_id" type="hidden" value="0" />
					<?=tag_helper::select('city', array(0 => "&mdash;") , array('use_values' => false, 'value' => 0, 'id'=>'city', 'rel'=>t('Выберите город/район'), "style" => "width: 150px")); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Адрес')?>: </div>
				<div class="left"><input type="text" id="address" class="text" /></div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Тип контакта')?>: </div>
				<div class="left">
					<select id="info_type">
						<option value="1"><?=t('Звонок')?></option>
						<option value="2"><?=t('Встреча')?></option>
						<option value="3"><?=t('Эл. сообщение')?></option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Мобильный телефон')?>: </div>
				<div class="left"><input type="text" id="work_phone" class="text" /></div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Домашний Телефон')?>: </div>
				<div class="left"><input type="text" id="home_phone" class="text" /></div>
				<div class="clear"></div>
			</div>

			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('E-mail')?>: </div>
				<div class="left"><input type="text" id="info_email" class="text" /></div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Дата')?>: </div>
				<div class="left" style="width: 220px;">
					<?=user_helper::datefields('info_date')?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;"><?=t('Результат')?>: </div>
				<div class="left">
					<select id="info_result">
						<option value="1"><?=t('Будет голосовать за Игоря Шевченка')?></option>
						<option value="2"><?=t('Еще не определился')?></option>
						<option value="3"><?=t('Не будет голосовать за Игоря Шевченка')?></option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left mt5" style="width: 100px;">&nbsp;</div>
				<div class="left">
					<input type="button"  id="info_save" value="<?=t('Сохранить')?>" />
					<input type="button"  id="info_cancel" value="<?=t('Отмена')?>" />
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div  id="block-user-election-informator-list">
			<div id="block-user-election-informator-addcontact-tmpl" class="hide mb10">
				<div class="mt10">
					<div class="left mt5" style="width: 100px;"><?=t('Тип контакта')?>: </div>
					<div class="left">
						<select id="info_type">
							<option value="1"><?=t('Звонок')?></option>
							<option value="2"><?=t('Встреча')?></option>
							<option value="3"><?=t('Эл. сообщение')?></option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10">
					<div class="left mt5" style="width: 100px;"><?=t('Дата')?>: </div>
					<div class="left" style="width: 220px;">
						<?=user_helper::datefields('info_date')?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10">
					<div class="left mt5" style="width: 100px;"><?=t('Результат')?>: </div>
					<div class="left">
						<select id="info_result">
							<option value="1"><?=t('Будет голосовать за Игоря Шевченка')?></option>
							<option value="2"><?=t('Еще не определился')?></option>
							<option value="3"><?=t('Не будет голосовать за Игоря Шевченка')?></option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt10">
					<div class="left mt5" style="width: 100px;">&nbsp;</div>
					<div class="left">
						<input type="button"  id="info_addcontact" value="<?=t('Сохранить')?>" />
						<input type="button"  id="info_cancelcontact" value="<?=t('Отмена')?>" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<? if(session::has_credential('admin') || request::get_int('id') == session::get_user_id()){ ?>
				<div class="mt10 mb10 fs12">
					<input type="button" id="info_add" class="button" value="<?=t('Добавить человека')?>" />
				</div>
			<? } ?>
			<table id="info_statistics">
				<tr>
					<td width="300px"><div id="block-budut_golosovat" class="bold fs16" style="cursor: pointer"><?=t('Проголосуют за Игоря Шевченка')?>: </div></td>
					<td class="fs16"><label id="budut_golosovat" class="bold fs15">0</label></td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="info_list-budut_golosovat" class="hide" style="margin-left: 20px; color: black"></div>
						<div class="p5 hide" style="text-align: center; border-top: 1px solid #600; background: #fee">
							<a href="javascript:;" id="show_more-budut_golosovat"><?=t('Смотреть еще')?></a>
						</div>
					</td>
				</tr>
				<tr>
					<td><div id="block-ewe_ne_opredelilsya" style="cursor: pointer"><?=t('Еще не определились')?>: </div></td>
					<td><label id="ewe_ne_opredelilsya" class="bold">0</label></td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="info_list-ewe_ne_opredelilsya" class="hide" style="margin-left: 20px; color: black"></div>
					</td>
				</tr>
				<tr <? if( ! in_array(session::get_user_id(), array(2, 4, 3949, 11752))){ ?>class="hide"<? } ?>>
					<td>*<?=t('Всего контактов')?>: </td>
					<td>
						<label id="total" class="bold" onclick="$('#block-user-election-informator-list #info_list').show()">0</label>
						( <label id="calls" class="bold">0</label> <?=t('звонков')?> / <label id="meeting" class="bold">0</label> <?=t('встреч')?>)
					</td>
				</tr>
			</table>
		</div>
		
	</div>
	
	<div class="mt10">
		<div class="pane_election_head p5">
			<div class="left bold cbrown"><?=t('Взнос в избирательный фонд')?></div>
			<div class="clear"></div>
		</div>
		<div class="mt10">
			<? $pay = array() ?>
			<? if(count($payments)>0){ ?>
				<? $paytypes = user_helper::get_payment_types() ?>
				<? foreach($payments as $p){ ?>
					<? $item = user_payments_peer::instance()->get_item($p) ?>
					<? $pay[$item['type']][] = $item ?>
				<? } ?>
			<? } ?>
			<? if(count($pay[5])>0){ ?>
				<? foreach($pay[5] as $p){ ?>
					<?// $total[5] += $p['summ'] ?>
					<? include 'payment.php'; ?>
				<? } ?>
			<? } ?>
		</div>
		<? if(session::has_credential('admin') || request::get_int('id') == session::get_user_id()){ ?>
			<div class="mt10 fs12"><a href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=payments" style="text-decoration: underline"><?=t('Редактировать')?> &rarr;</a></div>
		<? } ?>
	</div>
	
	<div id="block-user-election-tasks" class="mt10">
		<div class="pane_election_head p5">
			<div class="left bold cbrown"><?=t('Задания для активиста')?></div>
			<div class="clear"></div>
		</div>
		<div id="block-user-election-tasks-empty" class="mt10 hide">
			<div class="acenter"><?=t('Еще нет информации')?></div>
		</div>
		<div id="block-user-election-tasks-show" class="mt10">
			<div class="mt10" style="margin-left: 20px;">
				<div id="coverInFacebook" class="left checkBox"></div>
				<div class="left ml10"><?=t('Поставить обложку в Facebook')?></div>
				<div id="linkCoverInFacebook" class="left ml10 hide"><a href="javascript:;"><?=t('Перейти')?> &rarr;</a></div>
				<div class="clear"></div>
			</div>
			<? if(session::has_credential('admin') || request::get_int('id') == session::get_user_id()){ ?>
				<div class="mt10 fs12"><a id="edit" href="javascript:;" style="text-decoration: underline"><?=t('Редактировать')?> &rarr;</a></div>
			<? } ?>
		</div>
		<div id="block-user-election-tasks-edit" class="mt10 hide">
			<div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<div>
							<input type="checkbox" id="coverInFacebook" />
							<label for="coverInFacebook"><?=t('Поставить обложку в Facebook')?></label>
						</div>
						<div>
							<div id="block-coverInFacebook" class="hide" style="margin-left: 20px">
								<div class="cgray"><?=t('Вставьте, пожалуйста, линк на Ваш профиль в Facebook')?></div>
								<div>
									<?=t('Ссылка')?>: <input type="text" id="linkCoverInFacebook" />
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mt5">
					<div class="left" style="width: 20px;">&nbsp;</div>
					<div class="left">
						<input type="button" id="save" value="<?=t('Сохранить')?>" />
						<input type="button" id="cancel" value="<?=t('Отмена')?>" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<? if(in_array(session::get_user_id(), array(11752, 2))){ ?>
		<? include "election/command_member.php" ?>
	<? } ?>
</div>

<script type="text/javascript">
	var getItem;
	
	$(document).ready(function(){
		$('#block-admin-election-data #expander').click(function(){
			if( ! $('#block-admin-election-data-empty-group').is(':visible'))
				$('#block-admin-election-data-empty-group').show();
			else
				$('#block-admin-election-data-empty-group').hide();
		});
		
		$("#block-user-election-tasks-edit input[type='checkbox']").change(function(){
			var id = $(this).attr("id");
			var state = $(this).attr("checked") ? true : false
			
			if(state)
				$("#block-user-election-tasks-edit #block-"+$(this).attr("id")).show();
			else
			{
				$("#block-user-election-tasks-edit #block-"+$(this).attr("id")+" input[type=text]").val('');
				$("#block-user-election-tasks-edit #block-"+$(this).attr("id")).hide();
			}
		});
		
		$("#block-user-election-tasks-edit #save").click(function(){
			var data = new Object();
			
			$("input[type='text'], input[type='checkbox']", $('#block-user-election-tasks-edit')).each(function(){
				var id = $(this).attr('id');
				
				var value = $(this).val();
				if($(this).attr('type') == 'checkbox')
					value = $(this).attr('checked') ? 1 : 0;
				
				data[id] = value;
			});
			
			$.post('/profile/user_voter', {
				'act': 'save_task',
				'user_id': '<?=request::get_int('id')?>',
				'data': data
			}, function(response){
				if(response.success)
				{
					getItem();
					$('#block-user-election-tasks-edit #cancel').click();
				}
			}, 'json');
		});
		
		$("#block-user-election-tasks #edit").click(function(){
			$('#block-user-election-tasks-show').hide();
			$('#block-user-election-tasks-edit').show();
		});
		
		$("#block-user-election-tasks-edit #cancel").click(function(){
			$('#block-user-election-tasks-show').show();
			$('#block-user-election-tasks-edit').hide();
		});
		
		<? if(session::get_user_id() == request::get_int('id') || session::has_credential('admin')){ ?>
		$('#budut_golosovat, #block-budut_golosovat').click(function(){
			if($('#info_statistics #info_list-budut_golosovat').is(':visible'))
			{
				$('#info_statistics #info_list-budut_golosovat').hide();
				$('#info_statistics #show_more-budut_golosovat').parent().hide();
			}
			else
			{
				$('#info_statistics #info_list-budut_golosovat').show();
				$('#info_statistics #info_list-budut_golosovat > div').hide();
				$('#info_statistics #show_more-budut_golosovat')
					.click()
					.parent().show();
			}
		});
		
//		show_more-budut_golosovat
		$('#info_statistics #show_more-budut_golosovat').click(function(){
			var step = 20;
			var i = 0;
			$('#info_statistics #info_list-budut_golosovat > div').each(function(){
				if(i >= step)
					return;
				
				if($(this).is(':visible'))
					return;
				
				$(this)
					.show()
					.css('opacity', 0)
					.animate({
						'opacity': 1
					}, 256);
				
				i++;
			});
			
			if($('#info_statistics #info_list-budut_golosovat > div:hidden').length == 0)
				$(this).parent().hide();
		});
		<? } ?>
		
		<? if(session::get_user_id() == request::get_int('id') || session::has_credential('admin')){ ?>
		$('#ewe_ne_opredelilsya, #block-ewe_ne_opredelilsya').click(function(){
			if($('#info_statistics #info_list-ewe_ne_opredelilsya').is(':visible'))
				$('#info_statistics #info_list-ewe_ne_opredelilsya').hide();
			else
				$('#info_statistics #info_list-ewe_ne_opredelilsya').show();
		});
		<? } ?>
		
		$('#block-user-election-informator #info_add').click(function(){
			$('#block-user-election-informator-list').hide();
			$('#block-user-election-informator-add').show();
			
			$('#block-user-election-informator-add #first_name').val('');
			$('#block-user-election-informator-add #middle_name').val('');
			$('#block-user-election-informator-add #last_name').val('');
			$('#block-user-election-informator-add #country').val(0);
			$('#block-user-election-informator-add #region').val(0);
			$('#block-user-election-informator-add #city').val(0);
			$('#block-user-election-informator-add #info_type').val(1);
			$('#block-user-election-informator-add #info_email').val('');
			$('#block-user-election-informator-add #info_result').val(1);
		});
		
		$('#block-user-election-informator-add #info_save').click(function(){
			var data = new Object();
			
			$("input[type='text'], select", $('#block-user-election-informator-add')).each(function(){
				var id = $(this).attr('id');
				var value = $(this).val();
				
				data[id] = value;
			});
			
			$.post('/profile/user_voter', {
				'act': 'add_info',
				'user_id': '<?=request::get_int('id')?>',
				'data': data
			}, function(response){
				if(response.success)
				{
					getItem();
					$('#block-user-election-informator-add #info_cancel').click();
				}
			}, 'json');
		});
		
		$('#block-user-election-informator-add #info_cancel').click(function(){
			$('#block-user-election-informator-list').show();
			$('#block-user-election-informator-add').hide();
		});
		
		var buildList = function(data)
		{
			$('#block-user-election-informator-list #info_list-budut_golosovat').empty();
			$('#block-user-election-informator-list #info_list-ewe_ne_opredelilsya').empty();
			
			var meeting = 0;
			var calls = 0;
			var budut_golosovat = 0;
			var ewe_ne_opredelilsya = 0;
			
			for(var i in data)
			{
				var kuda = '';
				
				var contact = data[i].contacts[data[i].contacts.length - 1];
				
				if(contact.type == 1)
					calls++;
				
				if(contact.type == 2)
					meeting++;
				
				var type = $('#block-user-election-informator-add #info_type > option[value='+contact.type+']').html();
				var month = $('#block-user-election-informator-add #info_date_month > option[value='+contact.date_month+']').html();
				
				var result = $('#block-user-election-informator-add #info_result > option[value='+contact.result+']').html();
				if(contact.result == 1)
				{
					kuda = 'budut_golosovat';
					budut_golosovat++;
				}
				
				if(contact.result == 2)
				{
					kuda = 'ewe_ne_opredelilsya';
					ewe_ne_opredelilsya++;
				}
				
				var date = contact.date_day+" "+month+" "+contact.date_year;
				
				var div = $('<div />').addClass('mb10');
				
				var voter_info = data[i].name;
				voter_info += (data[i].email ? ', ' + data[i].email : '');
				voter_info += (data[i].address ? ', ' + data[i].address : ''); 
				voter_info += (data[i].work_phone ? ', ' + data[i].work_phone : '');
				voter_info += (data[i].home_phone ? ', ' + data[i].home_phone : '');
				$(div).append(
					$('<span />')
						.addClass('bold')
						.html(voter_info)
				);
				
				$(div).append($('<span />').html(', '+type+' - '));
				
				$(div).append(
					$('<span />')
						.css('color', 'gray')
						.html(date)
				);
				
				/* $(div).append(
					$('<span />').html(' - '+result)
				); */
				
				<? if( session::has_credential('admin') || request::get_int('id') == session::get_user_id()){ ?>
					$(div).append(
						$('<br />')
					);

					$(div).append(
						$('<a />')
							.attr('href', 'javascript:'+i+';')
							.html('<?=t('Изменить')?> ')
					);

					$(div).append(
						$('<span />').html(' / ')
					);

					$(div).append(
						$('<a />')
							.attr({'href': 'javascript:;', 'rel': i})
							.click(function(){
								var clone = $('#block-user-election-informator-addcontact-tmpl').clone();
								$(this).parent().after($(clone));
								$(clone)
									.attr('id', 'block-user-election-informator-addcontact')
									.show();

								var index = $(this).attr('rel');

								$('#info_addcontact', clone).click(function(){
									addContact(index);
								});

								$('#info_cancelcontact', clone).click(function(){
									cancelContact();
								});
							})
							.html('<?=t('Добавить повторный контакт')?> ')
					);
					
					$(div).append(
						$('<span />').html(' / ')
					);
					
					$(div).append(
						$('<a />')
							.attr({'href': 'javascript:;', 'rel': i})
							.click(function(){
								var index = $(this).attr('rel');
								if(confirm('<?=t('Вы действительно хотите удалить этот контакт?')?>'))
								{
									$.post('/profile/user_voter', {
										'act': 'remove_contact',
										'user_id': '<?=request::get_int('id')?>',
										'index': index
									}, function(response){
										console.log(response);
										getItem();
									}, 'json');
								}
							})
							.html('<?=t('Удалить')?> ')
					);
				<? } ?>
				
				$('#block-user-election-informator-list #info_list-'+kuda).append($(div));
			}
			
			$('#info_head label').html(data.length);
			
			$('#info_statistics #meeting').html(meeting);
			$('#info_statistics #calls').html(calls);
			$('#info_statistics #budut_golosovat').html(budut_golosovat);
			$('#info_statistics #ewe_ne_opredelilsya').html(ewe_ne_opredelilsya);
			$('#info_statistics #total').html(data.length);
		}
		
		var addContact = function(index){
			var data = new Object();
			
			$("input[type='text'], select", $('#block-user-election-informator-addcontact')).each(function(){
				var id = $(this).attr('id');
				var value = $(this).val();
				
				data[id] = value;
			});
			
			$.post('/profile/user_voter', {
				'act': 'add_contact',
				'user_id': '<?=request::get_int('id')?>',
				'index': index,
				'data': data
			}, function(response){
				if(response.success)
				{
					getItem();
					cancelContact();
				}
			}, 'json');
		}
		
		var cancelContact = function(){
			$('#block-user-election-informator-addcontact').remove();
		}
		
		$("#block-user-election-data-edit #save").click(function(){
			var data = new Object();
			
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
				'user_id': '<?=request::get_int('id')?>',
				'data': data
			}, function(response){
				if(response.success)
				{
					getItem();
					$('#block-user-election-data-edit #cancel').click();
				}
			}, 'json');
		});
		
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
		});
		
		$("#block-user-election-data #edit").click(function(){
			$('#block-user-election-data-show').hide();
			$('#block-user-election-data-edit').show();
		});
		
		$("#block-user-election-data-edit #cancel").click(function(){
			$('#block-user-election-data-show').show();
			$('#block-user-election-data-edit').hide();
		});
		
		$("#block-admin-election-data-edit #save").click(function(){
			var data = new Object();
			
			$("input[type='text'], input[type='checkbox']", $('#block-admin-election-data-edit')).each(function(){
				var id = $(this).attr('id');
				
				var value = $(this).val();
				if($(this).attr('type') == 'checkbox')
					value = $(this).attr('checked') ? 1 : 0;
				
				data[id] = value;
			});
			
			$.post('/profile/user_voter', {
				'act': 'save',
				'type': 'admin',
				'user_id': '<?=request::get_int('id')?>',
				'data': data
			}, function(response){
				if(response.success)
				{
					getItem();
					$('#block-admin-election-data-edit #cancel').click();
				}
			}, 'json');
		});
		
		$("#block-admin-election-data-edit input[type='checkbox']").change(function(){
			var id = $(this).attr("id");
			var state = $(this).attr("checked") ? true : false
			
			if(state)
				$("#block-admin-election-data-edit #block-"+$(this).attr("id")).show();
			else
			{
				$("#block-admin-election-data-edit #block-"+$(this).attr("id")+" input[type=text]").val(0);
				$("#block-admin-election-data-edit #block-"+$(this).attr("id")).hide();
			}
		});
		
		$("#block-admin-election-data #edit").click(function(){
			$('#block-admin-election-data-show').hide();
			$('#block-admin-election-data-edit').show();
		});
		
		$("#block-admin-election-data-edit #cancel").click(function(){
			$('#block-admin-election-data-show').show();
			$('#block-admin-election-data-edit').hide();
		});
		
		getItem = function()
		{
			$.post('/profile/user_voter', {
				'act': 'get_item',
				'user_id': '<?=request::get_int('id')?>'
			}, function(response){
				if( ! response.success)
					return;
				
				var blocks = new Array('user', 'admin', 'tasks');
				for(var key in blocks)
				{
					var _key = blocks[key]+'_data';
					
					var block = 'data';
					if(blocks[key] == 'tasks')
					{
						block = blocks[key];
						blocks[key] = 'user';
					}
					
					var cnt = 0;
					
					if(typeof response.data != 'undefined')
					{
						for(var i in response.data[_key])
						{
							var _val = response.data[_key][i];
							var sEl = $('#block-'+blocks[key]+'-election-'+block+'-show #'+i);
							var eEl = $('#block-'+blocks[key]+'-election-'+block+'-edit #'+i);

							if($(eEl).attr('type') != 'checkbox')
							{
								$(eEl).val(_val);
								
								if($(sEl).attr('id') == 'linkCoverInFacebook' && _val != '')
								{
									
									$(sEl)
										.show()
										.html('<a href="'+_val+'"><?=t('Перейти')?> &rarr;</a>');
								}
								
								if(_val > 0)
								{
									$(sEl).show();
									$('label', sEl).html(_val);
								}
							}
							else
							{
								if(_val > 0)
								{
									$(sEl).addClass('checkBoxSelected');
									cnt++;
								}
								else
								{
									$(sEl).removeClass('checkBoxSelected');
								}
								$(eEl)
									.attr('checked', _val > 0 ? true : false)
									.change();
							}
						}
					}
					
					$('#block-'+blocks[key]+'-election-'+block+'-show .checkBox').each(function(){
						if( ! $(this).hasClass('checkBoxSelected'))
							$(this).parent().hide();
						else
							$(this).parent().show();
					});
					
					$('#block-'+blocks[key]+'-election-'+block+'-empty').hide();
					
					if((cnt < 1 && blocks[key] == 'user') || (cnt < 2 && blocks[key] == 'admin'))
					{
						<? if(session::has_credential('admin') || session::get_user_id() == request::get_int('id')){ ?>
							$('#block-'+blocks[key]+'-election-'+block+' #edit').click()
						<? } else { ?>
							$('#block-'+blocks[key]+'-election-'+block+'-empty').show()
						<? } ?>
					}
				}
				
				if(typeof response.data != 'undefined')
					buildList(response.data.informator);
					
				buildMoneyCollectionList("madeAContribution", response.data.money_collection);
				buildMoneyCollectionList("gaveAnAgreement", response.data.money_collection);
				buildMoneyCollectionList("planToTalk", response.data.money_collection);
				buildMoneyCollectionList("leadGaveAnAgreement", response.data.opinion_leaders);
				buildMoneyCollectionList("leadPlanToTalk", response.data.opinion_leaders);
				buildMoneyCollectionList("agentsGaveAnAgreement", response.data.agents_influence);
				buildMoneyCollectionList("agentsPlanToTalk", response.data.agents_influence);
			}, 'json');
		}
		
		getItem();
	});
</script>
