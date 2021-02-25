<? #$people_recomended=user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id']);
   # $people_attracted=user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id'],true);
    load::model('blogs/comments');
    load::model('blogs/posts');
    load::model('groups/groups');
    load::model('geo')
    ?>
<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>
<style type="text/css">
    table#ppstat 
    {
        background-color:#cccccc;
        border-spacing:1px;
        font-size: 12px;
    }
    #ppstat td {
    padding: 4px 2px 4px 2px;    
    background-color:#FFFFFF;
    padding: 2px;
    }
</style>
<div class="left ml10 mt10" style="width: 62%;">
	<? if ( !$list ) { ?>
		<div class="acenter fs11 quiet m10 p10"><?=t('Тут еще никого нет')?>...</div>
	<? } else { ?>
<h1 class="column_head mb5">
                        <?if($mes['name']!=''){?>
<?=$mes['name']?>
                        <?}?>
</h1>
		<? $page=request::get_int('page'); 
                if(!$page)$page=1; $page=$page-1; $i=($page*50)+1; foreach ( $list as $id ) { include 'partials/person.php'; $i++; } ?>
                
		<div class="bottom_line_d mb10"></div>
		<div class="right pager"><?=pager_helper::get_full($pager)?></div>
	<? } ?>

</div>