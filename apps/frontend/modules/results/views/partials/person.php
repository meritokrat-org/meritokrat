<? 
$udata = user_auth_peer::instance()->get_item($id);
$user_data = user_data_peer::instance()->get_item($id); 
$list = blogs_comments_peer::instance()->get_by_users(array($id), false);
arsort($list);
$count_coms = count($list);
$list = blogs_posts_peer::instance()->get_by_user($id);
$count_blog_posts = count($list);

$allowed_groups=groups_peer::instance()->get_new();
$list = blogs_posts_peer::instance()->get_by_user($id, blogs_posts_peer::TYPE_GROUP_POST, $allowed_groups);
$count_groups_post = count($list);
?>
<div style="font-size: 9pt;"><div style=" float: left; width: 400px;"><b style="float: left;margin-right: 5px;"><?=$i?>.</b> 
<?if($mes[$id]['link']){?><a href="<?=$mes[$id]['link']?>" target="_blank">
    <b><?=user_helper::full_name($id,false)?></b></a><?}else{?>
        <?=user_helper::full_name($id,true,array('class'=>'bold'))?>
        <?}?>
	<? if ( $user_data['region_id'] && request::get_string('act') != 'covers' ) { ?>
		<? $region = geo_peer::instance()->get_region($user_data['region_id']) ?><a href="/search?submit=1&country=<?=$user_data['country_id']?>&region=<?=$user_data['region_id']?>"><?=$region['name_' . translate::get_lang()]?></a>
	<? } ?>
	<span class="cgray"><?=user_auth_peer::get_status($udata["status"])?></span>
	<? if(request::get_string('act') == 'covers'){ ?>
		:: <a href="<?=$covers[$id]?>" target="_blank"><?=t('Перейти')?> &rarr;</a>
	<? } ?>
	</div>
	<div class="right">
    <?if($mes['name']!=''){?>
                <?if($mes[$id]['link']):?><a href="<?=$mes[$id]['link']?>" target="_blank"><?endif;?>
                    <b><?=$mes[$id]['val']?></b>
                <?if($mes[$id]['link']):?></a><?endif;?>
    <?}?>
	</div>
	<div class="clear"></div>
</div>
<div class="clear gray_line"></div>