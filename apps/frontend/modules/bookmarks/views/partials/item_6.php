<div>
<? $num=0; ?>
<? foreach($list_6 as $item){ ?>
<? $num++; ?>
<? $data = user_data_peer::instance()->get_item($item['oid']) ?>
<? if($big){ ?>

<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
    <div style="width:285px;">
        <?=user_helper::full_name($data['user_id'],true,array('class'=>'fs16'))?><br/>
    </div> 
</div>
<? /** ?>
<div id="bookmark_item_<?=$item['id']?>" class="mb15 mr10 box_content p5 fs12" style="height:91px;">
        <?=user_helper::photo($data['user_id'], 't', array('class' => 'left mr10 border1'))?> 
        <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>

    <div style="width: 90%;">
        <?=user_helper::full_name($data['user_id'],true,array('class'=>'bold'))?><br/>

        <? $udata = user_auth_peer::instance()->get_item($data['user_id']) ?>
        <? if ( $udata['type']>0 and  $udata['type']<15 ) { ?> <?=user_auth_peer::get_type($udata['type']).user_auth_peer::get_hidden_type($udata['hidden_type'],$udata['type'])?><br/>
        
	<? if ( $data['country_id'] ) { ?>
		<? $country = geo_peer::instance()->get_country($data['country_id']) ?>
    <a href="/search?submit=1&country=<?=$data['country_id']?>"><?=$country['name_' . translate::get_lang()]?></a>
	<? if ( $data['region_id'] ) { ?>
		<? $region = geo_peer::instance()->get_region($data['region_id']) ?> / <a href="/search?submit=1&country=<?=$data['country_id']?>&region=<?=$data['region_id']?>"><?=$region['name_' . translate::get_lang()]?></a>
	<? } ?>
	<? if ( $data['city_id'] ) { ?>
		<? $city = geo_peer::instance()->get_city($data['city_id']) ?> / <a href="/search?submit=1&country=<?=$data['country_id']?>&region=<?=$data['region_id']?>&city=<?=$data['city_id']?>"><?=$city['name_' . translate::get_lang()]?></a>
	<? } ?>
        <br/>
        <? } ?>

        <? if ( $data['segment'] ) { ?>
<a href="/search?submit=1&segment=<?=$data['segment'] ?>"><?=user_auth_peer::get_segment($data['segment'])?></a>, <? if ( $data['additional_segment'] ) { ?> <a href="/search?submit=1&segment=<?=$data['additional_segment'] ?>"><?=user_auth_peer::get_segment($data['additional_segment'])?></a> <? } ?>
	<br/>
        <? } ?>

        <? $user_work = user_work_peer::instance()->get_item($data['user_id']) ?>
        <? if ( $user_work['work_name']) { ?> <?=stripslashes(htmlspecialchars($user_work['work_name']))?>, <? } if ( $user_work['position']) { ?> <?=stripslashes(htmlspecialchars($user_work['position']))?>   <? } ?>
        <? } ?>
	</div> 
</div>
<? */ ?>

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=user_helper::photo($data['user_id'], 'sm', array('class' => 'left mr10 border1'))?> 
        <div class="right" style="width:185px">
            <?=user_helper::full_name($data['user_id'],true)?>
            <br />
            <span style="color:grey">
            <? $udata = user_auth_peer::instance()->get_item($data['user_id']) ?>
            <?=user_auth_peer::get_status($udata['status'])?>
            </span>
        </div>    
    </div>    
<? }} ?>
</div>