<? $num=1 ?>
<style>
    td{text-align:center;font-size:12px;color:black;}
</style>
<div class="mt10">

<h1 class="column_head"><?=t('Текстовые страницы')?><a class="right" href="/admin/helpedit"><?=t('Добавить')?></a></h1>
<table>
<tr>
    <th width="30">№</th>
    <th style="text-align:left">URL</th>
    <th style="text-align:left"><?=t('Название')?></th>
    <th width="40"><?=t('Действия')?></th>
</tr>
<? foreach($list as $template){ ?>

<? $info = help_peer::instance()->get_item($template) ?>

<tr id="page<?=$info['id']?>">
    <td><?=$num?></td>
    <td style="text-align:left" class="halias">
        <a href="http://<?=conf::get('server')?>/help/index?<?=$info['alias']?>">
            http://<?=conf::get('server')?>/help/index?<?=stripslashes(htmlspecialchars($info['alias']))?>
        </a>
    </td>
    <td style="text-align:left">
        <a href="/admin/helpedit?id=<?=$info['id']?>">
        <?=(session::get('language')!='ru')?stripslashes(htmlspecialchars($info['title_ua'])):stripslashes(htmlspecialchars($info['title_ru']))?>
        </a>
    </td>
    <td style="text-align:center">
        <a href="/admin/helpedit?id=<?=$info['id']?>" class="dib icoedt" title="<?=t('Редактировать')?>"></a>
        <a href="javascript:;" class="hdel ml10 dib icodel" rel="<?=$info['id']?>" title="<?=t('Удалить')?>"></a>
    </td>
</tr>
<? $num++;} ?>
</table>

</div>

<script type="text/javascript">
$(document).ready(function($){
    $('a.hdel').unbind('click').click(function(){
        var id = $(this).attr('rel');
        if(confirm("<?=t('Вы уверены, что хотите удалить эту страницу?')?> "+$('#page'+id).find('td.halias').text())){
            $.post('/admin/helpdelete',{'id':id},function(){
                $('#page'+id).remove();
            });
        }else{
            return false;
        }
    });
});
</script>