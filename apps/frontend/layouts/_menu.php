<?php $menu = array(
	'/blogs' => array( t('Блоги'), t('Блоги') ),
); ?>

<div class="mt5">
<?php foreach ($menu as $url => $menu_data ) {
	$title = $menu_data[0];
	$alt = $menu_data[1];
	echo "<a title=\"{$alt}\" href=\"http://" . context::get('server') . "{$url}\" class=\"" . trim($url, '/') . ( $selected_menu == $url ? ' selected' : '' ) . "\">" . tag_helper::image('menu/' . trim($url, '/') . '.png', array('class' => 'mr5 vcenter', 'alt' => $title)) . "{$title}</a>";
} ?>
</div>