<? $user_data = user_data_peer::instance()->get_item($id); ?>
<? $user_auth = user_auth_peer::instance()->get_item($id); ?>
<? $last_visit = user_sessions_peer::instance()->last_visit($id); ?>
<? if($locationlat){ ?>
	<? $distance = user_data_peer::instance()->get_distance($locationlat, $locationlng, $id); ?>
<? } ?>
<div class="box_content p10 mb10">
	<div class="left" style="width: 80px;">
		<?=user_helper::photo($id, 't', array('class' => 'border1 left'))?>
	</div>
	
	<div class="left" style="width: 150px;">
		<div>
			<? if($last_visit){ ?>
				<!--<img class="left" style="padding-top: 2px; padding-right: 4px" alt="online" src="/static/images/common/user_online.png" />-->
			<? } ?>
			<?=user_helper::full_name($id, true, array('class'=>'bold'))?>
		</div>
		
		<div class="fs11"><?=$user_auth['status'] ? user_auth_peer::get_status($user_auth['status']) : ''?></div>
		
		<? load::model('geo') ?>
		<? if($user_data['country_id']){ ?>
			<? $country = geo_peer::instance()->get_country($user_data['country_id']) ?>
			<div class="fs11">
				<a href="/search?submit=1&country=<?=$user_data['country_id']?>">
					<?=$country['name_' . translate::get_lang()]?>
				</a>
				<? if($user_data['region_id']){ ?>
					&nbsp;/&nbsp;
					<? $region = geo_peer::instance()->get_region($user_data['region_id']) ?>
					<a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>">
						<?=$region['name_' . translate::get_lang()]?>
					</a>
					<? if($user_data['city_id']){ ?>
						&nbsp;/&nbsp;
						<? $city = geo_peer::instance()->get_city($user_data['city_id']) ?>
						<a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>&city=<?=$user_data['city_id']?>">
							<?=$city['name_' . translate::get_lang()]?>
						</a>
					<? } ?>
				<? } ?>
			</div>
		<? } ?>
		
		<div class="fs11">
			<? if(request::get_int('function')>0){ ?>
				<? load::model('user/user_desktop'); ?>
				<? $user_desktop = user_desktop_peer::instance()->get_item($id); ?>
				<? if($user_desktop['user_id'] == 5){ ?>
					<? $echo_functions[$id][]=t('Глава (Лидер) Партии'); ?>
				<? } ?>
				<? foreach(user_auth_peer::get_functions() as $function_id => $function_title){ ?>
					<? if(in_array($function_id, explode(',', str_replace(array('{', '}'), array('', ''), $user_desktop['functions'])))){ ?> 
						<? $echo_functions[$id][] = $function_title; ?>
					<? } ?>
				<? } ?>
				<?=implode(', ', $echo_functions[$id])?>
			<? } else { ?>
				<? load::model('user/user_work') ?>
				<? $user_work = user_work_peer::instance()->get_item($id) ?>
				<? if($user_work['work_name']){ ?> 
					<?=stripslashes(htmlspecialchars($user_work['work_name']))?>, 
				<? } ?>
				<? if($user_work['position']){ ?>
					<?=stripslashes(htmlspecialchars($user_work['position']))?>
				<? } ?>
			<? } ?>
		</div>
	</div>
	
	
	
	<div class="clear"></div>
</div>
