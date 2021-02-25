<div class="left" style="width: 100%;">

            <h1 class="column_head">
                <a href="/blogs"><?=t('Публикации')?></a> &rarr;
                <a href="/blogpost<?=$post['id']?>"><?=mb_substr(stripslashes(htmlspecialchars($post['title'])),0,70)?></a>
            </h1>

	<div id="views-history" class="box fs12">
		<table>
			<tr>
				<? $cnt = 0; ?>
				<? $flag = true; ?>
				<? $mpu_cnt = 0; ?>
				<? for($i = 0; $i < count($viewers); $i++){ ?>
					<? $user_auth = user_auth_peer::instance()->get_item($viewers[$i]) ?>
					<? if($user_auth["status"] == 20){ ?>
						<? $mpu_cnt++ ?>
					<? } ?>
					<? if($cnt == 0){ ?>
						<td style="background: #<?= $flag ? "FFF" : "EEE" ?>;">
					<? } ?>
					<div style="height: 24px">
						<?=($i+1)?>. <?=user_helper::full_name($viewers[$i])?>
					</div>
					<? if($cnt == (int)(count($viewers)/4)){ ?>
						<? $cnt = 0; ?>
						<? $flag = $flag ? false : true; ?>
						</td>
					<? } else { ?>
						<? $cnt++ ?>
					<? } ?>
				<? } ?>
			</tr>
			<tr>
				<td colspan="4">
					<?=t('Просмотрели')?>: <strong><?=$post["views"]?></strong> (<?=t("из них")?> <strong><?=$post["views"]-count($viewers)?></strong> <?=t("незарегистрированные")?>, <strong><?=$mpu_cnt?></strong> <?=t('членов МПУ')?>)
				</td>
			</tr>
		</table>
	</div>
</div>