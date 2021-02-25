<div class="content_pane mt10" id="pane_about">    
<table class="fs12 mt10">
	<? /* if ( $user_data['political_views'] && $user_data['show_political_views'] ) { ?>
	<tr>
		<td class="bold aright" width="35%;"><?=t('Политические взгляды')?></td>
		<td style="color:#333333">
			<?=political_views_peer::get_name($user_data['political_views'])?>
			<? if ( ( $user_data['political_views'] == 5 ) && $user_data['political_views_custom'] ) { ?>
				(<?=stripslashes(htmlspecialchars($user_data['political_views_custom'])?>)
			<? } ?>
		</td>
	</tr>
	<? } */ ?>
        <? //if (strpos($_SERVER['REMOTE_ADDR'],'88.155.20.43')) echo $user_data['contact_access'];
        if(user_auth_peer::get_rights(session::get_user_id(),$user_data['contact_access']) || $user_data['user_id']==session::get_user_id() || $user['offline']==session::get_user_id()){ ?>
		<? load::model('geo') ?>
	<? if ( $user_data['country_id'] ) { ?>
            <? $country = geo_peer::instance()->get_country($user_data['country_id']) ?>
            <tr><td class="bold aright" width="35%;"><?=t('Место проживания')?></td><td style="color:#333333"><a href="/search?submit=1&country=<?=$user_data['country_id']?>"><?=$country['name_' . translate::get_lang()]?></a>

            <? if ( $user_data['region_id'] ) { ?>
	            <? $region = geo_peer::instance()->get_region($user_data['region_id']) ?>
	            / <a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>"><?=$region['name_' . translate::get_lang()]?></a>
            <? } ?>

            <? if ( $user_data['region'] != "" ) { ?>
	            / <?=$user_data['region']?>
            <? } ?>

            <? if ( $user_data['city_id'] ) { ?>
				<? $city = geo_peer::instance()->get_city($user_data['city_id'], $user_data["country_id"] > 1 ? true : false) ?> / <a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>&city=<?=$user_data['city_id']?>"><?=$city['name_' . translate::get_lang()]?></a>
            <? } ?>

            <? if ( $user_data['city'] != "" ) { ?>
	            / <?=$user_data['city']?>
            <? } ?>

            <? if ( $user_data['location'] ) { ?> / <?=stripslashes(htmlspecialchars($user_data['location']))?> <? } ?>
            <? if (db_key::i()->exists("showadrop_".$user_data['user_id'])) { ?> / <?=t('ул.')?> <?=stripslashes(htmlspecialchars($user_data['street']))?> <?=stripslashes(htmlspecialchars($user_data['house']))?><? } ?>
            </td></tr>
	<? } ?>
        
       
        <? if ($user['id']==5) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?=t('Территория').' '.t('партийной деятельности')?></td>
                <td><a href="/search?submit=1&country=1"><?=$country['name_' . translate::get_lang()]?></a></td>
            </tr>
	<? } else { ?>
						<? if($user_data['party_region_id'] > 0){ ?>
            <tr>
							<td class="bold aright" width="35%;"><?=t('Территория').' '.t('партийной деятельности')?></td><td>
								<? $region = geo_peer::instance()->get_region($user_data['party_region_id']) ?><a href="/search?submit=1&country=1&region=<?=$user_data['party_region_id']?>"><?=$region['name_' . translate::get_lang()]?></a>
								<? if ( $user_data['party_city_id'] ) { ?>
									<? $city = geo_peer::instance()->get_city($user_data['party_city_id']) ?> / <a href="/search?submit=1&country=1&region=<?=$user_data['party_region_id']?>&city=<?=$user_data['party_city_id']?>"><?=$city['name_' . translate::get_lang()]?></a>
								<? } ?>
								<!--<? if ( $user_data['party_location'] ) { ?> / <?=stripslashes(htmlspecialchars($user_data['party_location']))?> <? } ?>-->
							</td>
						</tr>
						<? } ?>
	<? } ?>
	<? if ( $user_data['birthday'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Дата рождения')?></td><td style="color:#333333"><?=user_data_peer::instance()->get_formated_data($user_data['birthday'])?></td></tr>
	<? } ?>
	<? if ( $user_data['segment'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Сфера деятельности')?></td><td style="color:#333333"><?=user_auth_peer::get_segment($user_data['segment'])?></td></tr>
	<? } ?>
	<? if ( $user_data['additional_segment'] ) { ?>

                <tr><td class="bold aright" width="35%;"><?=t('Дополнительная сфера деятельности')?></td><td style="color:#333333"><?=user_auth_peer::get_segment($user_data['additional_segment'])?></td></tr>
	<? } ?>
	<? /*if ( $user_data['interests'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Интересы')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['interests'])?></td></tr>
	<? } ?>
	<? if ( $user_data['books'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Любимые книги')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['books'])?></td></tr>
	<? } ?>
	<? if ( $user_data['films'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Любимые фильмы')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['films'])?></td></tr>
	<? } ?>
	<? if ( $user_data['sites'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Любимые интернет-порталы')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['sites'])?></td></tr>
	<? } ?>
	<? if ( $user_data['music'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Любимая музыка')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['music'])?></td></tr>
	<? } ?>
	<? if ( $user_data['leisure'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Досуг')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['leisure'])?></td></tr>
	<? } */ ?>
	<? if ( $user_data['email'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Email')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['email']))?></td></tr>
	<? } ?>
<? if ($user_work['work_name'] or $user_work['position']) { ?>
                    <tr>
                            <td class="bold aright" width="35%;"><?=t('Текущее')?> <?=t('место работы')?></td>
                            <td style="color:#333333">
        <? if ( $user_work['work_name']) { ?> <?=stripslashes(htmlspecialchars($user_work['work_name'], ENT_QUOTES))?>, <? } if ( $user_work['position']) { ?> <?=stripslashes(htmlspecialchars($user_work['position']))?>
    <? } ?>
                            </td>
                    </tr>
<? } ?>
	<? if ( $user_data['mobile'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Мобильный телефон')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['mobile']))?></td></tr>
	<? } ?>
	<? if ( $user_data['work_phone'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Рабочий телефон')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['work_phone']))?></td></tr>
	<? } ?>
	<? if ( $user_data['home_phone'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Домашний телефон')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['home_phone']))?></td></tr>
	<? } ?>
	<? if ( $user_data['phone'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Другой телефон')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['phone']))?></td></tr>
	<? } ?>
	<? if ( $user_data['site'] ) { ?>
                <tr><td class="bold aright" width="35%;"><?=t('Сайт')?></td><td style="color:#333333"><?=user_helper::get_links($user_data['site'])?></td></tr>
	<? } ?>
	<? if ( $user_data['icq'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('ICQ')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['icq']))?></td></tr>
	<? } ?>
	<? if ( $user_data['skype'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Skype')?></td><td style="color:#333333"><?=stripslashes(htmlspecialchars($user_data['skype']))?></td></tr>
	<? } ?>

<? if ($user_contacts = unserialize($user_data['contacts']))
    {
$check=array_values($user_contacts);
foreach ($check as $item) 
    {
    if ($item!='') { $flag=1; break; }
    }
if ($flag==1)
    {
    ?>
	    <tr>
		    <td class="bold aright" width="35%;">
			    <?=t('В социальных сетях')?>
		    </td>
			<td style="color:#333333">
				<? foreach ( $user_contacts as $type => $contact )
					if ( $contact ) {
                        $contact = user_data_peer::instance()->prepare_contact($contact, $type);
                        ?>
                            <a href="<?=$contact?>"  rel="<?=stripslashes(htmlspecialchars($contact))?>" title="<?=user_data_peer::get_contact_type($type)?>" class="dib ico<?=$type?>"></a>
					<? } ?>
            </td>
		</tr>
	<? } } ?>
        
        <? if ( $user['offline']>0 ) { ?>
                <tr>
                    <td class="bold aright" width="35%;"><?=t('Создал')?></td>
                    <td style="color:#333333"><?=user_helper::full_name($user['offline']);?></td>
                </tr>
        <? } ?>
        <? if ( $user_data['locationlat']>0) { ?>
        <tr>
                <td class="bold aright" width="35%;"><?=t('На карте')?></td>
		<td style="color:#333333">
                    <a rel="nofollow" href="/search?smap=1&distance=10&submit=1&lng=<?=$user_data['locationlng']?>&lat=<?=$user_data['locationlat']?>&user_id=<?=$user_data['user_id']?>" target="_blank"><?=tag_helper::image('/logos/map.png', array("width"=>"24px",'class' => 'vcenter mr5'))?></a>
		<?if($user_data['user_id']==session::get_user_id()) { ?>
                    <a href="/profile/edit?id=<?=session::get_user_id()?>&tab=map"><?=t('Редактировать')?></a> 
                    <?}?>
                </td>
	</tr>
        <?}?>
	<? if ( $user['invited_by'] ) { ?>
		<tr><td class="bold aright" width="35%;"><?=t('Приглашение')?></td><td style="color:#333333"><?=user_helper::full_name($user['invited_by'])?></td></tr>
	<? } ?>
	<? if ( $user['recomended_by'] ) { ?>
                <tr><td class="bold aright" width="35%;"><?=t('Рекомендация')?></td><td style="color:#333333"><?=user_helper::full_name($user['recomended_by'])?></td></tr>
	<? } ?>
	<? if ( $user['from'] && session::has_credential('admin') ) { ?>
                <tr><td class="bold aright" width="35%;">*<?=t('Импортирован')?> <?=t('с')?></td><td style="color:#333333"><?=user_auth_peer::get_from($user['from'])?></td></tr>
	<? } ?>
                

        <? 
        if(session::has_credential('admin'))
        if ( $list_data = lists_users_peer::instance()->get_list(array('user_id'=>$user['id']))) {
            ?>
                <tr><td class="bold aright" width="35%;">*<?=t('В списках')?></td><td style="color:#333333">
            <?
                foreach($list_data as $key=>$value) {
                    $ldata = lists_peer::instance()->get_item($value);
                    echo "<a href='/people?list=".$ldata['id']."'>".$ldata['title']."</a>";
                    if(isset($list_data[$key+1]))
                        echo ", ";
                }
                ?></td></tr>
	<? } }?>
                
</table>
</div>