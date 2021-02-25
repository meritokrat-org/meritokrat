<?php
//$step=20;

    $dir=library_files_dirs_peer::instance()->get_item($dir_id);
      if ($dir_id==0) $dir['title']=t('Разное');
      $dircounter++; // счетчик "двигателя" по позициям ?>
            <? /*  <div id="s_<?=$dir_id?>" class="pointer folder left"> </div> */ ?>
            
            <div id="<?=$dir_id?>" class="pointer folder_title left mt5 cbrown bold " style="width:<?=(750-$step)?>px; margin-left: <?=$step?>px; background-color: #f7f7f7;">
                <div class="left ml10" style="color:#640705;">
                <?=stripslashes(htmlspecialchars($dir['title']))?></div>
                <div style="margin-top: 1px;" class="right aright mr5">
                <?=count($files[$dir_id])?>
<? if (session::has_credential('admin') and $dir_id!=0)   { ?>
                <? if ($dircounter!=1) { ?><a href="/library/up_dir?dir_id=<?=$dir_id?>" style="color:#565656;">&#9650;</a> <span class="cgray fs11" style="font-weight:normal"><?=$dir['position']?></span>
                <? }  if($dir_id!=$last_parent_dir) { ?>
                    <a href="/library/down_dir?dir_id=<?=$dir_id?>" style="color:#565656;">&#9660;</a>&nbsp;
                    <? } ?>
                    <a href="/library/file_edit?dir_id=<?=$dir_id?>" title="<?=t('Редактировать')?>" class="dib icoedt"></a>
                    <a onclick="return confirm('Ви впевнені?');" title="<?=t('Удалить')?>" href="/library/filedir_delete?id=<?=$dir_id?>" class="dib ml5 icodel"></a>
                <? } ?>
                </div>
            </div>
            <div class="clear"></div>
            
            <div id="files_<?=$dir_id?>" class="<?=($dir_id==request::get_int('dir_id',null) and $dir_id!=0) ? '' : 'hidden'?>">
            
            <? if ( $files[$dir_id]) {
                $counter=0;  
                ?>
            <div class="left" style="margin-left:<?=(20+$step)?>px;width:<?=(730-$step)?>px;">
                
            <? foreach ( $files[$dir_id] as $id ) {
                $counter++;
                $file=library_files_peer::instance()->get_item($id);
                if (isset($file['files']))
                $arr=unserialize($file['files']);
                ?>
                <div class="mt5" style="border-bottom: 1px solid #f7f7f7;">
                    <div class="left">
                        <div class="ml5">
                            <? if(isset($file['files'])){ ?>
                            <a target="_blank" href="<?=context::get('file_server').$file['id'].'/'.$arr[0]['salt']."/".$arr[0]['name']?>"><?=stripslashes(htmlspecialchars($file['title']))?></a>
                            <? }else{ ?>
                            <?=user_helper::get_links('<a href="'.$file['url'].'">'.stripslashes(htmlspecialchars($file['title'])).'</a>',false)?>
                            <? } ?>
                        </div>
                        <div class="left ml5 fs12"><?=$file['author']?></div>
                    </div>
                    <? if (isset($file['files'])) { ?>
                        <? foreach ($arr as $f) { ?>
                            <? $ext=end(explode('.', $f['name'])) ?>
                            <div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
                                <a href="<?=context::get('file_server').$file['id'].'/'.$f['salt']."/".$f['name']?>" class="dib ico<?=strtolower(str_replace(array('-','.png'),'',library_files_peer::instance()->get_icon($ext)))?>"></a>
                            </div>
                        <? }} else { ?>
                            <div class="left ml5 icoie <?//=$file['author'] ? 'mt15' : ''?>"></div>
                        <? } ?>
                        <? if ($file['lang']=='ua' or $file['lang']=='en') { ?>
                            <div class="left ml5 ico<?=$file['lang']?>" style="margin-top:  1<?//=$file['author'] ? '17' : '2'?>px;"></div>
                        <? } ?>
                        <div class="right aright mr5" style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?=$file['size'] ? $file['size'] : '' //$file['exts'] ? library_files_peer::formatBytes(filesize($file['url'])) : ''?>
                            <span id="<?=$id?>" class="info ml5 dib icoinf <?=!isset($file['describe']) ? ' hidden' : '' ?>" title="Інформація"></span>
        <? if (session::has_credential('admin'))   { ?>
                        <? if ($counter!=1) { ?>
                            <a href="/library/up_file?id=<?=$id?>" style="color:#565656;">&#9650;</a>  <span class="cgray fs11" style="font-weight:normal"><?=$file['position']?></span>
                        <? }  if($counter!=count($files[$dir_id])) { ?>
                            <a href="/library/down_file?id=<?=$id?>" style="color:#565656;">&#9660;</a>&nbsp;
                        <? } ?>
                            <a href="/library/file_edit?id=<?=$id?>" class="dib icoedt" title="<?=t('Редактировать')?>"></a>
                            <a onclick="return confirm('Ви впевнені?');" href="/library/file_delete?id=<?=$id?>" class="dib icodel" title="<?=t('Удалить')?>"></a>
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