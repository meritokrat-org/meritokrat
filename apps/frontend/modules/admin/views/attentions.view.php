<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
			<h1 class="column_head">Список Важливої Інформації</h1>
                        <div class="right mr15">
<a href="/admin/add_attention">Додати важливу інформацію  &rarr;</a>
                        </div>
                        <div class="clear"></div>
	<? foreach ( $list as $id ) { ?>
		<? $attention_data = attentions_peer::instance()->get_item($id) ?>
                        <div class="box_content p5 mb5" id="div_<?=$attention_data['id']?>">
                        <div class="left">
                        <?=$attention_data['title']?>
                        </div>
                        <div class="right">
                            <?=$attention_data['hidden']=='true' ? '' : tag_helper::image('/icons/check.png',array('class'=>"vcenter mr5"))?>
                            <a href="/admin/edit_attention?id=<?=$attention_data['id']?>" class="dib icoedt"></a>
                            <a id="<?=$attention_data['id']?>" href="javascript:;" class="del_attention ml5 dib icodel"></a>
                        </div>
                        <div class="clear"></div>
                        </div>
	<? } ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){                    
        $("a.del_attention").click(function(){ 
                if (confirm('Видалити?'))
                    {
                var id = this.id;
                $.post('/admin/del_attention',{'del':id},function(data){
                        if(data=='1'){
                                        $("#div_"+id).hide();
                        }
                        else {
                            alert();
                        } 
                            
                });
                }
        });
});   
</script>