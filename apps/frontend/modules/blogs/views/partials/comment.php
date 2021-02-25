<style>
    .quot_box {
        border: 1px solid #cccccc;
        background-color: #F9F9F9;
        text-align: justify;
        padding: 5px;
}
</style>


<? 
//require_once 'xml2arr.php';
//$array = new xml('<QUOTE><BOLD>aaa www</BOLD> <DATE>Сьогоднi 12:43</DATE>
//<MSG><QUOTE><BOLD>aaa www</BOLD> <DATE>Вчора 16:26</DATE>
//<MSG>my comment</MSG></QUOTE>
//Answer 1</MSG></QUOTE>
//Answer 2');
//echo "<pre>";
//print_r($array->dom);
//exit;
$num[$id] = 0;
$comment = blogs_comments_peer::instance()->get_item($id);
$childs = explode(',', $comment['childs']);
?>

<div class="mb10 comment_bg" id="comment<?=$id?>" <?if(db_key::i()->exists('negative_blog_comment:'.$id)) {?>style="background: #ffeeee; border: 1px solid #EEAAAA;"<?}?>>
	<? //$do_hide_comment = $comment['rate'] <= -5; ?>

	<div class="comment_hdata<?=$comment['id']?> left <?=$do_hide_comment ? 'hidden' : ''?>">
		<?=user_helper::photo($comment['user_id'], 's', array('class' => 'border1'))?>
	</div>

	<div class="left ml10" style="width: 565px;">
		<div class="fs11 pb5">
			<div class="left quiet">
				<?=user_helper::full_name($comment['user_id'])?> 
				<span class="quiet ml10"><?=user_helper::com_date(date($comment['created_ts']))?></span> &nbsp;
                                <?=($comment['edit'])?t('Отредактировано').': '.strip_tags(user_helper::full_name($comment['edit']),'<a>').(($comment['edit_ts'])?' '.user_helper::com_date(date($comment['edit_ts'])):''):''?>
				<? if ( $do_hide_comment ) { ?>
					<a href="javascript:;" onclick="$('.comment_hdata<?=$comment['id']?>').show();" class="dotted ml10"><?=t('Показать')?></a>
				<? } ?>
			</div>
                    <? if($childs[0]){ ?>
			<div class="right">
                                <a href="javascript:;" class="comhide" rel="<?=$comment['id']?>"><?=t('Скрыть ветку')?></a>
                                <a href="javascript:;" class="comshow hide" rel="<?=$comment['id']?>"><?=t('Показать ветку')?></a>
                        </div>
                    <? } ?>
			<div class="clear"></div>
		</div>

		<div class="combody comment_hdata<?=$comment['id']?> fs12 <?=$do_hide_comment ? 'hidden' : ''?>">
                          <?

                            $text =  user_helper::get_links($comment['text']);
                            if(preg_match_all('/\&lt\;QUOTE\&gt\;(.*)\&lt;\/QUOTE\&gt\;/si',trim($text),$mch)) {
                                $text = preg_replace('/\&lt\;BOLD\&gt\;/', '<b>', $text);
                                $text = preg_replace('/\&lt\;\/BOLD\&gt\;/', '</b>', $text);
                                $text = preg_replace('/\&lt\;DATE&gt\;/', '<em>', $text);
                                $text = preg_replace('/\&lt\;\/DATE\&gt\;/', '</em>', $text);
                                $text = preg_replace('/\&lt\;MSG&gt\;/', '<span>', $text);
                                $text = preg_replace('/\&lt\;\/MSG&gt\;/', '</span>', $text);
                                $text = preg_replace('/\&lt\;QUOTE\&gt\;/', '<div class="quot_box fs11">', $text);
                                $text = preg_replace('/\&lt\;\/QUOTE\&gt\;/', '</div>', $text);
                            }
                            echo $text;
                        ?></div>
		
                <div class="comment_hdata<?=$comment['id']?> fs11 mb5 mt5 <?=$do_hide_comment ? 'hidden' : ''?>">
			<? if ( session::is_authenticated() ) { ?>
				<a href="javascript:;" rel="<?=$comment['id']?>" class="dotted comment_reply"><?=t('Ответить')?></a>
                                <a href="#comment_form" onClick="func();" rel="<?=$comment['id']?>" class="dotted comment_quote_reply ml10"><?=t('Цитувати')?></a>
			<? } ?>
			<? if ( session::has_credential('moderator') ||
				( ( $comment['user_id'] == session::get_user_id() ) && !$comment['childs'] ) ||
				( session::has_credential('selfmoderator') && $post_data['user_id'] == session::get_user_id() ) ) { ?>
                                <a href="javascript:;" rel="<?=$comment['id']?>" class="dotted ml10 comment_update" onClick="Application.<?=($comment['user_id']==session::get_user_id()) ? 'initComUpdUser' : 'initComUpd'?>('<?=$comment['id']?>')"><?=t('Редактировать')?></a>
                                <?if(!db_key::i()->exists('negative_blog_comment:'.$id) || session::has_credential('admin')) {?><a href="javascript:;" onclick="Application.delCom('<?=$comment['id']?>','blogs/delete_comment',<?=($comment['user_id']==session::get_user_id() || session::has_credential('admin'))?1:0?>)" class="dotted ml10"><?=t('Удалить')?></a><?}?>
			<? } ?>
		</div>

	</div>
	<div class="clear"></div>
</div>
<div id="child_comments_<?=$comment['id']?>" class="mb15">
        <? foreach ( $childs as $child_id ) { if ( $child_id = (int)$child_id ) { ?>
                        <? include dirname(__FILE__) . '/child_comment.php'; ?>
        <? } } ?>
</div>
<? unset($childs); ?>
<div class="clear"></div>
<script>
    function func() {document.getElementById('comment_form').focus(); }
</script>