<div class="form_bg">
	<h1 class="column_head mt15"><?=t('Редактирование записи')?></h1>
    <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="POST" enctype="multipart/form-data">
	<table width="100%" class="mt10">
	<tbody>
        <tr>
            <td width="18%" class="aright"><?=t('Фото')?></td>
            <td>
            <input type="file" rel="Введiть шлях до фото" name="photo" style="width: 500px;" class="text" id="photo" value="<?=$item['photo']?>"/>
            <input type="hidden" name="id" value="<?=$item['id']?>"/>
            </td>
	</tr>
        <tr>
            <td width="18%" class="aright"><?=t('Автор')?></td>
            <td>
            <input type="text" rel="Введiть автора" name="author" style="width: 500px;" class="text" id="author" value="<?=htmlspecialchars(stripslashes($item['author']))?>"/>
            </td>
	</tr>
        <tr>
            <td width="18%" class="aright"><?=t('Название')?></td>
            <td>
            <input type="text" rel="Введiть назву" name="title" style="width: 500px;" class="text" id="title" value="<?=htmlspecialchars(stripslashes($item['title']))?>"/>
            </td>
	</tr>
        <tr>
            <td width="18%" class="aright"><?=t('Ссылка')?></td>
            <td>
            <input type="text" rel="Введiть лiнк" name="link" style="width: 500px;" class="text" id="link" value="<?=htmlspecialchars(stripslashes($item['link']))?>"/>
            </td>
	</tr>
	<tr>
            <td></td>
            <td>
                <input type="button" class="button" value="<?=t('Сохранить')?>" onclick="is_valid()" name="valid" id="valid"/>
                <input type="submit" class="hidden" value="<?=t('Сохранить')?>" name="submit" id="submit"/>
                <?=tag_helper::wait_panel() ?>
                <div class="success hidden mr10 mt10"><?=t('Баннер добавлен')?></div>
            </td>
	</tr>
	</tbody>
    </table>
    </form>
</div>
<script type="text/javascript">
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