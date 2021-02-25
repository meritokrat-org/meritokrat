<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<title>Друк учасникiв подii</title>
<head>
<script type="text/javascript" src="/static/javascript/jquery/jquery-1.4.2.js"></script>
<style type="text/css">
BODY
{
    FONT-SIZE: 9pt;
    COLOR: black;
    FONT-FAMILY: Arial;
    BACKGROUND-COLOR: white
}
table
{
    border-collapse:collapse;
}
table td
{
    padding:5px;
    border:1px solid black;
}
.fl {
    float: left;
    clear: none;
}
.cb {
    float: none;
    clear: both;
}
#user_list2 {
    position: absolute;
    width: 180px;
}
.one_user_box {
    z-index: 100;
    background: #fff;
    position: relative;
    padding: 1px 10px;
    border: 1px solid #ccc;
    width: 180px;
}
.one_user_ava_box {}
.one_user_ava_box img {
    width: 32px;
    height: 40px;
}
.one_user_info_block {
    margin-left: 10px;
     width: 135px;
}
.one_user_name {
    line-height: 20px;

}
.one_user_city {
    line-height: 20px;
}
.user_selected {
    background-color: #dddddd;
}
</style>
</head>
<body>
    <div id="print" style="margin-bottom:10px;">
        <input type="hidden" name="user_id" id="user_id" value=""/>
        Додати учасника:&nbsp;
        <input type="text" id="search_users" />
        <div id="user_list2" class="cb" style="display: none;"></div>&nbsp;
        <a href="javascript:viod(0)" onclick="$('.del').remove();document.getElementById('print').style.display = 'none';print();">ДРУКУВАТИ</a>
    </div>
        <? foreach(events_members_peer::instance()->get_members($event['id'], request::get_int('print', 1), request::get_int('confirm', 0)) as $user) {
            $user_list_data=user_data_peer::instance()->get_item($user['user_id']);
            $user_list_auth=user_auth_peer::instance()->get_item($user['user_id']); ?>
            <table cellspacing="0" cellpadding="0" style="width:750px">
                <tr>
                    <td style="width:90px"><?=user_helper::photo($user['user_id'], 'r', array(),false)?></td>
                    <td><b style="font-size:20px"><?=user_helper::full_name($user['user_id'],false)?></b>, <?=user_helper::geo( $user['user_id'] )?><?=$user_list_data['location'] ? ' ,'.$user_list_data['location'] : ''?></td>
                    <td style="width:35%">
                        <?=user_auth_peer::get_status($user_list_auth['status'])?>
                        <br/>
                        <?=$user_list_data['mobile'] ? $user_list_data['mobile'] : ($user_list_data['home_phone'] ? $user_list_data['home_phone'] : ($user_list_data['work_phone'] ? $user_list_data['work_phone'] : ($user_list_data['phone'] ? $user_list_data['phone'] : '')))?>, <?=$user_list_auth['email']?>
                    </td>
                    <td style="width:10px;border:none" class="del"><a href="javascript:;" onclick="$(this).parent().parent().parent().remove()">x</a></td>
                </tr>
            </table>
        <? } ?>
