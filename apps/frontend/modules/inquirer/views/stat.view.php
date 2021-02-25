<style type="text/css">
	ul {
		list-style-type: none;
	}
</style>

<div class="column_head">
	Statistics :: <?=$inquirer["name"]?>
</div>

<?  ?>

<div class="box_content p10">
	<? foreach($stat as $questions => $answers){ ?>
		<div class="p10" style="background: #EEE">
			<ul>
				<li style="font-weight: bold;"><?=$questions?></li>
				<li>
					<ul>
						<? foreach($answers as $answer => $val){ ?>
							<li>
								<div class="p2">
									<div style="position: absolute; z-index: 1001; padding-left: 8px; color: #FFF; font-weight: bold">
										<?=$answer?>
									</div>
									<div style="position: absolute; width: <?= $procent[$questions][$answer] * 480 / 100 ?>px; height: 24px; background: #600;"></div>
									<div class="left" style="width: 480px; background: #AAA; height: 24px;"></div>
									<div class="left" style="padding-left: 8px"><?=number_format($procent[$questions][$answer], 1, '.', '')?>% (ответов: <?=$val?>)</div>
									<div class="clear"></div>
									<div style="font-size: 10px;">
										<?=implode(", ", $users[$questions][$answer])?>
									</div>
								</div>
							</li>
						<? } ?>
					</ul>
				</li>
			</ul>
		</div>
	<? } ?>
</div>

<div class="box_content p10">
	<input type="button" class="button" value=" Back " onclick="window.location = '/inquirer/';" />
</div>