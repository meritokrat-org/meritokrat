<div class="sub_menu mt5 mb10 ml10">
	<a style="margin-right:7px" href="/comments?type=mine" <?=$type == 'mine' ? 'class="bold"' : ''?>><?=t('К моим мыслям')?></a>
	<a style="margin-right:7px" href="/comments?type=ppl" <?=$type == 'ppl' ? 'class="bold"' : ''?>><?=t('К комментированным мною мыслям')?></a>
        <a style="margin-right:7px" href="/comments?type=fav" <?=$type == 'fav' ? 'class="bold"' : ''?>><?=t('К мыслям любимых авторов')?></a>
        <a style="margin-right:7px" href="/comments?type=favppl" <?=$type == 'favppl' ? 'class="bold"' : ''?>><?=t('Любимые авторы')?></a>
        <a style="margin-right:7px" href="/comments?type=bkm" <?=$type == 'bkm' ? 'class="bold"' : ''?>><?=t('К мыслям в закладках')?></a><br>
        <a style="margin-right:7px" href="/comments?type=groups_posts_comments" <?=$type == 'groups_posts_comments' ? 'class="bold"' : ''?>><?=t('К мыслям в моих сообществах')?></a>
</div>