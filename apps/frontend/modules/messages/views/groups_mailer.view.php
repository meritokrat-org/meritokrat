<script src ="/static/javascript/jquery/jquery-1.4.2.js"></script>      
<form id="send_form" name="send_form" class="form mt10" rel="<?= t('Начинайте вводить имя друга') ?>...">
	<table width="100%" class="fs12">
		<tr>
			<td class="aright"><?= t('Имя получателя') ?></td>
			<td class="aleft"><?=$type?> <?=$name?></td>
		</tr>
		<tr>
			<td class="aright"><?= t('Сообщение') ?></td>
			<td>
				<textarea id="body" rel="<?= t('Введите текст сообщения') ?>" name="body" style="width: 500px; height:150px;">Шановний учасник "<?=$name?>"
Запрошуємо взяти участь у події "<?=$event['name']?>" яка відбудеться <?=date_helper::get_format_date($event['start'])?>
				</textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="button" id="button-submit" name="submit" class="button" value=" <?=t('Отправить') ?> " />
				<input type="button" id="button-cancel" name="cancel" class="button_gray" value=" <?=t('Отмена') ?> " />
				<?= tag_helper::wait_panel() ?>
				<div class="success hidden mr10 mt10"><?= t('Сообщение отправлено') ?></div>
			</td>
		</tr>

	</table>
</form>
<script>
	$(document).ready(function(){	
		var event_id = <?=$event_id?>;
		
		$("#button-submit").click(function(){
			if($('#body').val() != ''){
				$.ajax({
					url: '/messages/groups_mailer',
					data: {
						'submit': true,
						'event_id': event_id,
						'body': $('#body').val()
					},
					success: function($response) {
						$('.success').fadeIn('250', function() {$('.success').fadeOut(2000);});
						setTimeout(closeWnd, 1000);
					}
				});
			} else {
				$('.success').html('Спочатку напишіть повідомлення').fadeIn('250', function() {$('.success').fadeOut(2000);});
			}
		});
		
		$("#button-cancel").click(function(){
			closeWnd1();
		});
		
		function closeWnd() {
			$('#send_form').parent().fadeOut(400);
			$('#mailer').remove();

		}
		
		function closeWnd1() {
			$('#send_form').parent().fadeOut(400);
		}
	});
</script>