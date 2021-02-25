<h2><a href="/debug">Debug</a> &rarr; почта</h2>
<div class="line_light"></div>

<br />

<? if ( $list ) { ?>
	<a href="javascript:;" onclick="$.post('/debug/mail/remove', {});$('#mails').html('');">Удалить все письма</a>

	<br /><br />
<? } else { ?>
	...
<? } ?>

<ul id="mails">
	<? $i = 0 ?>
	<? foreach ( $list as $ts => $details ) { ?>
		<? $i++ ?>
		<li id="mail_<?=$i?>">
			<a href="javascript:;" onclick="$('#data_<?=$i?>').toggle();"><?=$details['subject']?></a> &nbsp;
			<a href="javascript:;" onclick="$.post('/debug/mail/remove?ts=<?=$ts?>', {}); $('#mail_<?=$i?>').hide();">[X]</a>
			
			<div id="data_<?=$i?>" class="hidden" style="margin: 10px; padding: 5px; font-size: 11px; border: 1px solid silver; background: #F7F7F7"><?=nl2br($details['data'])?></div>
		</li>
	<? } ?>
</ul>

<br /><br />