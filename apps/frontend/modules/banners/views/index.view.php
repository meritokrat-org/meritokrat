<div>
    
    <h1 class="mt10 mr10 column_head"><?=t('Баннеры')?></h1>
<? if (session::has_credential('admin')) 
{
    $max=db::get_row("SELECT max(position) FROM banners");
    ?>
	<div class="form_bg mr10 fs12 p10 mb10">
        <div class="right">
            <a onclick="$('#add_photoalbum').show(50);" href="javascript:;"><?=t('Добавить баннер')?></a>
            &nbsp;|&nbsp;
             <a onclick="$('#edit_title').show(50);" href="javascript:;"><?=t('Редактировать заголовок')?></a>
        </div>
		<div class="clear"></div>
        <div id="edit_title" class="hidden" style="display: none;">
            <form class="form_bg mr10 fs12 p10 mb10" id="title_form" method="POST" enctype="multipart/form-data">
        	<table width="100%" class="mt10">
        	<tbody>
                <tr>
                    <td width="18%" class="aright"><?=t('Название блока')?></td>
                    <td>
                    <input type="text" rel="Введiть назву" name="block_title" value="<?=htmlspecialchars(stripslashes($block_title))?>" style="width: 500px;" class="text" id="block_title"/>
                    </td>
    		</tr>
    		<tr>
                    <td></td>
                    <td>
                        <input type="button" class="button" value="<?=t('Сохранить')?>" name="submit" id="submit_title"/>
                        <input type="button" onclick="$('#edit_title').hide();" value="<?=t('Отмена')?>" class="button_gray" id=""/>
                    </td>
    		</tr>
        	</tbody>
            </table>
            </form>
        </div>
        <div id="add_photoalbum" class="hidden" style="display: none;">        
            <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="POST" enctype="multipart/form-data">
        	<table width="100%" class="mt10">
        	<tbody>
                <tr>
                    <td width="18%" class="aright"><?=t('Фото')?></td>
                    <td>
                    <input type="file" rel="Введiть шлях до фото" name="photo" style="width: 500px;" class="text" id="photo"/>
                    <input type="hidden" name="position" value="<?=($max['max']+1)?>"/>
                    </td>
    		</tr>
                <tr>
                    <td width="18%" class="aright"><?=t('Автор')?></td>
                    <td>
                    <input type="text" rel="Введiть автора" name="author" style="width: 500px;" class="text" id="author"/>
                    </td>
    		</tr>
                <tr>
                    <td width="18%" class="aright"><?=t('Название')?></td>
                    <td>
                    <input type="text" rel="Введiть назву" name="title" style="width: 500px;" class="text" id="title"/>
                    </td>
    		</tr>
                <tr>
                    <td width="18%" class="aright"><?=t('Ссылка')?></td>
                    <td>
                    <input type="text" rel="Введiть лiнк" name="link" style="width: 500px;" class="text" id="link"/>
                    </td>
    		</tr>
    		<tr>
                    <td></td>
                    <td>
                        <input type="button" class="button" onclick="is_valid()" value="<?=t('Добавить')?>" name="submit" id="valid"/>
                        <input type="submit" class="button hidden" value="<?=t('Добавить')?>" name="submit" id="submit"/>
                        <input type="button" onclick="$('#add_photoalbum').hide();" value="<?=t('Отмена')?>" class="button_gray" id=""/>
                        <?=tag_helper::wait_panel() ?>
                        <div class="success hidden mr10 mt10"><?=t('Баннер добавлен')?></div>
                    </td>
    		</tr>
        	</tbody>
            </table>
            </form>
        </div>
    </div>
<? } ?>


    <?
    $total=count($items);
    load::view_helper('banner');
    foreach ( $items as $i ) 
    {
        $item = banners_items_peer::instance()->get_item($i);
        $num++;
        ?>
            <div id="s_<?=$i?>" class="pointer banner left"></div>
            <div id="<?=$i?>" class="pointer banner_title left mt5 ml10 cbrown bold " style="width:740px; background-color: #f7f7f7;">
                <div class="left ml10 banner_clicker" style="color:#640705;">
                    <?=stripslashes(htmlspecialchars($item['title']))?>
                </div>
                <div style="margin-top: 1px;" class="right aright mr5">
                <? if (session::has_credential('admin')){ ?>
                    <? if ($num!=1){ ?>
                        <a href="/banners/move?id=<?=$i?>&pos=<?=$item['position']?>&mov=0" style="color:#565656;">&#9650;</a>&nbsp;
                    <? }if($total!=$num) { ?>
                        <a href="/banners/move?id=<?=$i?>&pos=<?=$item['position']?>&mov=1" style="color:#565656;">&#9660;</a>&nbsp;
                    <? } ?>
                    <a href="/banners/edit?id=<?=$i?>"><img class="ml5" alt="Редагування" src="/static/images/icons/2.2.png"/></a>
                    <a class="fs12" onclick="return confirm('Ви впевнені?');" href="/banners/delete?id=<?=$i?>"><img class="ml5" alt="Видалити" src="/static/images/icons/3.3.png"/></a>
                <? } ?>
                </div>
            </div>
            <div class="clear"></div>
            <div id="banner_<?=$i?>" class="hidden" style="padding:5px 0 0 20px;">
                <?=banner_helper::photo($item['photo'], 's', array('class' => 'left','style' => 'padding:1px;margin-right:10px;border:1px solid #640705;float:left;'))?>
                <?=t('Автор')?>: <?=stripslashes($item['author'])?><br/>
                <?=t('Название')?>: <?=stripslashes($item['title'])?><br/>
                <?=t('Ссылка')?>: <?=$item['link']?><br/>
            </div>
    <? } ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
   $(".banner_clicker").click(function() {
        var prnt = $(this).parent().attr('id');
        if(!$("#banner_"+prnt).is(":visible")){
            $("#banner_"+prnt).slideDown(300);
        }else{
            $("#banner_"+prnt).slideUp(300);
        }
    });
    $('#submit_title').click(function(){
        $.post('/banners/title',{'title':$('#block_title').val()});
        $('#edit_title').hide();
    });
});
function is_valid(){
    if($('#author').val()==''){
        alert($('#author').attr('rel'));
    }else if($('#title').val()==''){
        alert($('#title').attr('rel'));
    }else if($('#title').val().length > 40){
        alert('<?=t('Название слишком длинное')?>');
    }else if($('#link').val()==''){
        alert($('#link').attr('rel'));
    }else{
        $('input#submit').click();
    }
}
</script>