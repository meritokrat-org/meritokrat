<div class="left" style="width: 100%;">

            <h1 class="column_head"><a href="/photocompetition">Фотоконкурс</a> &rarr; <a href="/photocompetition/<?=$post['id']?>"><?=mb_substr(stripslashes(htmlspecialchars($photo['title'])),0,70)?></a> &rarr; <?=t('Голосуючі')?></h1>

	<div class="fs12 mt10 ml10">
            <? $voters=explode(',',str_replace(array('{','}'), array('',''), $photo['voters']));
               foreach ( $voters as $user_id ) { ?>
                   <?=user_helper::full_name($user_id)?><br>
		<? } ?>
	</div>

</div>