<?
if(request::get_int('region'))$url['region'] = '&region='.request::get_int('region');
if(request::get_int('type'))$url['type'] = '&type='.request::get_int('type');
if(request::get('view'))$url['view'] = '&view='.request::get('view');
//if(request::get_int('period'))$url['period'] = '&period=1&start='.request::get('start').'&end='.request::get('end');
?>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('view')">
<div class="left"><?=t('По виду статистики')?></div>
<div class="right mt5 icoupicon <?=(request::get('view') && request::get('view')!='peoples') ? '' : 'hide'?>" style="cursor: pointer;" id="view_on"></div>
<div class="right mt5 icodownt <?=(!request::get('view') || request::get('view')=='peoples') ? '' : 'hide'?>" style="cursor: pointer;" id="view_off"></div>
</div>
<div class="p10 box_content <?=(request::get('view') && request::get('view')!='peoples') ? '' : 'hide'?>" id="view">
        <ul class="mb5 ">
        <? $views = array('peoples'=>t('Люди'),'regions'=>t('Регионы'),'types'=>t('Типы агитматериалов')) ?>
        <? foreach ($views as $name=>$view) { ?>
            <li><a href="/results/agitation?view=<?=$name?>" style="<?=$name==request::get('view') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;"><?=$view?></a></li>
        <? } ?>
        </ul>
</div>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('functions')">
<div class="left"><?=t('По типу агитматериалов')?></div>
<div class="right mt5 icoupicon <?=request::get_int('type')>0 ? '' : 'hide'?>" style="cursor: pointer;" id="functions_on"></div>
<div class="right mt5 icodownt <?=!request::get_int('type') ? '' : 'hide'?>" style="cursor: pointer;" id="functions_off"></div>
</div>
<div class="p10 box_content <?=request::get_int('type')>0 ? '' : 'hide'?>" id="functions">
        <ul class="mb5 ">
        <? foreach (user_helper::get_agimaterials() as $function_id=>$function_title) { ?>
            <li><a href="/results/agitation?type=<?=$function_id?><?=$url['region']?>" style="<?= $function_id==request::get_int('type') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;"><?=$function_title?></a></li>
        <? } ?>
        <li><a href="/results/agitation?type=0<?=$url['region']?>"><?=t('Все')?></a></li>
        </ul>
</div>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('regions')">
<div class="left"><?=t('По региону')?></div>
<div class="right mt5 icoupicon <?=request::get_int('region')>0 ? '' : 'hide'?>" id="regions_on" style="cursor: pointer;"></div>
<div class="right mt5 icodownt <?=!request::get_int('region') ? '' : 'hide'?>" id="regions_off" style="cursor: pointer;"></div>
</div>
<div class="p10 box_content <?=request::get_int('region')>0 ? '' : 'hidden'?>" id="regions">
	<? $all_regions=geo_peer::get_regions(1);
        foreach ($all_regions  as $region_id => $title ) {
            $count_users=db::get_scalar('SELECT count(user_id) FROM user_data WHERE region_id='.$region_id.' AND user_id in (SELECT DISTINCT ON (user_id) user_id FROM user_agitmaterials WHERE (receive > 0 OR given > 0 OR presented > 0))');
            $az_regions[]=array('id'=>$region_id, 'title'=>$title,'count'=>$count_users);
            $rate_regions[$count_users.($region_id%10)]=array('id'=>$region_id, 'title'=>$title, 'count'=>$count_users);
            ksort($rate_regions);
        }
        ?>
    <div class="fs11 left ml10"><a href="javascript:;" id="az" class="areg hide">Назва &#9660;</a><a href="javascript:;" id="za" class="areg">Назва &#9650;</a></div>
    <div class="fs11 right mr15"><a href="javascript:;" id="rate" class="areg hide">Рейтинг &#9650;</a><a href="javascript:;" id="unrate" class="areg">Рейтинг &#9660;</a></div><br>

        <ul class="mb5 dreg" id="ul_az">
		<? foreach ($az_regions  as $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/results/agitation?country=1&region=<?=$region['id']?><?=$url['type']?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
                <li><a href="/results/agitation?region=0<?=$url['type']?>"><?=t('Все')?></a></li>
	</ul>

        <ul class="mb5 dreg hide" id="ul_za">
		<? rsort($az_regions);
                foreach ($az_regions  as $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/results/agitation?country=1&region=<?=$region['id']?><?=$url['type']?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
                <li><a href="/results/agitation?region=0<?=$url['type'].$url['view']?>"><?=t('Все')?></a></li>
	</ul>

        <ul class="mb5 dreg hide" id="ul_unrate">
		<? foreach ($rate_regions  as $region_count => $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/results/agitation?country=1&region=<?=$region['id']?><?=$url['type']?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
                <li><a href="/results/agitation?region=0<?=$url['type']?>"><?=t('Все')?></a></li>
	</ul>

        <ul class="mb5 dreg hide" id="ul_rate">
		<?
                foreach (array_reverse($rate_regions) as $region_count => $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/results/agitation?country=1&region=<?=$region['id']?><?=$url['type']?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
                <li><a href="/results/agitation?region=0<?=$url['type']?>"><?=t('Все')?></a></li>
	</ul>
</div>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('period')">
<div class="left"><?=t('Поиск по периоду')?></div>
<div class="right mt5 icoupicon hide" id="period_on" style="cursor: pointer;"></div>
<div class="right mt5 icodownt" id="period_off" style="cursor: pointer;"></div>
</div>
<div class="p10 box_content acenter <?=(request::get_int('start')>0 OR request::get_int('end')>0) ? '' : 'hidden'?>" id="period">
<form action="/results/agitation?period=1<?=$url['type'].$url['region'].$url['view']?>" method="GET">
        <? if(count($url)>0){ ?>
        <? foreach($url as $k=>$v){ ?>
            <input type="hidden" name="<?=$k?>" value="<?=request::get($k)?>"/>
        <? }} ?>
        <input type="hidden" name="period" value="1"/>
        <span class="cgray fs11"><?=t('С')?>:</span>
        <input name="start" id="start" value="<?=request::get_string('start')?>" class="text mb10" type="text" style="width:70px" />
        <span class="cgray fs11"><?=t('По')?>:</span>
        <input name="end" id="end" value="<?=request::get_string('end')?>" class="text mb10" type="text" style="width:70px" />
        <input name="submit" type="submit" class="button" value="<?=t('Поиск')?>"/>
</form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.areg').click(function(){
            $('.areg').removeClass('bold');
            var id = this.id;
            $(this).hide();
            if (id=='az') {
                $('#za').show();
                $('#za').addClass('bold');
            } else if (id=='za') {
                $('#az').show();
                $('#az').addClass('bold');
            } else if (id=='unrate') {
                $('#rate').show();
                $('#rate').addClass('bold');
            } else if (id=='rate') {
                $('#unrate').show();
                $('#unrate').addClass('bold');
            }

            $('.dreg').hide();
            $('#ul_'+id).show();
        });
    });
</script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript">
    $(document).ready(function($){
        var settings = {
        changeMonth: true,
        changeYear: true,
         autoSize: true,
        showOptions: {direction: 'left' },
        dateFormat: 'dd-mm-yy',
        yearRange: '2010:2025',
        firstDay: true
        };
        $('#start').datepicker(settings);
        $('#end').datepicker(settings);
    });
</script>
