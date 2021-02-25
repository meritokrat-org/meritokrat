<div id="grpfiles">
    
    <?  
    $count_dirs=count($dirs)-1; 
    if (is_array($dirs_tree) and count($dirs_tree)>0) {
    foreach ( $dirs_tree as $dir_id=>$array ) 
        {
                $step=0;
                include 'listing.php'; 
        } ?>
  <?  }   ?>
 </div>
<div id="grplst" class="hide">
<? $last=groups_files_peer::instance()->get_list(array("group_id"=>$group['id']),
        array(),array('id DESC'),10);
?>
     <? if(is_array($last))foreach ( $last as $id ) {
                    $counter++;
                    $file=groups_files_peer::instance()->get_item($id);
                    if (isset($file['files']))
                                       $arr=unserialize($file['files']);?>
                    <div class="mt5" style="border-bottom: 1px solid #f7f7f7;">
                        <div class="left">
                            <div class="ml5"><a href="<?=(isset($file['files']))?context::get('file_server').$file['id'].'/'.$arr[0]['salt']."/".$arr[0]['name']:$file['url']?>"><?=stripslashes(htmlspecialchars($file['title']))?></a></div>
                              <div class="left ml5 fs12"><?=$file['author']?></div>
                        </div>
                    <? if (isset($file['files'])) {
                        foreach ($arr as $f) {
                            $ext=end(explode('.', $f['name'])); 
                            ?> <div class="left ml5">
                                <a href="<?=context::get('file_server').$file['id'].'/'.$f['salt']."/".$f['name']?>">
                                    <img src="/static/images/files/<?=groups_files_peer::instance()->get_icon($ext)?>"/>
                                </a></div>
                <?      }      
                        } else { ?> <div class="left ml5"><img src="/static/images/files/IE.png"></div> <? } ?>
                                       <? if ($file['lang']=='ua' or $file['lang']=='en') { ?><div class="left ml5" style="margin-top:  1<?//=$file['author'] ? '17' : '2'?>px;"><img src="https://s1.meritokrat.org/icons/<?=$file['lang']?>.png"></div><? } ?>
                            <div class="right aright <?=!$nosteep?'mr5':'mr25'?>" style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?=$file['size'] ? $file['size'] : '' //$file['exts'] ? groups_files_peer::formatBytes(filesize($file['url'])) : ''?>
                                <img id="<?=$id?>" class="info ml5 <?=!isset($file['describe']) ? ' hidden' : '' ?>" alt="Інформація" src="/static/images/icons/1.png" />
                            </div>
                    <div class="clear"></div>
                    <div id="file_describe_<?=$id?>" class="ml10 fs11 hidden"><?=stripslashes(htmlspecialchars($file['describe']))?></div>
                    </div>
                <div class="clear"></div>
                <? } ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
  showOrHide = false;   
   $("#flshowall").click(function(){
        $('.fls').hide();
   });

   $("#lstfiles").click(function(){
 if ( showOrHide == true ) {
          $('#grpfiles').show();
        $('#grplst').hide();
        $('#lstfiles').html('<?=('Последние')?>');
        showOrHide = false;   
} else if ( showOrHide == false ) {
        $('#grpfiles').hide();
        $('#grplst').show();
        $('#lstfiles').html('<?=('Все')?>');
        showOrHide = true;
}
   });
   
   $(".file").hover(function() {
       $(this).addClass("folder_selected");
   }, function() {
       $(this).removeClass("folder_selected");
   });
   $(".folder").click(function() {
                if(!$("#file"+this.id).is(":visible"))
                {
                       $("#file"+this.id).slideDown(300);

                }
                else
                {
                       $("#file"+this.id).slideUp(300);
                }
});
   $(".folder_title").click(function() {
                if(!$("#files_"+this.id).is(":visible"))
                {
                        $("#files_"+this.id).slideDown(300);

                }
                else
                {
                       $("#files_"+this.id).slideUp(300);
                }
});
   
});
</script>