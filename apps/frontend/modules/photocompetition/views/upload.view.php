<div class="">
<h1 class="mt10 column_head column_center">
    <a href="/photocompetition"><?=t('Фотоконкурс')?></a> &rarr; <?=t('Загрузка фотографии')?>
</h1>
<form action="/photocompetition/upload" id="photo_form" class="form mt15" method="post" enctype="multipart/form-data">
<table width="100%" class="fs12">
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