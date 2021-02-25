<? if(count($teams) > 0){ ?>
	<ul style="list-style: none;">
		<? foreach($teams as $team){ ?>
			<? $team = team_peer::instance()->get_item($team["group_id"]);?>
			<li><a href="/team<?=$team["id"]?>/1"></a><?=$team["title"]?></a></li>
		<? } ?>
	</ul>
<? }else{?>
	<div style="padding: 15px 15px 0 15px">
		<p style="color:#888;"><?=t("Тут пока ничего нет")?></p>
	</div>
<?}?>
