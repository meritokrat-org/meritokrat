<?
if(!$page = request::get_int('page'))
    $num=1;
else
    $num = ($page-1)*10+1;
?>
<style>
th{text-align:left}
td{font-size:13px;color:black;}
</style>

<div class="mt10">

<h1 class="column_head">
    <?=t('Документы')?>
    <? if(session::has_credential('admin')){ ?>
    <a class="right" href="/docs?action=edit"><?=t('Добавить')?></a>
    <? } ?>
</h1>
<table>
<tr>
    <th width="20">№</th>
    <th><?=t('Название')?></th>
    <? if(session::has_credential('admin')){ ?>
    <th width="40"><?=t('Действия')?></th>
    <? } ?>
</tr>
<? foreach($list as $item){ ?>

<? $document = docs_peer::instance()->get_item($item) ?>

<tr id="doc<?=$document['id']?>">
    <td><?=$num?></td>
    <td>
        <a href="/docs/view?alias=<?=$document['alias']?>"><?=$document['title']?></a>
    </td>
    <? if(session::has_credential('admin')){ ?>
    <td class="aleft">
        <a href="/docs?action=edit&id=<?=$document['id']?>" class="dib icoedt" title="<?=t('Редактировать')?>"></a>
        <a href="javascript:;" class="del ml5 dib icodel" rel="<?=$document['id']?>" title="<?=t('Удалить')?>"></a>
    </td>
    <? } ?>
</tr>
<? $num++;} ?>
</table>

</div>
<div class="right pager"><?=pager_helper::get_full($pager)?></div>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('a.del').click(function(){
        var rel = $(this).attr('rel');
        if(confirm('Ви впевненi що хочете видалити цей документ?')){
            $('#doc'+rel).remove();
            $.post('/docs?action=delete',{'id':rel});
        }
    });
});
</script>