<div id="vidatki_form" class="hidden form">
    <div id="vdataholder" class="mt15">
        <? if(is_array($finances)){ ?>
            <? foreach($finances as $v){ ?>
                <? include 'vidatok.php' ?>
            <? } ?>
        <? } ?>
    </div>
    <div id="vformholder">
        <form id="edit_vidatki_form">
            <input type="hidden" name="vid" id="vid" value="" />
            <input type="hidden" name="vregion" id="vregion" value="<?=$group['region_id']?>" />
            <table class="fs12">
                <tr>
                    <td class="aright"><?=t('Дата')?></td>
                    <td><?=user_helper::datefields('vdate',time(),true)?></td>
                </tr>
                <tr>
                    <td class="aright"><?=t('Сумма')?></td>
                    <td><input type="text" class="text" name="vsumm" id="vsumm" value="" style="width:188px" /></td>
                </tr>
                <tr>
                    <td class="aright"><?=t('Описание')?></td>
                    <td><textarea name="vtext" id="vtext"></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="button" class="button" id="vsubmit" value="<?=t('Сохранить')?>" />
                        <input type="button" class="button_gray hide" id="vcancel" value="<?=t('Отмена')?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#vsubmit').click(function(){
            $.post('/ppo/vidatki',{
                'id' : $('#vid').val(),
                'date_day' : $('#vdate_day').val(),
                'date_month' : $('#vdate_month').val(),
                'date_year' : $('#vdate_year').val(),
                'summ' : $('#vsumm').val(),
                'text' : $('#vtext').val(),
                'region_id' : $('#vregion').val()
            },function(data){
                _returnform();
                $('#vdataholder').html(data);
            });
        });
        $('#vidatki_form').delegate(".vedit","click",function(){
            var elem = $('#vdata'+$(this).attr('rel'));
            elem.find('span').each(function(){
                var html = $(this).html();
                var cls = $(this).attr('class');
                $('#'+cls).val(html);
            });
            $('#edit_vidatki_form').insertAfter(elem);
            $('#vcancel').show();
        });
        $('#vcancel').click(function(){
            _returnform();
        });
        $('#vidatki_form').delegate(".vdelete","click",function(){
            if(confirm('<?=t('Вы уверены?')?>')){
                var id = $(this).attr('rel');
                $.post('/ppo/vidatki',{
                    'id' : id
                },function(){
                    $('#vdata'+id).remove();
                    _returnform();
                });
            }
        });
    });
    function _returnform(){
        $('#vformholder').append($('#edit_vidatki_form'));
        $('#vid').val('');
        $('#vsumm').val('');
        $('#vtext').val('');
        $('#vcancel').hide();
    }
</script>