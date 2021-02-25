<h1 class="column_head"><?=t('По категории')?></h1>
<div class="p10 box_content">
	<ul class="mb5">
        <li><a <?=(request::get_int('section')==1)?'class="bold"':''?> href="/events/?section=1<?=$mode?>"><?=t('Информационные')?></a></li>
        <li><a <?=(request::get_int('section')==2)?'class="bold"':''?> href="/events/?section=2<?=$mode?>"><?=t('Организационные')?></a></li>
        <li><a <?=(request::get_int('section')==3)?'class="bold"':''?> href="/events/?section=3<?=$mode?>"><?=t('Учебные')?></a></li>
        <li><a <?=(request::get_int('section')==8)?'class="bold"':''?> href="/events/?section=8<?=$mode?>"><?=t('Другие')?></a></li>
	</ul>
</div>
<h1 class="column_head"><?=t('По организатору')?></h1>
<div class="p10 box_content">
	<ul class="mb5">
        <li><a <?=(request::get_int('type')==3)?'class="bold"':''?> href="/events/?type=3<?=$mode?>"><?=t('Секретариат')?></a></li>
        <li><a <?=(request::get_int('type')==1)?'class="bold"':''?> href="/events/?type=1<?=$mode?>"><?=t('Сообщество')?></a></li>
        <li><a <?=(request::get_int('type')==0)?'class="bold"':''?> href="/events/?type=0<?=$mode?>"><?=t('Координатор')?></a></li>
     
        <!--  <li><a <?#if(strlen(request::get('type'))>0 && request::get_int('type')==0)
           # echo 'class="bold"';?> href="/events/?type=0<?#$mode?>"><?#t('Участники')?></a></li>-->
	</ul>
</div>
<h1 class="column_head"><?=t('По уровню')?></h1>
<div class="p10 box_content">
	<ul class="mb5">
        <li><a <?=(request::get_int('level')==1)?'class="bold"':''?> href="/events/?level=1<?$mode?>"><?=t('Национальная')?></a></li>
        <li><a <?=(request::get_int('level')==2)?'class="bold"':''?> href="/events/?level=2<?$mode?>"><?=t('Местная')?></a></li>
	</ul>
</div>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('regions')">
<div class="left"><?=t('По региону')?></div>
<div class="right mt5 icoupicon hide" id="regions_on" style="cursor: pointer;"></div>
<div class="right mt5 icodownt" id="regions_off" style="cursor: pointer;"></div>
</div>
<div class="p10 box_content" <?=(!request::get_int('region'))?'style="display:none;"':''?> id="regions">
	<ul class="mb5">
		<? foreach ( geo_peer::instance()->get_regions(1) as $region_id => $title ) { ?>
		<li><a href="/events/?region=<?=$region_id?>" <?=(request::get_int('region')==$region_id)?'class="bold"':''?> style="margin: 1px;"><?=$title?></a><div style="color:#660000" class="right fs11 bold"><?=db::get_scalar('SELECT count(user_id) FROM events WHERE region_id=:region_id',array('region_id'=>$region_id))?></div></li>
	<? } ?>
	</ul>
</div>