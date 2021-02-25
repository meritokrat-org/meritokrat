<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('statuces')">
<div class="left"><?=t('По статусу')?></div>
<div class="right hide mt5 icoupicon" style="cursor: pointer;" id="statuces_on"></div>
<div class="right mt5 icodownt" style="cursor: pointer;" id="statuces_off"></div>
</div>
<?
#urladd="&act=".request::get_string('act');
#if(request::get_int('function')>0)$urladd.="&function=".request::get_int('function');
?>
<div class="p10 box_content <?=(request::get_int('status')>0 || request::get_int('meritokrat') || request::get_int('expert') || request::get_int('famous')
        || request::get_string('identification') || request::get_int('activate') || request::get_string('offline') || request::get_int('del'))? '' : 'hide'?>" id="statuces">
	<ul class="mb5">
		<? foreach ( user_auth_peer::get_statucess() as $status => $title ) { ?>
		<li><a href="/people?status=<?=$status?><?=$urladd?>" style="<?=$status==request::get_int('status') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;"><?=$title?></a></li>
	<? } ?>
            <li><a href="/people?expert=1<?=$urladd?>" style="<?=request::get_int('expert')==1 ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;"><?=t('Эксперты')?></a></li>
            <? if (session::has_credential('admin')) { ?>
                
                <li><a href="/people?meritokrat=1<?=$urladd?>" style="<?=request::get_int('meritokrat')==1 ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;">*<?=t('Лише мерітократи')?></a></li>
                <li><a href="/people?famous=1<?=$urladd?>" style="<?=request::get_int('famous')==1 ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;">*<?=t('Известные люди')?></a></li>
                <li><a href="/people?identification=check<?=$urladd?>" style="<?= $_GET['identification']=='check' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;">*<?=t('Не идентифицированные')?></a></li>
                <li><a href="/people?offline=all<?=$urladd?>" style="<?= $_GET['offline'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;">*<?=t('Офф-лайн сторонники')?></a></li>
                <li><a href="/people?del=1<?=$urladd?>" style="<?= $_GET['del'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;">*<?=t('Удаленные')?></a></li>
            <? } ?>
	</ul>
</div>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('functions')">
<div class="left"><?=t('По функции')?></div>
<div class="right mt5 <?=request::get_int('function')>0 ? '' : 'hide'?> icoupicon" style="cursor: pointer;" id="functions_on"></div>
<div class="right mt5 <?=!request::get_int('function') ? '' : 'hide'?> icodownt" style="cursor: pointer;" id="functions_off"></div>
</div>
<div class="p10 box_content <?=request::get_int('function')>0 ? '' : 'hide'?>" id="functions">
        <ul class="mb5 ">
         <? foreach (user_auth_peer::get_functions() as $function_id=>$function_title) { ?>
                <li><a href="/people?val=<?=$function_id?>&function=<?=$function_id?><?=$urladd?>" style="<?= $function_id==request::get_int('function') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;"><?=$function_title?></a></li>
        <? } ?>
        </ul>
</div>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('regions')">
<div class="left"><?=t('По региону')?></div>
<div class="right mt5 hide icoupicon" id="regions_on" style="cursor: pointer;"></div>
<div class="right mt5 icodownt" id="regions_off" style="cursor: pointer;"></div>
</div>
<div class="p10 box_content <?=request::get_int('region')>0 ? '' : 'hidden'?>" id="regions">
	<? $all_regions=geo_peer::get_regions(1);
        foreach ($all_regions  as $region_id => $title ) {
            $count_users=db::get_scalar('SELECT count(user_id) FROM user_data WHERE region_id=:region_id AND user_id in (SELECT id FROM user_auth WHERE active=:active)',array('active'=>1,'region_id'=>$region_id));
            $az_regions[]=array('id'=>$region_id, 'title'=>$title,'count'=>$count_users);
            $rate_regions[$count_users.($region_id%10)]=array('id'=>$region_id, 'title'=>$title, 'count'=>$count_users);
            ksort($rate_regions);
            }
            ?>
    <div class="fs11 left ml10"><a href="javascript:;" id="az" class="areg hide">Назва &#9660;</a><a href="javascript:;" id="za" class="areg">Назва &#9650;</a></div>
    <div class="fs11 right mr15"><a href="javascript:;" id="rate" class="areg hide">Рейтинг &#9650;</a><a href="javascript:;" id="unrate" class="areg">Рейтинг &#9660;</a></div><br>
    
        <ul class="mb5 dreg" id="ul_az">
		<? foreach ($az_regions  as $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/search?submit=1&country=1&region=<?=$region['id']?><?=$urladd?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
	</ul>

        <ul class="mb5 dreg hide" id="ul_za">
		<? rsort($az_regions);
                foreach ($az_regions  as $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/search?submit=1&country=1&region=<?=$region['id']?><?=$urladd?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
	</ul>    
    
        <ul class="mb5 dreg hide" id="ul_unrate">
		<? foreach ($rate_regions  as $region_count => $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/search?submit=1&country=1&region=<?=$region['id']?><?=$urladd?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
	</ul>    

        <ul class="mb5 dreg hide" id="ul_rate">
		<? 
                foreach (array_reverse($rate_regions) as $region_count => $region ) { ?>
		<li><a style="<?=$region['id']==request::get_int('region') ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>margin: 1px;" href="/search?submit=1&country=1&region=<?=$region['id']?><?=$urladd?>" style="margin: 1px;"><?=$region['title']?></a><div class="cbrown right fs11 bold"><?=$region['count']?></div></li>
                <? } ?>
	</ul>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.areg').click(function(){
            $('.areg').removeClass('bold');
            var id = this.id;
            $(this).hide();
            if (id=='az') {
                $('#za').@show(); 
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
