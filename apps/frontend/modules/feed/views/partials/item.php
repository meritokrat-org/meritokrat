<? $item = feed_peer::instance()->get_item($id) ?>
<? 
if (in_array($item['action'],array(1,7))){ 
$item['text'] = stripslashes(str_replace('[[action]]', feed_peer::get_action($item['action']), $item['text'])) ?>
<div id="feed_item_<?=$id?>" class="mb10 mr10 box_content p10 fs12">
	<div class="left" style="width: 90%;">
		<?= str_replace("https://meritokrat.org", "", $item['text'])?>
	</div>
	<script type="text/javascript" language="javascript">
        	$("#feed_item_<?=$id?> .rating_info").attr("class", "hide");
	</script>
	<a class="delete right" href="javascript:;" onclick="feedController.deleteItem(<?=$id?>);"></a>
	<div class="clear"></div>
</div>
<? } ?>
