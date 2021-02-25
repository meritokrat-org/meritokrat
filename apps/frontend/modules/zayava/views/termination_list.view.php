<? include "partials/navigation.view.php" ?>

<h1 class="column_head">
	<?=t('Заявления на выход')?> : <?=$count_of_list?>
</h1>

<style type="text/css">
	table.termination tr th, table.termination tr td {
		border: 1px solid #ddd;
		vertical-align: middle;
		color: #000;
	}
</style>

<table class="termination">
	<tr>
		<th rowspan="2">ID</th>
		<th rowspan="2">ПІБ</th>
		<th rowspan="2">Дата подання онлайн заяави</th>
		<th colspan="2">Оригінал</th>
		<th rowspan="2">Дії</th>
	</tr>
	<tr>
		<th>Підтвердження</th>
		<th>Дата надходження</th>
	</tr>
	<? $num = 1; ?>
	<? foreach($list as $item){ ?>
		<tr id="statement-item-<?=$item["id"]?>">
			<td align="center"><?=$item["id"]?></td>
			<? load::model("user/user_data"); ?>
			<? $user_data = user_data_peer::instance()->get_item($item["user_id"]); ?>
			<td align="center">
				<? $name_arr = array(
						$user_data["last_name"],
						$user_data["first_name"],
						$user_data["father_name"],
					); ?>
				<a href="/profile-<?=$item["user_id"]?>"><?=implode(" ", $name_arr)?></a>
			</td>
			<td align="center">
				<?=$item["date_created"]?>
			</td>
			<td align="center">
				<?// if(!$item["confirmation"]){ ?>
					<input type="checkbox" id="confirm-statement-<?=$item["id"]?>" <?= $item["confirmation"] ? "checked" : "" ?> />
				<?// } else { ?>
					<!--<img src="/static/images/icons/check.png" />-->
				<?// } ?>
			</td>
			<td align="center" id="date-confirmation-<?=$item["id"]?>">
				<? if($item["date_confirmation"] != ""){ ?>
					<?=$item["date_confirmation"]?>
				<? } else { ?>
					<?=user_helper::datefields('statement-date-confirmation', 0) ?>
				<? } ?>
			</td>
			<td width="45px">
				<div
					class="left ml10"
					style="
						width: 1px;
						height: 1px;
					">
				</div>
				<? if(!$item["confirmation"]){ ?>
					<div
						class="left"
						style="
							background: url(/static/images/icons/apply.png) 100% 100% no-repeat;
							background-size: cover;
							width: 16px;
							height: 24px;
							<? if(!$item["date_confirmation"]){ ?>
								opacity: .25;
								filter: alpha(opacity=25);
							<? } ?>
						"
						id="apply-statement-<?=$item["id"]?>">
					</div>
				<? } ?>
				<div
					class="left"
					style="
						background: url(/static/images/icons/to_trash.png) 100% 100% no-repeat;
						background-size: cover;
						width: 16px;
						height: 24px;
					"
					id="remove-statement-<?=$item["id"]?>">
				</div>
				<div class="clear"></div>
			</td>
		</tr>
	<? } ?>
</table>

<script type="text/javascript">
	
	$("input[id^='confirm-statement']").click(function(){
		var id = $(this).attr("id").split("-")[2];
		if($(this).attr("checked")){
			$("#apply-statement-"+id).animate(
				{
					"opacity": "1"
				},
				256
			);
		} else {
			$("#apply-statement-"+id).animate(
				{
					"opacity": ".25"
				},
				256
			);
		}
	});
	
	$("div[id^='apply-statement']").click(function(){
		var id = $(this).attr("id").split("-")[2];
		if(!$("#confirm-statement-"+id).attr("checked")){
			return false;
		}
		$.post("/zayava/termination",
			{
				"act": "confirm_statement",
				"statement-date-confirmation_day": $("#statement-date-confirmation_day").val(),
				"statement-date-confirmation_month": $("#statement-date-confirmation_month").val(),
				"statement-date-confirmation_year": $("#statement-date-confirmation_year").val(),
				"id": id
			},
			function(data){
				if(data.success){
					$("#apply-statement-"+data.statement.id).hide();
					$("#date-confirmation-"+data.statement.id)
						.html(data.statement.date_confirmation);
				}
			},
			"json"
		);
	});
	
	$("div[id^='remove-statement']").click(function(){
		if(confirm("<?=t("Вы уверены, что хотите удалить эту запись?")?>")){
			var id = $(this).attr("id").split("-")[2];
			$.post("/zayava/termination",
				{
					"act": "remove_statement",
					"id": id
				},
				function(data){
					if(data.success){
						$("#statement-item-"+id).remove();
					}
				},
				"json"
			);
		}
	});
	
</script>