<table style="text-align: center; width: 626px; height: 728px;" border="0">
	<tbody>
	<? foreach($list as $item){ ?>
		<div style="width: 33%; padding: 0; margin: 10px 0 0 0; float: left">
			<div style="padding: 5px">
				<input style="width: 100%; font-weight: bold;" onclick="javascipt: window.location='/shop/<?=$item["id"]?>'" type="button" value="<?=$item["title_".translate::get_lang()]?>">
			</div>
			<div style="width: 215px; height: 143px; margin: 5px auto; background-image: url('<?=$item['photo'] != "" ? context::get('image_server')."f/shop/category/{$item["id"]}{$item['photo']}.jpg" : "/static/images/no_image.jpg"?>'); background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
			<div style="padding-left: 18px;">
				<p><span style="font-size: 16px;"><a href="/shop/<?=$item["id"]?>">Детальніше </a><span style="font-size: 18px;"><a href="/shop/<?=$item["id"]?>"><strong>→</strong></a>&nbsp;</span></span></p>
				<p><span style="font-size: 16px;"><span style="font-size: 18px;"><br></span></span></p>
			</div>
		</div>
	<? } ?>

	</tbody>
</table>