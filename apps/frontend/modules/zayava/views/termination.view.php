<div id="statement" class="mt10">
	<h1 class="column_head">
		Заява про добровільне припинення членства у політичній партії «Мерітократична партія України»
	</h1>
	<div class="aright mt10">
		<p>
			Голові політичної партії<br />
			«Мерітократична партія України»
		</p>
		<p>
			С. В. Коннову
		</p>
		<p>
			Члена політичної партії<br />
			«Мерітократична партія України»
		</p>
		<p>
			<? load::model("user/user_data") ?>
			<? $user_id = session::get_user_id(); ?>
			<? if(request::get_int("user_id")){ ?>
				<? $user_id = request::get_int("user_id"); ?>
			<? } ?>
			<? $user_data = user_data_peer::instance()->get_item($user_id) ?>
			<? $name_arr = array(
					$user_data["last_name"],
					$user_data["first_name"],
					$user_data["father_name"],
				); ?>
			<?=implode(" ", $name_arr)?>
		</p>
	</div>
	<div class="acenter bold">
		<p>
			ЗАЯВА<br />
			про добровільне припинення членства<br />
			у політичній партії «Мерітократична партія України»
		</p>
	</div>
	<div>
		<p style="
			 text-align: justify;
			 text-indent: 40px;
			">
			Цією заявою повідомляю, що припиняю своє членство у політичній партії «Мерітократична партія України» з моменту подання оригіналу даної заяви з власноручним підписом до уповноваженого органу політичної партії «Мерітократична партія України».
		</p>
	</div>
	<div class="acenter">
		<input type="button" class="button" value=" <?=t('Подать заявление')?> " id="send_statement" />
	</div>
</div>

<div id="statement-success" class="mt10 hide">
	<h1 class="column_head">
		Заява про добровільне припинення членства у політичній партії «Мерітократична партія України»
	</h1>
	<div align="center">
		<div class="mt10 p10" style="background: #ccc; width: 384px; border-radius: 7px;">
			<div class="p10" style="background: #fff; border-radius: 5px;">
				<p style="
					 text-align: justify;
					 text-indent: 40px;
					">
					Ваша он-лайн заява прийнята. Для завершення процесу припинення членства в МПУ Вам необхідно <a href="/zayava/termination?act=print"><b>роздрукувати</b></a>, <b>підписати</b> і надіслати до Секретаріату МПУ поштою або передати особисто <b>оригінал</b> цієї заяви.
				</p>
				<p>
					Адреса Секретаріату МПУ: вул. Костянтинівська 2а, 5-й поверх, м.Київ, 04071.
				</p>
				<input type="button" class="button" value=" <?=t('Распечатать заявление')?> " id="print_statement" />
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
	$("#print_statement").click(function(){
		window.location = "/zayava/termination?user_id=<?=$user_id?>&act=print";
	});
	
	$("#send_statement").click(function(){
		$.post("/zayava/termination",
			{
				"act": "send_statement",
				"user_id" : <?=$user_id?>
			},
			function(data){
				if(data.success){
					$("#statement").animate(
						{
							"opacity": "0"
						},
						256,
						function(){
							$(this).hide()
						}
					);
					$("#statement-success").show()
						.css("opacity", "0")
						.animate(
							{
								"opacity": "1"
							},
							256
						);
				}
			},
			"json"
		);
	});
	
	<? if($statement_data && $statement_data["visible"]) { ?>
		$("#send_statement").click();
	<? } ?>
	
</script>