<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>ЗАЯВА ПРО ВИПУСК НОВОГО ПАРТІЙНОГО КВИТКА</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="https://s1.meritokrat.org/system.css?1" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="/static/javascript/jquery/jquery-1.4.2.js"></script>
		<style type="text/css">
			a
			{
				color: black;
			}
		</style>
	</head>

	<body>
		<div style="width: 880px">
			<div style="text-align: right">
				<div>Голові Політичної партії</div>
				<div>«Мерітократична партія України»</div>
				<div>Коннову С. В.</div>
			</div>

			<div style="margin-top: 30px; text-align: center; font-size: 16px; font-weight: bold">
				<div>ЗАЯВА ПРО ВИПУСК НОВОГО ПАРТІЙНОГО КВИТКА</div>
			</div>

			<div style="margin-top: 30px">
				<div>Прошу здійснити перевипуск мого партійного квитка члена МПУ. Правильність наведених нижче особистих даних підтверджую.</div>
				<div style="margin-top: 15px; text-indent: 15px">1. <b><?=$data["user"]["last_name"]?> <?=$data["user"]["first_name"]?> <?=$data["user"]["father_name"]?></b></div>
				<div style="margin-top: 15px; text-indent: 15px">2. <b><?=$data["user"]["location"]?></b></div>
			</div>

			<div style="margin-top: 30px; font-style: italic; color: #888;">
				<div>Будь ласка, перевірте правильність наведених вище даних. Увага! ПІБ має бути заповнено українською мовою. Якщо дані потребують коригування, натисніть на кнопку «Редагувати дані», внесіть необхідні зміни і знову перейдіть за посиланням «Подати он-лайн заяву на перевипуск партквитка» у Вашому профілі.</div>
			</div>

			<div style="margin-top: 30px; text-align: center">
				<div><a href="javascript:;" id="send" class="button">НАДІСЛАТИ</a> <a href="/profile/edit?id=<?=$data["user"]["user_id"]?>" class="button">РЕДАГУВАТИ ДАНІ</a> <a href="/profile-<?=$data["user"]["user_id"]?>" class="button">СКАСУВАТИ</a></div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				$("a#send").click(function(){
					$.post("/zayava/vipusk", {
						act: "send_message"
					}, function(response){
						if( ! response.success)
							return;
						
						if(response.mtype == 0)
							alert("Дякуємо! Ваша заява на пере випуск партійного квитка прийнята.");
						else if(response.mtype == 1)
							alert("Дякуємо! Ваша заява на пере випуск партійного квитка прийнята. Зверніть увагу, що новий партквиток буде випущений за умови погашення Вами заборгованості зі сплати членських внесків. На даний момент у Вас існує заборгованість за таку кількість місяців: "+response.dolg);
						
						window.location = "/profile-<?=$data["user"]["user_id"]?>";
					}, "json");
				});
			});
		</script>
	</body>
</html>