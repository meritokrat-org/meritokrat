<? foreach ($users as $user_id) { ?>
	<input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?= $user_id ?>"
	       value="<?= $user_id ?>">
	<div id="friend_<?= $user_id ?>" rel="<?= $user_id ?>" class="item friend"
	     onclick="<?= request::get_int('more_select') ? 'Application.thisfriendSelect(this);' : 'Application.thisOneUserSelect(this);' ?>"
	     style="height:50px;">
		<?= user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false) ?>
		<div style="margin-left: 50px;">
			<a class="dotted" target="_blank"
			   href="/profile-<?= $user_id ?>"><?= user_helper::full_name($user_id, false) ?></a>
			<?= (is_array($invited) && in_array($user_id, $invited)) ? '<br/><span style="color:green;">' . t('Уже приглашен') . '</span>' : '' ?>
		</div>
	</div>
<? } ?>
<div class="clear pb10"></div>
<div class="mt10 mb5 ml5 left pager">
	<? #$srch=0;if($q!='')
	$srch = 1;
	$pager = pager_helper::get_full_ajax($pager, $page, 12, 16, $q, $srch); ?>
	<? $pager = str_replace("Srch", "Group", $pager); ?>
	<? $pager = str_replace(")\"", ",'" . request::get_int('group_id') . "')\"", $pager); ?>
	<? $pager = str_replace(")\"", ",'" . request::get_int('more_select') . "')\"", $pager); ?>
	<?= $pager ?>
</div>
<div class="clear pb10"></div>