<div id="pane_files" class="content_pane hidden">
    <div class="box_content p5 fs11">
<? if ($user_data['user_id']==session::get_user_id()) { ?>
        <a href="/profile/file?id=<?= $user['id'] ?>&add=1"><?= t('Добавить материал') ?></a>
        <? } ?>
    </div>
        <?   $max=db::get_row("SELECT max(position) FROM files_dirs WHERE type=1 AND parent_id=0 AND object_id=".$user_data['user_id']); ?>
            <?
    $count_dirs=count($dirs)-1;
  $nosteep=1;
    if(is_array($dirs_tree))
    foreach ( $dirs_tree as $dir_id=>$array ) 
    {
            $step=0;
            #include __DIR__.'/../file/listing.php';
           include conf::get('project_root').'/apps/frontend/modules/profile/views/partials/file/listing.php';
    }
    ?>
    <div class="box_content p5 mb10 fs11"><a href="/profile/file?id=<?= $user['id'] ?>"><?= t('Все материалы') ?> &rarr;</a></div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
   $("#showall").click(function(){
   <? foreach ( $dirs as $dir_id ) { ?>
   $("#files_<?=$dir_id?>").show();
   <? } ?>
   $("#showall").hide();
   $("#hideall").show();
   });
   
   $("#hideall").click(function(){
   <? foreach ( $dirs as $dir_id ) { ?>
   $("#files_<?=$dir_id?>").hide();
   <? } ?>
   $("#hideall").hide();
   $("#showall").show();
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