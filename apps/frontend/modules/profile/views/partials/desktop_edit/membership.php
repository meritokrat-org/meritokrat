<form id="membership_form" class="form mt10 <?=!session::has_credential('admin') ? '' : 'hidden'?>">
    <? if ( session::has_credential('admin') ) { ?>
            <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
    <? } ?>
    <input type="hidden" name="type" value="membership">
    <? if($membership['id']){ ?>
        <input type="hidden" name="mid" value="<?=$membership['id']?>" />
    <? } ?>
    <table width="100%" class="fs12" id="membership_table">

        <tr>
            <td class="aright"></td>
            <td class="bold" style="padding-top:20px"><?=t('Решение про принятие в члены МПУ')?></td>
        </tr>
        <tr>
            <td class="aright"><?=t('Дата')?></td>
            <td>
                <?=user_helper::datefields('invdate',$membership['invdate'])?>
                <!--input name="invdate" id="invdate" type="text" class="text" value="<?=($membership['invdate'])?date('d-m-Y',$membership['invdate']):''?>" /-->
            </td>
        </tr>
        <tr>
            <td class="aright"><?=t('Номер')?></td>
            <td><input name="invnumber" type="text" class="text" value="<?=($membership['invnumber'])?$membership['invnumber']:''?>" /></td>
        </tr>

        <tr>
            <td class="aright"></td>
            <td class="bold" style="padding-top:20px"><?=t('Номер партийного билета')?></td>
        </tr>
        <tr>
            <td class="aright"><?=t('Номер')?></td>
            <td><input name="kvnumber" type="text" class="text" value="<?=($membership['kvnumber'])?$membership['kvnumber']:''?>" /></td>
        </tr>
        <tr>
            <td class="aright"></td>
            <td><input name="kvmake" type="checkbox" <?=($membership['kvmake'])?'checked="checked"':''?> value="1" />  <?=t('Изготовление')?></td>
        </tr>
        <tr>
            <td class="aright"></td>
            <td><input name="kvgive" type="checkbox" <?=($membership['kvgive'])?'checked="checked"':''?> value="1" />  <?=t('Вручение')?></td>
        </tr>
        <tr>
            <td class="aright"><?=t('Комментарий')?></td>
            <td><input name="kvcomment" type="text" class="text" value="<?=($membership['kvcomment'])?htmlspecialchars(stripslashes($membership['kvcomment'])):''?>" /></td>
        </tr>

        
        
        
        
        <!--                    PARTY OFF                   -->
        
        <tr>
            <td class="aright"></td>
            <td class="bold" style="padding-top:20px"><?=t('Выход из МПУ')?></td>
        </tr>
        <tr>
            <td class="aright"><?=t('Порядок')?></td>
            <td>
                <?
                    $off_types = membership_helper::get_party_off_types();
										unset($off_types[100]);
                    echo tag_helper::select('off_type',$off_types,array('value'=>$membership['remove_type']));
                ?>
            </td>
        </tr>
        <tr class="hidden" id="tr_reason">
            <td class="aright"><?=t('Причина')?></td>
            <td>
                <select id="removewhy" name="removewhy">
                    <option value="">&mdash;</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="aright"><?=t('Дата')?></td>
            <td>
                <?=user_helper::datefields('removedate', $membership['removedate'])?>
                <!--input name="removedate" id="removedate" type="text" class="text" value="<?=($membership['removedate'])?date('d-m-Y',$membership['removedate']):''?>" /-->
            </td>
        </tr>
				<? if(intval(db_key::i()->get('schanger'.session::get_user_id())) OR in_array(session::get_user_id(),array(2,5,3789,5968,11752))){ ?>
					<? load::model("user/zayava_termination"); ?>
					<? $statement_data = user_zayava_termination_peer::instance()->get_statement_by_user_id($user_desktop['user_id']); ?>
					<tr class="hidden" id="statement-original">
						<td class="aright">Оригінал заяви</td>
						<td>
							<input
								type="checkbox"
								id="statement-confirmation"
								name="statement-confirmation"
								<? if($statement_data["confirmation"]){ ?>
									checked
								<? } ?>
							/>
						</td>
					</tr>
					<tr id="block-removenumber" class="hide">
							<td class="aright"><?=t('Номер')?></td>
							<td><input name="removenumber" type="text" class="text" value="<?=($membership['removenumber'])?$membership['removenumber']:''?>" /></td>
					</tr>
					<tr>
									<td class="aright"><?=t('Статус')?></td>
									<td>
											<? $types=user_auth_peer::get_statuses();
												 unset($types[15]);
												 unset($types[20]);
											?>
											<?=tag_helper::select('ustatus', $types, array('value'=>5))?>
									</td>
					</tr>
        <? } ?>
        
        
        
        
        
        
        
        
        
        
        <tr>
                <td></td>
                <td style="padding-top:20px">
                        <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                        <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                        <?=tag_helper::wait_panel('membership_wait') ?>
                        <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        <div class="error hidden mr10 mt10"><?=t('Вы заполнили не все поля. Часть информации может быть не сохранена')?></div>
                </td>
        </tr>
    </table>
</form>
<script>
    $(function(){
        $('select[name="off_type"]').change(function() {
					if($(this).val() == 1){
						$("#statement-original").show();
					} else {
						$("#statement-original").hide();
					}
					
					if($(this).val() == 3){
						$("#block-removenumber").show().val("");
					} else {
						$("#block-removenumber").hide();
					}
            $.ajax({
               type: 'post',
               url: '/profile/ajax',
               data: { 
                   id: $(this).val(),
                   operation: 'get_party_off_reason'
               },
               success: function(data) {
                   
                   resp = eval("("+data+")");
                   if(resp.success==1) {
                       
                       if(!resp.data) {
                            $('#removewhy').html('');
                            $('#tr_reason').hide();
                        }
                        else {
                           html = '';
                           for(i in resp.data) {
                               html+='<option value="'+(i)+'"';
                               if((i)=='<?=$membership['removewhy']?>') html+=' selected';
                               html+='>'+resp.data[i]+'</option>';
                           }
                           $('#removewhy').html(html);
                           $('#tr_reason').show();
                        }
                   }
                   else alert(resp.reason);

               }
            });
        }).change();
    });
    
</script>