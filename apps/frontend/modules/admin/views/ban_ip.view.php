<script type="text/javascript" src="/static/javascript/library/form/form.js"></script>
<div class="left ml10 mt10" style="width: 35%;">
    <?include 'partials/left.php';?>
</div>
<div class="left ml10 mt10 acenter" style="width: 62%;">
    <h1 class="column_head">Стоимость</h1>
    <form class="form form_bg" id="ban_ip_form">
        <input type="hidden" name="operation" value="ban_ip">
        <table>
            <tr>
                <td class="aright">
                    IP-address:
                </td>
                <td>
                    <input type="text"  style="width: 250px;" class="text" name="ip">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" class="button" name="submit" value="<?=t('Сохранить')?>">
                </td>
            </tr>
        </table>
    </form>
    <div class="clear"></div>
    <div class="success hidden"><?=t('Изменения сохранены')?></div>
    <div class="error hidden"></div>
</div>
<script>
    var banForm = new Form(
        'ban_ip_form',
        {
            success: function(data) {
                resp = data;//eval("("+data+")");
                if(resp.success==1) {
                    $('.success').fadeIn(300, function() {  $(this).fadeOut(5000); });
                    $('input[type="text"').val('');
                }
                else {
                    $('.error').html(resp.reason);
                    $('.error').fadeIn(300, function() {  $(this).fadeOut(5000); });
                }
            }
        }
    );
    
    
  
    
    
    
    
</script>
