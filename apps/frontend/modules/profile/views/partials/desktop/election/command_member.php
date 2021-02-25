<div id="block-election-tasksForCommandMember" class="mt10">
	<div class="pane_election_head p5">
		<? if (session::has_credential('admin')) { ?>
			<div class="left mr5">
				<input type="checkbox" id="command_member"
				       <? if ($user_voter['command_member'] != 0){ ?>checked="checked"<? } ?>/>
			</div>
		<? } ?>
		<div class="left bold cbrown"><?= t('Задания для члена комманды') ?></div>
		<div class="clear"></div>
	</div>
	<div id="block-election-tasksForCommandMember-edit" class="mt10">
		<!--<div class="mt5">
			<div class="left" style="width: 20px;">&nbsp;</div>
			<div class="left">
				<div>
					<input type="checkbox" id="coverInFacebook" />
					<label for="coverInFacebook"><?= t('Поставить обложку в Facebook') ?></label>
				</div>
				<div>
					<div id="block-coverInFacebook" class="hide" style="margin-left: 20px">
						<div class="cgray"><?= t('Вставьте, пожалуйста, линк на Ваш профиль в Facebook') ?></div>
						<div>
							<?= t('Ссылка') ?>: <input type="text" id="linkCoverInFacebook" />
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>-->
		<div class="mt5">
			<div class="left" style="width: 20px;">&nbsp;</div>
			<div class="left">
				<div>
					<input type="checkbox" id="coverInMeritokrat"
					       <? if ($user_voter['command_member_tasks']["coverInMeritokrat"] != 0){ ?>checked="checked"<? } ?> />
					<label for="coverInMeritokrat"><?= t('Портретное фото в профиле в сети Меритократ') ?></label>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mt5">
			<div class="left" style="width: 20px;">&nbsp;</div>
			<div class="left">
				<div>
					<input type="checkbox" id="biography"
					       <? if ($user_voter['command_member_tasks']["biography"] != 0){ ?>checked="checked"<? } ?> />
					<label for="biography"><?= t('Заполнена биография в профиле в Меритократе') ?></label>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="mt10 cbrown bold p5"><?= t("Сбор денег в избирательный фонд (от социальных инвесторов)") ?></div>
		<!--<div class="mt10">
			<div class="left" style="width: 20px;">&nbsp;</div>
			<div class="left">
				<div>
					<input type="checkbox" id="personalContribution" />
					<label for="personalContribution"><?= t('Личный взнос') ?></label>
				</div>
				<div>
					<div id="block-personalContribution" class="hide" style="margin-left: 20px">
						<div class="cgray"><?= t('Укажите, пожалуйста, сумму, которую Вы готовы внести') ?></div>
						<div>
							<?= t('Сумма') ?>: <input type="text" id="countPersonalContribution" />
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>-->
		<div class="mt5" style="margin-left: 20px">
			<table>
				<tr>
					<td width="30%" class="bold"><?= t("Уже сделали взнос") ?></td>
					<td width="10%"><label id="label-count-madeAContribution" class="bold">0</label></td>
					<td width="60%">
						<div><a id="addMadeAContribution" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-madeAContribution" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("Сумма") ?>:</div>
								<div class="left"><input type="text" id="count"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-madeAContribution" style="margin-left: 20px"></div>
					</td>
				</tr>
				<tr>
					<td class="bold"><?= t("Дали согласие") ?></td>
					<td><label id="label-count-gaveAnAgreement" class="bold">0</label></td>
					<td>
						<div><a id="addGaveAnAgreement" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-gaveAnAgreement" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("Сумма") ?>:</div>
								<div class="left"><input type="text" id="count"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-gaveAnAgreement" style="margin-left: 20px"></div>
					</td>
				</tr>
				<tr>
					<td class="bold"><?= t("Планирую поговорить") ?></td>
					<td><label id="label-count-planToTalk" class="bold">0</label></td>
					<td>
						<div><a id="addPlanToTalk" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-planToTalk" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-planToTalk" style="margin-left: 20px"></div>
					</td>
				</tr>
			</table>
		</div>

		<div class="mt10 cbrown bold p5"><?= t("Привлеченные лидеры мнений") ?></div>
		<div style="margin-left: 20px">
			<table>
				<tr>
					<td width="30%" class="bold"><?= t("Уже дали согласие") ?></td>
					<td width="10%"><label id="label-count-leadGaveAnAgreement" class="bold">0</label></td>
					<td width="60%">
						<div><a id="addLeadGaveAnAgreement" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-leadGaveAnAgreement" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("Кто этот человек") ?>:
								</div>
								<div class="left">
									<textarea id="description" style="width: 200px;"></textarea>
								</div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-leadGaveAnAgreement" style="margin-left: 20px"></div>
					</td>
				</tr>
				<tr>
					<td width="30%" class="bold"><?= t("Планирую поговорить") ?></td>
					<td width="10%"><label id="label-count-leadPlanToTalk" class="bold">0</label></td>
					<td width="60%">
						<div><a id="addLeadPlanToTalk" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-leadPlanToTalk" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("Кто этот человек") ?>:
								</div>
								<div class="left">
									<textarea id="description" style="width: 200px;"></textarea>
								</div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-leadPlanToTalk" style="margin-left: 20px"></div>
					</td>
				</tr>
			</table>
		</div>

		<div class="mt10 cbrown bold p5"><?= t("Привлеченные агенты влияния") ?></div>
		<div style="margin-left: 20px">
			<table>
				<tr>
					<td width="30%" class="bold"><?= t("Уже дали согласие") ?></td>
					<td width="10%"><label id="label-count-agentsGaveAnAgreement" class="bold">0</label></td>
					<td width="60%">
						<div><a id="addAgentsGaveAnAgreement" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-agentsGaveAnAgreement" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("Кто этот человек") ?>:
								</div>
								<div class="left">
									<textarea id="description" style="width: 200px;"></textarea>
								</div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-agentsGaveAnAgreement" style="margin-left: 20px"></div>
					</td>
				</tr>
				<tr>
					<td width="30%" class="bold"><?= t("Планирую поговорить") ?></td>
					<td width="10%"><label id="label-count-agentsPlanToTalk" class="bold">0</label></td>
					<td width="60%">
						<div><a id="addAgentsPlanToTalk" href="javascript:;"><?= t("Добавить") ?> &rarr;</a></div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="block-agentsPlanToTalk" class="hide mb10">
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("ФИО") ?>:</div>
								<div class="left"><input type="text" id="name"/></div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5 mt5" style="width: 100px;"><?= t("Кто этот человек") ?>:
								</div>
								<div class="left">
									<textarea id="description" style="width: 200px;"></textarea>
								</div>
								<div class="clear"></div>
							</div>
							<div>
								<div class="left aright mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="save" value="<?= t("Сохранить") ?>"/>
									<input type="button" id="cancel" value="<?= t("Отмена") ?>"/>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div id="block-list-agentsPlanToTalk" style="margin-left: 20px"></div>
					</td>
				</tr>
			</table>
		</div>

		<div class="mt5">
			<div class="left" style="width: 20px;">&nbsp;</div>
			<div class="left">
				<input type="button" id="save" value="<?= t('Сохранить') ?>"/>
				<input type="button" id="cancel" value="<?= t('Отмена') ?>"/>
			</div>
			<div class="clear"></div>
		</div>

	</div>
