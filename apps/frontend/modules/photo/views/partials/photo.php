<? $photo = photo_peer::instance()->get_item($id) ?>
<a class="left acenter" href="<?=context::get('image_server').photo_helper::photo_path($id, 'o')?>" rel="prettyPhoto[gallery]" title="<?=htmlspecialchars(stripslashes($photo['title']))?>">
    <?=photo_helper::photo($id, 'ph', array())?>
    <br/><span class="fs12"><?=(mb_strlen($photo['title'])>70)?mb_substr(htmlspecialchars(stripslashes($photo['title'])),0,70).'...':htmlspecialchars(stripslashes($photo['title']))?></span>
</a>