<script>
    $(document).ready(function($){
        $('#user_id').change(function(){
            $.post('/ajax/eventaddmember',{'id':$(this).val(),'event':'<?=$event['id']?>','status':'<?=request::get_int('print')?>','confirm':'<?=request::get_int('confirm', 0)?>'},function(data){
                if(data)
                    $(data).insertAfter($('#print'));
            });
        });
    });
    $(function(){
          $cur_sel = -1;
          $data_count=0;
          $top =0;
          $excodes = new Array(9,13,16,18,17,20,27,38,40);
            $('#search_users').keyup(function(event) {
                   $post_data = $('#search_users').val();
                   if($.inArray(event.keyCode,$excodes)==-1) {
                        $cur_sel = -1;
                        if($('#search_users').val().length>2 || event.keyCode==8)
                            $.ajax({
                                        type: 'post',
                                        url: '/ajax/user_search',
                                        dataType: 'json',
                                        data: ({'fio': $post_data}),
                                        success: function($jsdata) {
                                                         $str = new String();
                                                         for($key in $jsdata) {
                                                                $str += '<div class="one_user_box fl" onMouseOver="mOver('+$key+')" onMouseOut="mOut('+$key+')" onClick="mClick('+$key+')" uid="'+$key+'" nid="'+$top+'">'+
                                                                             '<div class="one_user_ava_box fl" >'+
                                                                                 '<img src="'+$jsdata[$key][2]+'">'+
                                                                              '</div>'+
                                                                              '<div class="one_user_info_block fl">'+
                                                                                  '<div class="one_user_name bold fs11 cb" style="color: #660000;">'+$jsdata[$key][0]+'</div>'+
                                                                                  '<div class="one_user_city fs11 cb" style="color: #000000;">'+$jsdata[$key][1]+'</div>'+
                                                                              '</div>'+
                                                                        '</div>';
                                                                $('#user_list2').show();
                                                                $('#user_list2').html($str);
        //                                                        $h += $('div[nid="'+$top+'"]').height();
                                                                $top++;
                                                            }
                                                       $data_count = $top;
                                                       $top=0;
                                                },
                                        error: function() {
                                             $('#user_list2').hide();

                                        }
                                 })
                   }
                   window.f=true;
                   if((event.keyCode==40) || (event.keyCode==9)) {
                       if($cur_sel<$data_count-1)
                                 $cur_sel++;
                       if($cur_sel<$data_count) {
                                       $('div[nid="'+($cur_sel-1)+'"]').removeClass('user_selected');
                                       $('div[nid="'+($cur_sel)+'"]').addClass('user_selected');
                       }
                   }
                   if (event.keyCode==38) {
                          if($cur_sel>0) {
                              if($cur_sel<$data_count) {
                                    $('div[nid="'+($cur_sel)+'"]').removeClass('user_selected');
                                    $('div[nid="'+($cur_sel-1)+'"]').addClass('user_selected');
                                    if($cur_sel>0)
                                        $cur_sel--;
                               }
                           }
                   }
                   if(event.keyCode==13) {
                       $("#user_id").attr('value',$('#user_list2').find('div.user_selected').attr('uid'));
                       $("#search_users").val($('#user_list2').find('div.user_selected').find('div.one_user_name').html());
                       $('#user_list2').hide();
$('#user_id').trigger('change');
                       $data_count = 0;
                       $cur_sel = 0;
                       $top =0;
                       return false;
                   }

            });
             $('#search_users').blur(function(){
                 $('#user_list2').hide();
                 $data_count = 0;
                   $cur_sel = 0;
                   $top =0;
                   $('#search_users').attr('uid', '');
             });
        });

function mOver($key) {
    $('#search_users').unbind('blur');
    $nid = $('div[uid="'+$key+'"]').attr('nid');
    $('#user_list2').find('div').removeClass('user_selected');
    $('div[nid="'+$nid+'"]').addClass('user_selected');
}

function mOut($key) {
    $('#search_users').bind('blur',function(){
         $('#user_list2').hide();
         $data_count = 0;
           $cur_sel = 0;
           $top =0;
     });
    $('#user_list2').find('div').removeClass('user_selected');
}

function mClick($key) {
   $("#user_id").attr('value',$('#user_list2').find('div.user_selected').attr('uid'));
   $("#search_users").attr('uid', $('#user_list2').find('div.user_selected').attr('uid'));
   $("#search_users").val($('#user_list2').find('div.user_selected').find('div.one_user_name').html());
   $('#user_list2').hide();
$('#user_id').trigger('change');
   $data_count = 0;
   $cur_sel = 0;
   $top =0;
}
</script>
</body>
</html>