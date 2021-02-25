<?php
    $dir=library_files_dirs_peer::instance()->get_item($dir_id);
    
      if ($dir_id==0) $dir['title']=t('Разное'); 
      $dircounter++; // счетчик "двигателя" по позициям ?>
            <?if($dir_id>0){?>
            <div id="<?=$dir_id?>" class="pointer folder_title left mt5 cbrown bold " style="width:<?=!$nosteep?(750-$step).'px':'100%'?>; margin-left: <?=$step?>px; background-color: #f7f7f7;">
                <div class="left ml10" style="color:#640705;">
                <?=stripslashes(htmlspecialchars($dir['title']))?></div>
                <div style="margin-top: 1px;" class="right aright mr5">
                <?=count($files[$dir_id])?>
<?  if ((request::get_int('id')==session::get_user_id() || !request::get_int('id')) && !$nosteep) { ?>
                <? if ($dircounter!=1) { ?><a href="/profile/up_dir?dir_id=<?=$dir_id?>" style="color:#565656;">&#9650;</a> <span class="cgray fs11" style="font-weight:normal"><?=$dir['position']?></span>
                <? }  if($dir_id!=$last_parent_dir) { ?>
                    <a href="/profile/down_dir?dir_id=<?=$dir_id?>" style="color:#565656;">&#9660;</a>&nbsp;
                    <? } ?>
                    <a href="/profile/file_edit?dir_id=<?=$dir_id?>"><img class="ml5" alt="Редагування" src="/static/images/icons/2.2.png"></a>
                <a class="fs12" onclick="return confirm('Ви впевнені?');" href="/profile/filedir_delete?id=<?=$dir_id?>"><img class="ml5" alt="Видалити" src="/static/images/icons/3.3.png"></a>
                <? } ?>
                </div>
            </div>
            <div class="clear"></div>
            <?}?>
            <div id="files_<?=$dir_id?>" class="<?=($dir_id==request::get_int('dir_id',null) || $dir_id==0) ? '' : 'hidden'?>">
            
            <?   if ( $files[$dir_id]) { 
                $counter=0;  
                ?>
            <div class="left" style="<?if(!$nosteep){?>margin-left:<?=(20+$step)?>px;width:<?=(730-$step)?>px;<?}else{?>width:98%;margin-left:10px;<?}?>">
                
            <?  foreach ( $files[$dir_id] as $id ) {
                        $counter++;
                        $file=library_files_peer::instance()->get_item($id); 
                        if (isset($file['files']))
                        $arr=unserialize($file['files']);
                        ?>
                        <div class="mt5" style="border-bottom: 1px solid #f7f7f7;">
                            <div class="left">
                                <div class="ml5"><a target="_blank" href="<?=(isset($file['files']))?context::get('file_server').$file['id'].'/'.$arr[0]['salt']."/".$arr[0]['name']:$file['url']?>"><?=stripslashes(htmlspecialchars($file['title']))?></a></div>
                                <div class="left ml5 fs12"><?=$file['author']?></div>
                            </div>
                <? if (isset($file['files'])) {
                        foreach ($arr as $f) {
                            $ext=end(explode('.', $f['name'])); 
                            ?> <div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
                                <a href="<?=context::get('file_server').$file['id'].'/'.$f['salt']."/".$f['name']?>">
                                    <img src="/static/images/files/<?=library_files_peer::instance()->get_icon($ext)?>">
                                </a></div>
                <?      }      
                        } else { ?> <div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>"><img src="/static/images/files/IE.png"></div> <? } ?>
                        <? if ($file['lang']=='ua' or $file['lang']=='en') { ?><div class="left ml5" style="margin-top:  1<?//=$file['author'] ? '17' : '2'?>px;"><img src="https://s1.meritokrat.org/icons/<?=$file['lang']?>.png"></div><? } ?>
                        <div class="right aright mr5" style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?=$file['size'] ? $file['size'] : '' //$file['exts'] ? library_files_peer::formatBytes(filesize($file['url'])) : ''?>
                            <img id="<?=$id?>" class="info ml5 <?=!isset($file['describe']) ? ' hidden' : '' ?>" alt="Інформація" src="/static/images/icons/1.png">
        <?  if ((request::get_int('id')==session::get_user_id() || !request::get_int('id')) && !$nosteep) { ?>
                        <? if ($counter!=1) { ?>
                            <a href="/profile/up_file?id=<?=$id?>" style="color:#565656;">&#9650;</a>  <span class="cgray fs11" style="font-weight:normal"><?=$file['position']?></span>
                        <? }  if($counter!=count($files[$dir_id])) { ?>
                            <a href="/profile/down_file?id=<?=$id?>" style="color:#565656;">&#9660;</a>&nbsp;
                            <? } ?>
                        <a href="/profile/file_edit?id=<?=$id?>"><img class="ml5" alt="Редагування" src="/static/images/icons/2.png"></a>
                            <a onclick="return confirm('Ви впевнені?');" href="/profile/file_delete?id=<?=$id?>"><img class="ml5" alt="видалення" src="/static/images/icons/3.png"></a>
                    <? } ?>
                        </div>
                <div class="clear"></div>
                <div id="file_describe_<?=$id?>" class="ml10 fs11 hidden"><?=stripslashes(htmlspecialchars($file['describe']))?></div>
                </div>
                <div class="clear"></div>
                <? } ?>
           </div>
            <? }
            if (is_array($array) && count ($array)>0) 
            {
                
                $step+=20;
                foreach ( $array as $dir_id=>$array ) 
                    {
                        include 'listing.php';
                    }
                $step-=20;
            }
            //else 
            ?>
            <? if ((is_array($array) && count ($array)>0) and !is_array($files[$dir_id])) { ?>
                <div class="acenter"><?//=t('Папка пуста')?></div>
                <div class="clear"></div>
            <? } ?>
            <div class="clear"></div>
            </div>