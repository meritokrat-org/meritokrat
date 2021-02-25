<? 
                load::model('ideas/comments');
                $idea = ideas_peer::instance()->get_item($id) ?>
                <? if (context::get_controller()->get_module()!='home') { ?>

<!--div class="left"><?=user_helper::photo($idea['user_id'], 's', array('class' => 'border1'))?></div-->
<div class="left ml10 mt10" style="width: 97%;">
<? if(request::get('bookmark')){ ?>
    <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),4,$idea['id']); ?>
    <a class="bookmark mb10 ml5 right" style="<?=($bkm)?'display:none;':'display:block;'?>" href="#add_bookmark" onclick="Application.bookmarkThisItem(this,'4','<?=$idea['id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
    <a class="unbkmrk mb10 ml5 right" style="<?=($bkm)?'display:block;':'display:none;'?>" href="#del_bookmark" onclick="Application.unbookmarkThisItem(this,'4','<?=$idea['id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>
<? } ?>
	<a class="fs18" href="/idea<?=$id?>"><?=session::get('language')=='ru' ? stripslashes(htmlspecialchars($idea['title_ru'])) : stripslashes(htmlspecialchars($idea['title']))?></a>
	<div class="fs11 quiet mb5 mt5">
            <div class="left mb5" style="margin-top:-5px;"><?=user_helper::full_name($idea['user_id'])?> &nbsp;<br/></div>
                <div class="mb5" style="color:black;font-size: 12px;"><br/><?=session::get('language')=='ru' ? stripslashes(htmlspecialchars($idea['anounces_ru'])) : stripslashes(htmlspecialchars($idea['anounces']))?></div>
                    <div class="p5" style="background: #F7F7F7;">
<?=tag_helper::image('common/up.png', array('class' => 'vcenter'))?> <?=t('Идею поддерживают')?>: <b><?=$idea['rate']?></b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>" class="fs11 cgray"><?=t('Читать далее')?> &rarr;</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>#comments" class="fs11 cgray"><?=t('Комментариев')?>: <?=ideas_comments_peer::instance()->get_count_by_idea($idea['id'])?></a>
		<?//=date_helper::human($idea['created_ts'], ', ')?>

		</div>
	</div>

</div>

<div class="clear"></div>
<? } else { ?>
        <!--div class="item fl">
     <div class="fl"-->
    <!--div class="left"><?=user_helper::photo($idea['user_id'], 's', array('class' => 'border1'))?></div-->
      <div class="left ml10 mt10" style="width: 97%;">
	<a class="fs18" href="/idea<?=$id?>"><?=session::get('language')=='ru' ? stripslashes(htmlspecialchars($idea['title_ru'])) : stripslashes(htmlspecialchars($idea['title']))?></a>
	<div class="fs11 quiet mb5 mt5">
            <div class="left mb5" style="margin-top:-5px;"><?=user_helper::full_name($idea['user_id'])?> &nbsp;<br/></div>
            <div class="mb5" style="color:black;font-size: 12px;"><br/><?=session::get('language')=='ru' ? stripslashes(htmlspecialchars($idea['anounces_ru'])) : stripslashes(htmlspecialchars($idea['anounces']))?></div>
                    <div class="mb5">
<?=tag_helper::image('common/up.png', array('class' => 'vcenter','width'=>'16', 'height'=>'16','style'=>'margin-bottom:2px;'))?> <?=t('Идею поддерживают')?>: <b><?=$idea['rate']?></b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>" class="fs11 cgray"><?=t('Читать далее')?> &rarr;</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>#comments" class="fs11 cgray"><?=t('Комментариев')?>: <?=ideas_comments_peer::instance()->get_count_by_idea($idea['id'])?></a>
		<?//=date_helper::human($idea['created_ts'], ', ')?>
		</div>
                <div style="background-color: #CCCCCC;height:1px;"></div>
	</div>
	</div>	<!--/div>

</div-->
<? } ?>