<?
$arr = array(//array('link','link_name')
                    '1' => array('blogpost','комментария к посту в блоге'),
                    '2' => array('blog','поста в блоге'),
                    '5' => array('poll','комментария к опросу'),
                    '6' => array('idea','комментария к идеологии'),
                    '7' => array('event','комментария к событию'),
                    '8' => array('poll','опроса')
                    );
$action= array('','удаление','редактирование');
?>
<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=$htitle?></h1>

	<? foreach ( $list as $item ) { ?>
        <? $item = admin_complaint_peer::instance()->get_item($item) ?>
		<div class="box_content p10 mb10">
			<p>
                            <?=date('H:i d.m', $item['created_ts'])?><br/>
                            <?=t('Жалоба участника').': '.strip_tags(user_helper::full_name($item['user_id']),'<a>')?>
                            <?='(<a href="/admin/complaint?user_id='.$item['user_id'].'">'.db::get_scalar('SELECT COUNT(*) FROM complaints WHERE user_id = '.$item['user_id']).'</a>)'?>
                            <br/>
                            <?=t('На модератора').': '.strip_tags(user_helper::full_name($item['moderator_id']),'<a>')?>
                            <?='(<a href="/admin/complaint?moderator_id='.$item['moderator_id'].'">'.db::get_scalar('SELECT COUNT(*) FROM complaints WHERE moderator_id = '.$item['moderator_id']).'</a>)'?>
                            <br/>
                            <?=t('Причина').': '.$action[$item['action']].' '.$arr[$item['content_type']][1]?>
                        </p>
		</div>
	<? } ?>

	<div class="box_content p10 mb10 fs11">
		<?=pager_helper::get_short($pager)?>
	</div>

</div>
