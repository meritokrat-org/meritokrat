<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10 fs12" id="rmcol" style="width: 62%;">
	<h1 class="column_head"><?=t('Выборы 2012')?></h1>
	
	<form id="election-form" action="/admin/election?act=save" method="post">
		<div class="mt10" style="margin-left: 100px;">
			<div class="left mr5" style="margin-top: 3px; width: 100px;"><?=t('Сумма')?>: </div>
			<div class="left">
				<input type="text" id="summa" name="summa" value="<?=$election_summa?>" class="text" />
			</div>
			<div class="clear"></div>
		</div>
		
		<? if(count($history) > 0){ ?>
		<div class="mt10" style="margin-left: 100px;">
			<div class="left mr5" style="width: 100px;"><?=t('Изменил')?>: </div>
			<div class="left">
				<div><?=user_helper::full_name($history[count($history)-1]['user_id'])?></div>
				<div><?=date('d.m.Y H:i', $history[count($history)-1]['time'])?></div>
			</div>
			<div class="clear"></div>
		</div>
		<? } ?>
		
		<div class="mt10" style="margin-left: 100px;">
			<div class="left mr5" style="width: 100px;">&nbsp;</div>
			<div class="left">
				<input type="submit" name="submit" value="<?=t('Сохранить')?>" />
			</div>
			<div id="msg-success" class="left ml5 hide" style="margin-top: 3px; color: green"><?=t('Данные успешно сохранены')?></div>
			<div class="clear"></div>
		</div>
	</form>
</div>

<div class="clear"></div>

<script>
	$(document).ready(function(){
		var form = new Form(
			'election-form',
			{
				success: function(response)
				{
					if(response.success)
						$('#msg-success').show();
					
					setTimeout(function(){
						$('#msg-success').hide();
					}, 2000);
				}
			}
		);
	});
</script>