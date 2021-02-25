<? if ( $user_contacts = unserialize($user_data['contacts']) ) { ?>

	<h2 class="column_head"><?=t('Контакты')?></h2>
	<div class="content_pane mb10" id="pane_bio">
		<? foreach ( $user_contacts as $type => $contact ) if ( $contact ) { ?>
			<a rel="nofollow" href="<?=stripslashes(htmlspecialchars($contact))?>" target="_blank"><?=tag_helper::image('/logos/' . $type . '.png', array('class' => 'vcenter mr5', 'title' => user_data_peer::get_contact_type($type)))?></a>
		<? } ?>
	</div>
<? } ?>