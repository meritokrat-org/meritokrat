<form id="function_form" class="form mt10">
        <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <input type="hidden" name="type" value="function">
        <table width="100%" class="fs12">
            <? foreach (user_auth_peer::get_functions(false) as $function_id=>$function_title) { $ctn=0; ?>
                <tr>
                        <td class="aright"><input name="function_<?=$function_id?>" class="text func" type="checkbox" value="1" <?=@in_array($function_id, $user_functions) ? 'checked' : ''?> /></td>
                        <td id="wt_holder<?=$function_id?>"> 

                            <?
                            if(@in_array($function_id, $user_functions) && sizeof($user_desktop_funct[$function_id])>0)
                            { 
                                $rg = $user_desktop_funct[$function_id];
                                
                            }
                            else
                            {
                                $rg = array("0"=>array("region_id"=>$user_data['region_id'],"city_id"=>$user_data['city_id']));
                            } 
                            ?>

                            <?=$function_title?>
                           <? if(in_array($function_id,array(5,6,9,10,18))){ foreach($rg as $rgv){ 
                               $rg=$rgv['region_id'];
                               $ct=$rgv['city_id'];
                              ?>
                            <div class="fnct<?=$function_id?>" <?=(@in_array($function_id, $user_functions))?'style="margin-bottom:10px;"':'style="display:none;"'?> class="func_<?=$function_id?>">
                                    <?$regns=geo_peer::instance()->get_regions(1);$regns[0]="Оберiть регiон";ksort($regns);?>
                                    
                                    <? if(in_array($function_id,array(6,10,18))){  ?>

                                        <?=tag_helper::select('region-'.$function_id.'[]',$regns, array('use_values' => false, 'value' => $rg, 'id'=>'region-'.$function_id, 'class'=>'region', 'rel'=>t('Выберите регион'), 'style'=>'margin-top:5px')); ?><br/>

                                        <? if(!$cities = geo_peer::instance()->get_cities($rg))$cities=array();  if($ct){ ?>
                                            <?  ?>
                                            <?// $cities[$ct]=$city['name_' . translate::get_lang()] ?>
                                        <? }else{ ?>
                                            <? $cities[0]=t('- оберiть регiон -') ?>
                                        <? } ?>

                                        <?=tag_helper::select('city-'.$function_id.'[]', $cities , array('use_values' => false, 'value' => $ct,'id'=>'city-'.$function_id, 'class'=>'city', 'rel'=>t('Выберите город/район'), 'style'=>'margin-top:5px')); ?>
                                    
                                    <? }else{ ?>
                                        
                                        <?=tag_helper::select('region-'.$function_id.'[]',$regns, array('use_values' => false, 'value' => $rg, 'id'=>'region-'.$function_id, 'rel'=>t('Выберите регион'), 'style'=>'margin-top:5px')); ?>
                                        
                                    <? } ?>
    <input type="button" name="clear" class="button_gray <?=($ctn==0)?'hide':''?> clear" value=" <?=t('Убрать')?> " />
                                </div>

                            <? $ctn++;} ?>
                                      <div id="wt_add<?=$function_id?>">
                     <input type="button" alt="<?=$function_id?>"  name="wt_add" class="wt_add button <?=!in_array($function_id, $user_functions) ? 'hide' : ''?> " value=" <?=t('Добавить')?> ">
                </div> <?}?>  </td>
                </tr>
            <?  }?>
                <tr>
                        <td></td>
                        <td>
                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                <?=tag_helper::wait_panel('function_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>
        </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $('.wt_add').click(function(){
        var fnc=$(this).attr("alt"); 
        var $place = $('div.fnct'+fnc+':first').clone(); 
        $('div.fnct'+fnc+':last').after($place);
        _wp($place);
       // _clearSelectors($place);
        $place.find('.clear').removeClass('hide');
    });

$('input.func').click(function(){
    iname = $(this).attr('name').replace(/function_/, ""); 
    if(iname==5||iname==6||iname==9||iname==10||iname==18){
        if ($(this).is(':checked')) {
        $('.fnct'+iname).show();
        } else {
        $('.fnct'+iname).hide();
        }        
        $('#wt_add'+iname+' #wt_add').toggle();
        }
    });
$('.region').change(function () {
                var rid = $(this).attr('id').replace(/region-/, "").replace(/_/, ""); 
                var city = $('#city-'+rid); 
                var region = $(this).val();
		if (rid == '0') {
			city.html('').attr('disabled', true);
			return false;
		}
		city.html('<option>завантаження...</option>').attr('disabled', true);

		$.post(	'/profile/get_select',{"region":region},
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				} else {
					var options = '<option value="">- оберіть місто/район -</option>';
					$(result.cities).each(function() {
						options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
					});
					city.html(options).attr('disabled', false);
				}
			},
			"json"
		);
	});
});
function _countrySelectors($this){
    $this.find('.country').unbind('change').change(function () {
        var country_id = $(this).val();
        if (country_id == '0') {
                $this.find('.city, .region').html('<option value="0">&mdash;</option>');
                return(false);
        }
        $this.find('.region').attr('disabled', true).html('<option>завантаження...</option>');

        var url = 'https://<?=context::get('server')?>/profile/get_select';

        $.post(	url,{"country_id":country_id},
            function (result) {
                if (result.type == 'error') {
                        $this.find('.region').html('<option value="9999">закордон</option>').attr('disabled', false);
                        $this.find('.city').html('<option value="9999">закордон</option>').attr('disabled', false);
                }
                else {
                        var options = '<option value="">- оберіть регіон -</option>';
                        $(result.regions).each(function() {
                                options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                        });
                        $this.find('.region').html(options).attr('disabled', false).trigger('change');
                }
            },
        "json"
        );
    });
}

function _regionSelectors($this){
    $this.find('.region').unbind('change').change(function () {
        var region_id = $(this).val();
        if (region_id == '0') {
            $this.find('.city, .region').html('<option value="0">&mdash;</option>');
            return(false);
        }
        $this.find('.city').attr('disabled', true).html('<option>завантаження...</option>');

        var url = 'https://<?=context::get('server')?>/profile/get_select';

        $.post(	url,{"region":region_id},
            function (result) {
                if (result.type == 'error') {
                        $this.find('.city').html('<option value="0">&mdash;</option>').attr('disabled', false);
                        return(false);
                }
                else {
                        var options = '<option value="">- оберіть місто/район -</option>';
                        $(result.cities).each(function() {
                                options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                        });
                        $this.find('.city').html(options).attr('disabled', false);
                }
            },
            "json"
        );
    });
}
$('.clear').click(function(){
    $(this).parent("div").remove();
});    
    function _wp($obj){
    $obj.each(function(){
        var $this = $(this);
        $this.find('.clear').click(function(){$this.remove()});
       _countrySelectors($this);
        _regionSelectors($this);
    });
}
</script>