</div>

<script type="text/javascript">
	var buildMoneyCollectionList;

	$(document).ready(function () {
		$("#command_member").change(function () {
			var user_id = "<?=request::get_int("id")?>";
			var state = $(this).attr("checked") != false ? 1 : 0;

			$.post("/profile/user_voter", {
				"act": "set_command_member",
				"user_id": user_id,
				'state': state
			}, function (response) {
				if (response.success != true)
					return;

				if (state != 0)
					$("#block-election-tasksForCommandMember-edit").show();
				else
					$("#block-election-tasksForCommandMember-edit").hide();
			}, "json");
		});

		if ($("#command_member").attr("checked") != false)
			$("#block-election-tasksForCommandMember-edit").show();
		else
			$("#block-election-tasksForCommandMember-edit").hide();

		$("#addMadeAContribution, #addGaveAnAgreement, #addPlanToTalk, #addLeadGaveAnAgreement, #addLeadPlanToTalk, #addAgentsGaveAnAgreement, #addAgentsPlanToTalk").click(function () {
			var block;

			switch ($(this).attr("id")) {
				case "addMadeAContribution":
					block = "madeAContribution";
					break;

				case "addGaveAnAgreement":
					block = "gaveAnAgreement";
					break;

				case "addPlanToTalk":
					block = "planToTalk";
					break;

				case "addLeadGaveAnAgreement":
					block = "leadGaveAnAgreement";
					break;

				case "addLeadPlanToTalk":
					block = "leadPlanToTalk";
					break;

				case "addAgentsGaveAnAgreement":
					block = "agentsGaveAnAgreement";
					break;

				case "addAgentsPlanToTalk":
					block = "agentsPlanToTalk";
					break;
			}

			$("#block-" + block).show();
			$("#block-" + block + " #name").val("");
			$("#block-" + block + " #count").val(0);
			$("#block-" + block + " #description").val("");
		});

		$("input[id='save']").click(function () {
			var blocks = new Array();
			blocks = [
				"block-madeAContribution", "block-gaveAnAgreement", "block-planToTalk",
				"block-leadGaveAnAgreement", "block-leadPlanToTalk", "block-agentsGaveAnAgreement",
				"block-agentsPlanToTalk"
			];

			var block_id = $(this).parent().parent().parent().attr("id");

			if (block_id == "block-election-tasksForCommandMember-edit") {
				save_command_member_tasks();
				return;
			}

			if ($.inArray(block_id, blocks) < 0)
				return;

			var data = new Object();

			var block = $(this).parent().parent().parent().attr("id").split("-")[1];

			$("input[type='text'], textarea", $('#block-' + block)).each(function () {
				var id = $(this).attr('id');

				var value = $(this).val();

				data[id] = value;
			});

			var act = "save_money_collection";

			if ($.inArray(block, ["leadGaveAnAgreement", "leadPlanToTalk"]) != -1)
				act = "save_opinion_leaders"

			if ($.inArray(block, ["agentsGaveAnAgreement", "agentsPlanToTalk"]) != -1)
				act = "save_agents_influence"

			$.post('/profile/user_voter', {
				'act': act,
				'type': block,
				'user_id': '<?=request::get_int('id')?>',
				'data': data
			}, function (response) {
				if (response.success) {
					getItem();
					$("#block-" + block + " #cancel").click();
				}
			}, 'json');
		});

		$("input[id='cancel']").click(function () {
			var blocks = new Array();
			blocks = [
				"block-madeAContribution", "block-gaveAnAgreement", "block-planToTalk",
				"block-leadGaveAnAgreement", "block-leadPlanToTalk", "block-agentsGaveAnAgreement",
				"block-agentsPlanToTalk"
			];

			var block_id = $(this).parent().parent().parent().attr("id");

			if ($.inArray(block_id, blocks) < 0)
				return;

			var block = $(this).parent().parent().parent().attr("id").split("-")[1];
			$("#block-" + block).hide();
		});

		$("#block-election-tasksForCommandMember-edit input[type='checkbox']").change(function () {
			var id = $(this).attr("id");
			var state = $(this).attr("checked") ? true : false

			if (state)
				$("#block-election-tasksForCommandMember-edit #block-" + $(this).attr("id")).show();
			else {
				$("#block-election-tasksForCommandMember-edit #block-" + $(this).attr("id") + " input[type=text]").val("");
				$("#block-election-tasksForCommandMember-edit #block-" + $(this).attr("id")).hide();
			}
		});

		buildMoneyCollectionList = function (field, data) {
			$("#block-list-" + field).empty();

			if (typeof data[field] == 'undefined')
				return false;

			var list = data[field];

			var length = 0;
			for (var i in list) {
				var css = new Object();
				if (length > 0)
					css = {"class": "mt5"}

				var row = $("<div />").attr(css);

				$(row).append(
					$("<span />")
						.css({'font-weight': 'bold', 'color': 'black'})
						.html(list[i].name)
				);

				if (typeof list[i].count != 'undefined')
					$(row).append($("<span />").html(" - " + list[i].count + " грн."));

				if (typeof list[i].description != 'undefined')
					$(row).append($("<span />").html(" - " + list[i].description));

				$(row).append($("<br />"));

				$(row).append(
					$("<a />")
						.attr({"href": "javascript:;", "class": "mr5"})
						.html("<?=t("Редактировать")?>")
				);

				$(row).append(
					$("<a />")
						.attr({"href": "javascript:;", 'rel': field + ':' + i})
						.html("<?=t("Удалить")?>")
						.click(function () {
							var rel = $(this).attr('rel').split(':');
							removeItem(rel[0], rel[1]);
						})
				);

				$("#block-list-" + field).append($(row));
				length++;
			}

			$("#label-count-" + field).html(length);
		}

		var removeItem = function (type, index) {
			if (confirm("<?=t("Вы действительно хотите удалить?")?>")) {
				var act = "remove_money_collection";

				if ($.inArray(type, ["leadGaveAnAgreement", "leadPlanToTalk"]) != -1)
					act = "remove_opinion_leaders";

				if ($.inArray(type, ["agentsGaveAnAgreement", "agentsPlanToTalk"]) != -1)
					act = "remove_agents_influence";

				$.post('/profile/user_voter', {
					'act': act,
					'user_id': '<?=request::get_int("id")?>',
					'type': type,
					'index': index
				}, function (response) {
					if (response.success)
						getItem();
				}, 'json');
			}
		}

		var save_command_member_tasks = function () {
			var tasks = $("#block-election-tasksForCommandMember-edit");

			var data = new Object();

//			data["coverInFacebook"] = $("#coverInFacebook", tasks).attr("checked") != false ? 1 : 0;
//			if($("#coverInFacebook", tasks).attr("checked") != false && $("#linkCoverInFacebook", tasks).val() != "")
//				data["linkCoverInFacebook"] = $("#linkCoverInFacebook", tasks).val();
//			else
//				data["linkCoverInFacebook"] = "";

			if ($("#coverInMeritokrat", tasks).length != 0)
				data["coverInMeritokrat"] = $("#coverInMeritokrat", tasks).attr("checked") != false ? 1 : 0;
			else
				data["coverInMeritokrat"] = <? if($user_voter["command_member_tasks"]["coverInMeritokrat"]){ ?><?=$user_voter["command_member_tasks"]["coverInMeritokrat"]?><? } else { ?>0<? } ?>;

			if ($("#biography", tasks).length != 0)
				data["biography"] = $("#biography", tasks).attr("checked") != false ? 1 : 0;
			else
				data["biography"] = <? if($user_voter["command_member_tasks"]["biography"]){ ?><?=$user_voter["command_member_tasks"]["biography"]?><? } else { ?>0<? } ?>;

//			data["personalContribution"] = $("#personalContribution", tasks).attr("checked") != false ? 1 : 0;
//			if($("#personalContribution", tasks).attr("checked") != false && $("#countPersonalContribution", tasks).val() != "")
//				data["countPersonalContribution"] = parseInt($("#countPersonalContribution", tasks).val());
//			else
//				data["countPersonalContribution"] = 0;

			$.post("/profile/user_voter", {
				"act": "save_command_member_tasks",
				"user_id": "<?=request::get_int("id")?>",
				"data": data
			}, function (response) {
				console.log(response);
			}, "json");
		}
	});
</script>