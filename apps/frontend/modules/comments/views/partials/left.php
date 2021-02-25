<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('sort')">
<div class="left"><?=t('Виды мыслей')?></div>
<div class="right mt5 icoupicon" style="cursor: pointer;" id="sort_on"></div>
<div class="right mt5 icodownt hide" style="cursor: pointer;" id="sort_off"></div>
</div>
<div class="box_content p10" id="sort">
        <div class="right" style="cursor: pointer; padding-top: 40px;">         
            <?=tag_helper::image('common/btn_arrow_left_gray.jpg',array('id'=>"mstrl",'width'=>'30'))?>
        </div>
	<ul class="mb5" style="font-size:13px;">
	<li><a href="/comments?type=mine" <?=$type == 'mine' ? 'class="bold"' : ''?>><?=t('К моим мыслям')?></a></li>
        <li><a href="/comments?type=my_child_comments" <?=$type == 'my_child_comments' ? 'class="bold"' : ''?>><?=t('К моим комментариям')?></a></li>
        <li><a href="/comments?type=ppl" <?=$type == 'ppl' ? 'class="bold"' : ''?>><?=t('К комментированным мною мыслям')?></a></li>
        <li><a href="/comments?type=fav" <?=$type == 'fav' ? 'class="bold"' : ''?>><?=t('К мыслям любимых авторов')?></a></li>
        <li><a href="/comments?type=favppl" <?=$type == 'favppl' ? 'class="bold"' : ''?>><?=t('Любимые авторы')?></a></li>
        <li><a href="/comments?type=bkm" <?=$type == 'bkm' ? 'class="bold"' : ''?>><?=t('К мыслям в закладках')?></a></li>
        <li><a href="/comments?type=groups_posts_comments" <?=$type == 'groups_posts_comments' ? 'class="bold"' : ''?>><?=t('К мыслям в моих сообществах')?></a></li>
        <? if(session::has_credential('admin')){ ?>
        <li><a href="/comments?type=ideal" <?=$type == 'ideal' ? 'class="bold"' : ''?>>*<?=t('До Ідеальної Країни')?></a></li>
        <? } ?>
        </ul>
</div>