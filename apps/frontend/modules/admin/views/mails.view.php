<? $num=1 ?>
<? if(session::get('language')!='ru') $lang='ua'; else $lang='ru'; ?>
<style>
    td{text-align:center;font-size:12px;color:black;}
</style>
<div class="mt10">

<h1 class="column_head"><?=t('Шаблоны сообщений')?><a class="right" href="/admin">НАЗАД</a></h1>
<table>
<tr>
    <th>№</th>
    <th>Тест</th>
    <th><?=t('Название')?></th>
    <th><?=t('Адрес')?></th>
    <th><?=t('Отправитель')?></th>
</tr>
<? foreach($list as $template){ ?>

<? $email = email_peer::instance()->get_item($template) ?>

<tr>
    <td><?=$num?></td>
    <td width="30">
        <a hreh="javascript:;" class="testmail" id="<?=$email['id']?>" style="cursor:pointer">Тест</a>
        <img class="hide" width="15" src="/static/images/common/loading.gif"/>
    </td>
    <td style="text-align:left"><a href="/admin/medit?id=<?=$email['id']?>"><?=$email['name']?></a></td>
    <td><?=$email['sender_mail']?></td>
    <td><?=$email['sender_name_'.$lang]?></td>
</tr>
<? $num++;} ?>
</table>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('a.testmail').click(function(){
            var $this = $(this);
            $this.hide().next('img').show();
            $.post('/admin/testmail',{'id':this.id},function(data){
                $this.show().next('img').hide();
                if(data=='error')
                    alert('При отправке сообщения возникли ошибки.');
                else if(data=='ok')
                    alert('Сообщение отправлено'); 
            });
        });
    });
</script>