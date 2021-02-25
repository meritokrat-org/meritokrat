<? //<div class="content_pane mt10" id="pane_education">?>
    <table width="100%" class="fs12">
                    <? if ($user_education && @count(array_unique(@array_values($user_education)))>2 ) {?>
<tr>
                                <td class="bold" colspan="2"><div class="ml15 fs16"><?=t('Образование')?></div></td>
</tr>
<? }
if ( $user_education['midle_edu_country'] or $user_education['midle_edu_city'] or $user_education['midle_edu_name'] or $user_education['midle_edu_admission'] or $user_education['midle_edu_graduation']) { ?>

                        <tr>
                                <td class="bold aright" width="35%;"><?=t('Среднее')?> <?=t('образование')?></td>
                                <td style="color:#333333;">
        <? if ( $user_education['midle_edu_name']) { ?> &#8470; <?=stripslashes(htmlspecialchars($user_education['midle_edu_name']))?>, <? } if ( $user_education['midle_edu_city']) { ?> <?=stripslashes(htmlspecialchars($user_education['midle_edu_city']))?>,<? } if ( $user_education['midle_edu_country']) { ?> <?=stripslashes(htmlspecialchars($user_education['midle_edu_country']))?>,
        <? } if ( $user_education['midle_edu_admission']) { ?> <?=stripslashes(htmlspecialchars($user_education['midle_edu_admission']))?> <? } if ( $user_education['midle_edu_graduation']) { ?> - <?=stripslashes(htmlspecialchars($user_education['midle_edu_graduation']))?>
        <? } ?>
                                </td>
                        </tr>
        <? } if ( $user_education['smidle_edu_country'] or $user_education['smidle_edu_city'] or $user_education['smidle_edu_name'] or $user_education['smidle_edu_admission'] or $user_education['smidle_edu_graduation']) { ?>
                        <!-- Midle special -->
			<tr>
				<td class="bold aright" width="35%;"><?=t('Среднее')?> <?=t('специальное образование')?></td>
                                <td style="color:#333333;">
                                    <? if ( $user_education['smidle_edu_name']) { ?> <?=stripslashes(htmlspecialchars($user_education['smidle_edu_name']))?>,
                                    <? } if ( $user_education['smidle_edu_city']) { ?> <?=stripslashes(htmlspecialchars($user_education['smidle_edu_city']))?>,
                                    <? } if ( $user_education['smidle_edu_country']) { ?> <?=stripslashes(htmlspecialchars($user_education['smidle_edu_country']))?><br/>
                                    <? } if ( $user_education['smidle_edu_admission']) { ?> <?=stripslashes(htmlspecialchars($user_education['smidle_edu_admission']))?>
                                    <? } if ( $user_education['smidle_edu_graduation']) { ?> - <?=stripslashes(htmlspecialchars($user_education['smidle_edu_graduation']))?>
                                    <? } ?>
                                </td>
                        </tr>
       <? } if ( $user_education['major_edu_country'] or $user_education['major_edu_city'] or $user_education['major_edu_name'] or $user_education['major_edu_faculty'] or $user_education['major_edu_department'] or $user_education['major_edu_form'] or $user_education['major_edu_status'] or $user_education['major_edu_admission'] or $user_education['major_edu_graduation']) { ?>
                        <!-- Major university education -->
			<tr>
                                <td  class="bold aright" width="35%;"><?=t('Основное высшее образование')?></td>
                                <td style="color:#333333;">
        <? if ( $user_education['major_edu_name']) { ?> <?=stripslashes(htmlspecialchars($user_education['major_edu_name']))?>,
        <? } if ( $user_education['major_edu_city']) { ?> <?=stripslashes(htmlspecialchars($user_education['major_edu_city']))?>,
        <? } if ( $user_education['major_edu_country']) { ?> <?=stripslashes(htmlspecialchars($user_education['major_edu_country']))?><br/>
        <? } if ( $user_education['major_edu_faculty']) { ?> <?=stripslashes(htmlspecialchars($user_education['major_edu_faculty']))?>,
        <? } if ( $user_education['major_edu_department']) { ?> <?=stripslashes(htmlspecialchars($user_education['major_edu_department']))?>,
        <? } if ( $user_education['major_edu_form']) { ?> <?=user_education_peer::get_form($user_education['major_edu_form'])?><br/>
        <? } if ( $user_education['major_edu_status']) { ?> <?=user_education_peer::get_status($user_education['major_edu_status'])?>,
        <? } if ( $user_education['major_edu_admission']) { ?> <?=stripslashes(htmlspecialchars($user_education['major_edu_admission']))?>
        <? } if ( $user_education['major_edu_graduation']) { ?> - <?=stripslashes(htmlspecialchars($user_education['major_edu_graduation']))?>
        <? } ?>
                                </td>
                        </tr>
        <? } if ( $user_education['additional_edu_country'] or $user_education['additional_edu_city'] or $user_education['additional_edu_name'] or $user_education['additional_edu_faculty'] or $user_education['additional_edu_department'] or $user_education['additional_edu_form'] or $user_education['additional_edu_status'] or $user_education['additional_edu_admission'] or $user_education['additional_edu_graduation']) { ?>
                        <!-- additional university education -->
			<tr>
                                <td class="bold aright" width="35%;"><?=t('Дополнительное')?> <?=t('высшее образование')?></td>
                                <td style="color:#333333;">
        <? if ( $user_education['additional_edu_name']) { ?> <?=stripslashes(htmlspecialchars($user_education['additional_edu_name']))?>,
        <? } if ( $user_education['additional_edu_city']) { ?> <?=stripslashes(htmlspecialchars($user_education['additional_edu_city']))?>,
        <? } if ( $user_education['additional_edu_country']) { ?> <?=stripslashes(htmlspecialchars($user_education['additional_edu_country']))?><br/>
        <? } if ( $user_education['additional_edu_faculty']) { ?> <?=stripslashes(htmlspecialchars($user_education['additional_edu_faculty']))?>,
        <? } if ( $user_education['additional_edu_department']) { ?> <?=stripslashes(htmlspecialchars($user_education['additional_edu_department']))?>,
        <? } if ( $user_education['additional_edu_form']) { ?> <?=user_education_peer::get_form($user_education['additional_edu_form'])?><br/>
        <? } if ( $user_education['additional_edu_status']) { ?> <?=user_education_peer::get_status($user_education['additional_edu_status'])?>,
        <? } if ( $user_education['additional_edu_admission']) { ?> <?=stripslashes(htmlspecialchars($user_education['additional_edu_admission']))?>
        <? } if ( $user_education['additional_edu_graduation']) { ?> - <?=stripslashes(htmlspecialchars($user_education['additional_edu_graduation']))?>
                                </td>
                        </tr>
<? } ?>
        <? } if ( $user_education['another_edu']) { ?>
			<tr>
				<td class="bold aright" width="35%;"><?=t('Другое образование')?></td>
				<td style="color:#333333"><?=stripslashes(htmlspecialchars($user_education['another_edu']))?></td>
			</tr>

        <? } ?>

		</table>
<?//</div>?>