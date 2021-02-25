<form id="photo_form" action="" method="post" class="form mt10" enctype="multipart/form-data">
		<? if ( session::has_credential('admin') ) { ?>
			<input type="hidden" name="id" value="<?=$user_data['user_id']?>">
		<? } ?>

		<div class="left acenter">
                    <?if(!$file_path){?>
			<?=user_helper::photo($user_data['user_id'], 'p', array('class' => 'border1', 'id' => 'photo'))?>
                    <div id="rads" class="right">
                    <?}else{?>
                    <div class="left"><img class="border1" id="photo" src="<?=$file_path?>2.jpg"  /><?}?>
                        <table>
                            <?/*if($file_path){?>
                            <tr>
                                <td>
                                <div><input checked class="mt15 mr5" type="radio" value="1" name="filter"  />
                                    <div class="right mr5" style="background: url(/static/images/logo.png) repeat scroll 48% 52% black; height: 45px; width: 45px;"></div>
                                </div>
                                </td>
                                <td style="padding-top:0;">
                                    <div style="width:80px;"><input type="radio" class="mt15" value="2" name="filter"  />
                                        <div class="right"><img src="/static/images/logo2.png"  /></div>
                                    </div>
                                </td>
                            </tr><?}*/?>
                            <tr>
                                <td></td>
                                <td>
                                  <table class="left fs12" style="width: 430px;">
                                            <?if(!$file_path){?><tr>
                                                    <td colspan="2" class="cgray aleft fs11">
                                                            <?=t('Вы можете загрузить фотографии в формате JPG, PNG или GIF размером')?> <b><?=t('не более 2 Мб.')?></b> <?=t('Пожалуйста, загружайте только собственное фото, не используйте посторонних изображений.')?><br/><br/>
                                                            <?=t('При возникновении проблем попробуйте загрузить фотографию меньшего размера или')?> <a href="mailto:secretariat@meritokratia.info"><?=t('отправьте сообщение Секретариату МПУ')?></a><br/>
                                                           <br />
                                                    </td>
                                            </tr>
                                            <tr>
                                                    <td class="aright" width="30%"><?=t('Выберите файл')?></td>
                                                    <td><input type="file" name="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /></td>
                                            </tr><?}?>
                                            <tr>
                                                    <td></td>
                                                    <td> <?if(!$file_path){?>
                                                            <input id="su" type="submit" name="submit" class="button_gray" value="<?=t('Загрузить')?> ">
                                                            <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value="<?=t('Отмена')?> ">
                                                            <? } else { ?>
                                                            <?/* if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])) { ?><span id="dwn" <?=(!$file_path)?'class="hidden"':''?>><a class="button dwnb" onclick="document.execCommand('SaveAs',true,'/static/php/getfile.php?f=<?=$file_path?>1.jpg');"><?=t('Скачать')?></a></span><? } else {*/ ?>
                                                            <span id="dwn" <?=(!$file_path)?'class="hidden"':''?>><a class="button dwnb" href="/static/php/getfile.php?f=<?=$file_path?>2.jpg"><?=t('Скачать')?></a></span> <?// } ?>
                                                            <span id="dwn" <?=(!$file_path)?'class="hidden"':''?>><a class="button dwnb" href="/profile/photobranding"> <?=t('Загрузить другое фото')?></a></span>
                                                            <?}?>
                                                    </td>
                                            </tr>
                                    </table>
                              </td>
                            </tr>
                       </table>
                    </div>
                    
		</div>

		<div class="clear"></div><br />
	</form>
      <script type="text/javascript">
  	$('#rads .mt15').click(function(){
            $('#photo').attr('src', '<?=$file_path?>'+this.value+'.jpg');
            $('#dwn').html('<a class="button dwnb" href="/static/php/getfile.php?f=<?=$file_path?>'+this.value+'.jpg"><?=t('Скачать')?></a>');
        });
        $('#su').click(function(){
            if(!$(this).hasClass('button'))
                return false;
            else
               $(this).removeClass('button_gray').addClass('button'); 
        });
        $('input[name="file"]').change(function(){
            $('input[name="submit"]').removeClass('button_gray').addClass('button');
        });
      </script>
<style>
.dwnb{
  padding:3px;

}

a.dwnb:hover
{
  text-decoration:none;
}
</style>