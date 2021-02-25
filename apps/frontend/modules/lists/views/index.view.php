<div id="list_holder">
<h1 class="mt10 mb10 mr10 column_head"><?=t('Списки участников')?></h1>

<? foreach ( $list as $i ) { ?>
<? if ($item = lists_peer::instance()->get_item($i)) { ?>
    <? include 'item.view.php'; ?>
<? } } ?>

</div>

<div id="form_holder" class="left mt5 ml15 cbrown bold" style="width:740px; background-color: #f7f7f7; color:#640705;">
    <form action="/lists/change" method="POST" class="hide left" id="change_form">
        <input type="hidden" name="id" value=""/>
        <input type="text" name="title" class="text" style="width:300px" value=""/>
        <input type="checkbox" id="in_team" name="in_team" style=""/> - В команду
        <input type="button" name="ok" class="button" value="ОК"/>
        <input type="button" name="undo" class="button_gray" value="<?=t('Отмена')?>"/>
    </form>
</div>

<? if(session::has_credential('admin')){ ?>
<div class="left mt10 ml10 cbrown">
    <a href="#add" class="fs12 ml10"><?=t('Добавить')?> &rarr;</a>
</div>
<? } ?>

<script type="text/javascript">
jQuery(document).ready(function(){
    var $id =  $('#change_form').find('input[name="id"]');
    var $title = $('#change_form').find('input[name="title"]');
    var $in_team = $('#change_form').find('input[name="in_team"]');

    var $form = $('#change_form');

    function _bind(){
        $('div.title').unbind('click').click(function(){
            Application.showList($(this).attr('id'));
        });
        $('a[href$="#add"]').unbind('click').click(function(){
            $('#form_holder').append($form);
            $id.val('');
            $title.val('');
            $($in_team).attr("checked", false)
            $form.show();
        });
        $('a[href$="#edit"]').unbind('click').click(function(){
            var id = $(this).attr('alt');
            var $obj = $('#list_'+id).find('div.title');
            var $team = $('#list_'+id).find('#in_team');

            $id.val(id);
            $title.val($obj.html());
            $($in_team).attr("checked", $($team).attr("checked") ? 1 : 0);
            $($team).attr("checked", $($team).attr("checked") ? 1 : 0)

            $obj.hide().end().prepend($form);
            $form.show();
        });
    }
    _bind();
    $form.find('input[name="undo"]').click(function(){
        $form.hide();
        $('div.title').show();
    });
    $form.find('input[name="ok"]').click(function(){
        if($title.val()!=''){
            if($id.val()!=''){
                $form.hide();
                $('#list_'+$id.val()).find('div.title').text($title.val()).show();
            }

            $.post('/lists/change',{'id':$id.val(),'title':$title.val(), 'in_team':$($in_team).attr("checked") ? 1 : 0},function(data){

                if(data.length!=0){
                    $('#list_holder').append(data);
                    $form.hide();
                    _bind();
                }
            });
        }else{
            alert('<?=t('Введите текст')?>');
        }
    });
});
</script>