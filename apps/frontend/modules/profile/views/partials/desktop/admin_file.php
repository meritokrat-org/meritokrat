<div class="mt5" style="border-bottom: 1px solid #f7f7f7;">
    <div>
        <div class="ml5">
            <?
    if (isset($file['files'])) {
        foreach ($arr as $f) {
            $ext = end(explode('.', $f['name']));
            ?> 
                <a href="<?= context::get('file_server') . $file['id'] . '/' . $f['salt'] . "/" . $f['name'] ?>">
                    <img src="/static/images/files/<?= library_files_peer::instance()->get_icon($ext) ?>">
                </a>
        <? }
    } else { ?> <img src="/static/images/files/IE.png"/> <? } ?>
            <a <?= $file['url'] ? 'target="_blank"' : '' ?>  class="info" id="159" 
         href="<?= context::get('file_server') . $file['id'] . '/' . $f['salt'] . "/" . $f['name'] ?><?//= ($arr[0]) ? context::get('file_server') . $file['id'] . '/' . $arr[0]['salt'] . "/" . $arr[0]['name'] : $file['url'] ? $file['url'] : 'javascript:;' ?>">
    <?= ($file['title'])?stripslashes(htmlspecialchars($file['title'])):'Без назви' ?> </a> -
    <?=user_helper::full_name($file['admin_id'],true,array(),false)?> 
              
    </div></div>
    <div class=" aright mr5" style="border-bottom: 1px solid #d7d7d7; color:#565656;">
    <?=($file['date'] && $file['size']>0)?date("H:i d-m-y",$file['date']).', ':'' ?><?= $file['size'] > 0 ? $file['size'] : '' ?>
        <img id="<?= $id ?>" class="info ml5 <?= !isset($file['describe']) ? ' hidden' : '' ?>" alt="Інформація" src="/static/images/icons/1.png">
        <? if (session::has_credential('admin')) { ?>
            <a href="/profile/admin_file_edit?id=<?= $id ?>"><img class="ml5" alt="Редагування" src="/static/images/icons/2.png"></a>
            <a onclick="return confirm('Ви впевнені?');" href="/profile/admin_file_delete?id=<?= $id ?>"><img class="ml5" alt="видалення" src="/static/images/icons/3.png"></a>
<? } ?>
    </div>
    <div class="clear"></div>
    <div id="file_describe_<?= $id ?>" class="ml10 fs11 hidden"><?= stripslashes(htmlspecialchars($file['describe'])) ?></div>
</div>
<div class="clear"></div>       