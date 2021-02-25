<? $num=1 ?>
<? if(session::get('language')!='ru') $lang='ua'; else $lang='ru'; ?>
<style>
    td{text-align:center;font-size:12px;color:black;}
</style>
<div class="mt10">

<h1 class="column_head"><?=t('Подсказки')?><a class="right" href="/admin">НАЗАД</a></h1>
<table>
<tr>
    <th>№</th>
    <th style="text-align:left">Alias</th>
    <th style="text-align:left"><?=t('Название')?></th>
</tr>
<? foreach($list as $template){ ?>

<? $info = help_info_peer::instance()->get_item($template) ?>

<tr>
    <td><?=$num?></td>
    <td style="text-align:left"><a href="/admin/infoedit?id=<?=$info['id']?>"><?=stripslashes(htmlspecialchars($info['alias']))?></a></td>
    <td style="text-align:left"><a href="/admin/infoedit?id=<?=$info['id']?>"><?=stripslashes(htmlspecialchars($info['title']))?></a></td>
</tr>
<? $num++;} ?>
</table>

</div>