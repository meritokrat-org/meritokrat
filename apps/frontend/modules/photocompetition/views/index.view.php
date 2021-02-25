<div class="">
<h1 class="mt10 column_head column_center">
    <?=t('Фотоконкурс')?>
<? if (session::is_authenticated()) { ?><!--<a class="right" onclick="Application.ShowHide('upload_form')"><?=t('Загрузить фото')?></a>--> <? } ?>
</h1>  
<? if( ! request::get("act") == "frame" ){ ?>
	<div>
		<img src="/static/images/common/photo_konkurs.jpg" />
	</div>
	<div class="acenter" style="font-size: 16px">
		<a href="/photocompetition?act=frame">Переглянути усі конкурсні роботи</a>
	</div>
<? } else { ?>

<div class="box_content p5 fs12">
<?=t('Сортировка по')?>
    <a href="/photocompetition?sort=votes" class="<?=request::get('sort')!='ts' ? 'bold' : ''?>"><?=t('рейтингу')?></a> |
    <a href="/photocompetition?sort=ts" class="<?=request::get('sort')=='ts' ? 'bold' : ''?>"><?=t('дате')?></a>
    <a href="/help/index?photocompetition" class="right"><?=t('Правила конкурса')?></a>
</div>
<? if (session::is_authenticated()) { ?>
<div class="<?=request::get('act')=='upload' ? '' : 'hide'?> form_bg m5" id="upload_form">
<form action="/photocompetition/upload" id="photo_form" class="form" method="post" enctype="multipart/form-data">
<table width="100%" class="fs12 mt10">
<tr>
        <td class="aright"><?=t('Фото')?></td>
        <td>
                <div class="mb5">
                    <input type="file" name="file"/><br/>
                </div>
        </td>
</tr>
<tr>
        <td class="aright"><?=t('Название')?></td>
        <td>
                <div class="mb5">
                        <input type="text" name="title" value=""/>
                </div>
        </td>
</tr>
<tr>
        <td class="aright"><?=t('Описание')?></td>
        <td>
                <div class="mb5">
                        <textarea name="text" rows="2" cols="1" style="width:280px"></textarea>
                </div>
        </td>
</tr>
<tr>
        <td></td>
        <td>
                <input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button" value=" <?=t('Добавить')?> ">
                <?=tag_helper::wait_panel() ?>
        </td>

</tr>
</table>
</form>
</div>
<? } ?>

<div>

    <? foreach ($photos as $photo_id) {
        $photo=photo_competition_peer::instance()->get_item($photo_id); ?>
    <div class="left box_content p5 m5" style="height:300px;width:232px;">
        <a href="/photocompetition/<?=$photo['id']?>">
            <?=photo_competition_peer::photo($photo['id'], 'mp', array('width'=>'228'))?>
        </a>
        <br>
        <a href="/photocompetition/<?=$photo['id']?>" class="pointer"><?=htmlspecialchars(stripslashes($photo['title']))?></a>
        <br>
        <? if ($photo['user_id']!=session::get_user_id() && !photo_competition_peer::instance()->has_voted($photo['id'], session::get_user_id()) && session::is_authenticated()) { ?>
        <!--<input type="button" class="button vote mt5" id="<?=$photo_id?>" rel="<?=$photo_id?>" class="vote" value="<?=t('Голосовать')?>">-->
        <? } ?>
        <div class="right mr5 mt5">
            <span class="bold" id="votes_<?=$photo['id']?>">
                <?//=session::has_credential('admin') ? '<a href="/photocompetition/voters?id='.$photo["id"].'">'.$photo['votes'].'</a>' : $photo['votes']?>
                <?=$photo['votes']?>
            </span>
            <span class="fs11 cgray">голосів</span>
        </div>
    </div>
    <? } ?>
	
</div>

<div class="clear"></div>

<div style="width:100%">
    <div class="left ml5 mt10 fs11 quiet">
        <?=t('Фотографий на странице')?>:
        <?=tag_helper::select('limit',array('12'=>12,'18'=>18,'30'=>30,'45'=>45),array('id'=>'limit','value'=>db_key::i()->get('photocompetition_'.session::get_user_id().'_limit')))?>
    </div>
    <div class="left mt10 fs11 quiet" style="margin-left:290px">
        <?=t('Всего фотографий')?>: <?=$count?> &nbsp;&nbsp;&nbsp; <?=t('Страниц')?>: <?=ceil($count/$limit)?>
    </div>
    <div class="right pager mr5 mt10"><?=pager_helper::get_long($pager)?></div>
</div>
<? } ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
        $('.vote').click(function(){
        var vote_id = $(this).attr('id');
        var votes = parseInt($('#votes_'+vote_id).html());
        $(this).fadeOut(100);
        $.post(
                '/photocompetition/vote',
                {id: vote_id},
                function () {
                        $('#votes_'+vote_id).html(votes+1 );
                }
        );
                    
    });
    
    $('#limit').change(function(){
        $.post('/photocompetition/change_limit',{'value':$(this).val()},function(){
            window.location = 'http://'+window.location.hostname+'/photocompetition';
        });
    });
    
});

</script>