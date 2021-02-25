<!--<script type="text/javascript" src='/static/javascript/library/plugins/ajax.file.upload.js'></script>-->
<form id="photo_form" action="/profile/branding" method="post" class="form mt10" enctype="multipart/form-data">
		<input type="hidden" id="filepath" value=""/>
		<div class="left acenter">
		    <div class="left">
			<div class="original">
			    <?=user_helper::photo($user_data['user_id'], 'p', array('class' => 'border1', 'id' => 'photo','style'=>'width: 200px;'))?>
			</div>
		    </div>
                    <div id="rads" class="right">
			<table class="left fs12" style="width: 430px;">
			    <tr>
				<td colspan="2" class="cgray aleft fs11">
					<?=t('Вы можете загрузить фотографии в формате JPG, PNG или GIF размером')?> <b><?=t('не более 2 Мб.')?></b> <?=t('Пожалуйста, загружайте только собственное фото, не используйте посторонних изображений.')?><br/><br/>
					<?=t('При возникновении проблем попробуйте загрузить фотографию меньшего размера или')?> <a href="http://<?=context::get("host")?>/messages/compose?user_id=10599"><?=t('отправьте сообщение Администрации сайта "Меритократ"')?></a><br/>
				    <br />
				</td>
			    </tr>
			    <tr>
				    <td class="aright" width="30%"><?=t('Выберите файл')?></td>
				    <td><input type="file" name="file" id="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /></td>
			    </tr>
			    <tr>
				    
				    <td colspan="2">  
					<input type="button" id="save-image" class="button hidden" value="<?=t('Сохранить')?>"/>
					<input type="button" id="upload-image" class="button" value="<?=t('Загрузить')?>"/>
					
<!--					<input type="button" id="save-image" class="button hidden" value="<?=t('Загрузить друго')?>"/>-->
					<input type="button" id="cancel" name="submit" class="button_gray" value="<?=t('Отменить')?>"/>
				    </td>
			    </tr>
			</table>
		    </div>
                    
		</div>

		<div class="clear"></div><br />
	</form>
      <script type="text/javascript">
	var selectArea = new Object({
	    "x" : 0,
	    "y" : 0,
	    "w" : 100,
	    "h" : 100
	});
	var setImgArea = function() {
	    $('[id="photo"]').imgAreaSelect({
				handles: true,
				aspectRatio: '1:1',
				x1: 0,
				x2: 100,
				y1: 0,
				y2: 100,
				onSelectEnd: function(img, selection) {
				    
				    var scaleX = 100/img.width;
				    var scaleY = 100/img.height;
				    
				    selectArea.x = (selection.x1*scaleX);
				    selectArea.w = (selection.width*scaleX);
				    selectArea.y = (selection.y1*scaleY);
				    selectArea.h = (selection.height*scaleY);
				    
				    if(!parseInt(selectArea.w)) {
					if(!$('[id="save-image"]').hasClass('hidden'))
					    $('[id="save-image"]').addClass('hidden');
				    }
				    else {
					$('[id="save-image"]').removeClass('hidden');
				    }
				    
				}
			    });
	}
	  
	$('[id="save-image"]').click(function() {  
		window.location = '/profile/branding?act=brand&file='+$('#filepath').val()+'&crop[x]='+selectArea.x+'&crop[y]='+selectArea.y+'&crop[w]='+selectArea.w+'&crop[h]='+selectArea.h;
	});
	
  	$('[id="upload-image"]').click(function() {
	    $.ajaxFileUpload
	    (
		{
		    url:'/profile/branding?act=upload', 
		    secureuri:false,
		    fileElementId:'file',
		    dataType: 'json',
		    success: function (data, status)
		    {
			    $('[id="save-image"]').removeClass('hidden');
			    $('[id="photo"]').attr('src','/static/branding/'+context.user_id+'/'+data);
			    $('#filepath').val(data);
			    
			    var timer = setTimeout(setImgArea, 500);
			    
		    }
		}
	    );
	
	});
      </script>
<style>
.imgareaselect-selection {
    background: url("http://<?=conf::get('server')?>/static/images/logo2.png") no-repeat scroll 95% 95% transparent !important;
}
.imgareaselect-outer {
    background-color: #000000;
    opacity: 0.7;
}
</style>