<div class="left acenter fs12" style="width: 170px; height: 160px; vertical-align: middle; padding:9px 5px 5px 5px; background-color:#F7F7F7">
	<? $screen_id = photo_albums_peer::instance()->get_album_screen_photo($album_id) ?>
	<? $album = photo_albums_peer::instance()->get_item($album_id) ?>
	<a href="/photo?album_id=<?=$album_id?>"><?=photo_helper::photo($screen_id, 'ph')?></a><br />
	<a href="/photo?album_id=<?=$album_id?>"><?=htmlspecialchars(stripslashes($album['title']))?></a>
</div>