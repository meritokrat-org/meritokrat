<div class="content_pane hide mt10" id="pane_interests">
<table width="100%" class="fs12">
<? if ( $user_data['interests'] ) { ?>
                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Интересы, увлечения')?></td>
				<td style="color:#333333">
					<?=stripslashes(htmlspecialchars($user_data['interests']))?>
				</td>
			</tr>
<? } if ( $user_data['books'] ) { ?>
                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Любимые книги')?></td>
				<td style="color:#333333">
					<?=stripslashes(htmlspecialchars($user_data['books']))?>

				</td>
			</tr>
<? } if ( $user_data['films'] ) { ?>
                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Любимые фильмы')?></td>
				<td style="color:#333333">
					<?=stripslashes(htmlspecialchars($user_data['films']))?>

				</td>
			</tr>
<? } if ( $user_data['sites'] ) { ?>
                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Любимые интернет-порталы')?></td>
				<td style="color:#333333">
					<?=stripslashes(htmlspecialchars($user_data['sites']))?>

				</td>
			</tr>
<? } if ( $user_data['music'] ) { ?>
                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Любимая музыка')?></td>
				<td style="color:#333333">
					<?=stripslashes(htmlspecialchars($user_data['music']))?>

				</td>
			</tr>
<? } if ( $user_data['leisure'] ) { ?>
                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Досуг')?></td>
				<td style="color:#333333">
					<?=stripslashes(htmlspecialchars($user_data['leisure']))?>

				</td>
			</tr>
<? } ?>
 		</table>
	</div>