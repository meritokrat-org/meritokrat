<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
	<head>
		<title>Друк заяви</title>
		<script type="text/javascript" src="/static/javascript/jquery/jquery-1.4.2.js"></script>
		<?=tag_helper::css('system.css') ?>
	</head>
	<body class="p10">
		<div>
			<input type="button" class="button mr15" value=" <?=t('ДРУКУВАТИ')?> " id="print_statement" />
		</div>
		<div class="mt10" style="width: 800px">
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
					<? $user_data = user_data_peer::instance()->get_item($statement_data["user_id"]) ?>
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
			<div align="center">
				<p>
					<div class="left" style="margin-left: 64px">Дата: ________________________</div>
					<div class="right" style="margin-right: 64px">Підпис: ________________________</div>
					<div class="clear"></div>
				</p>
			</div>
		</div>
		<script type="text/javascript">
			
			$("#print_statement").click(function(){
				$(this).parent().hide();
				print();
			});
			
		</script>
	</body>
</html>