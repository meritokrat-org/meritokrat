var blogsController = new function()
{
	this.indexAction = function ()
        {
//            $(function(){
//              $cur_sel = -1;
//              $data_count=0;
//              $top =0;
//              $excodes = new Array(9,13,16,18,17,20,27,38,40);
//                $('#search_users').click(function(event) {
//                       $post_data = $('#search_users').val();
//                       if($.inArray(event.keyCode,$excodes)==-1) {
//                            $cur_sel = -1;
//                            if($('#search_users').val().length>2 || event.keyCode==8)
//                                $.ajax({
//                                            type: 'post',
//                                            url: '/ajax/user_search',
//                                            dataType: 'json',
//                                            data: ({'fio': $post_data}),
//                                            success: function($jsdata) {
//                                                             $str = new String();
//                                                             for($key in $jsdata) {
//                                                                    $str += '<div class="one_user_box fl" onMouseOver="mOver('+$key+')" onMouseOut="mOut('+$key+')" onClick="mClick('+$key+')" uid="'+$key+'" nid="'+$top+'">'+
//                                                                                 '<div class="one_user_ava_box fl" >'+
//                                                                                     '<img src="'+$jsdata[$key][2]+'">'+
//                                                                                  '</div>'+
//                                                                                  '<div class="one_user_info_block fl">'+
//                                                                                      '<div class="one_user_name bold fs11 cb" style="color: black;">'+$jsdata[$key][0]+'</div>'+
//                                                                                      '<div class="one_user_city fs11 cb" style="color: #000000;">'+$jsdata[$key][1]+'</div>'+
//                                                                                  '</div>'+
//                                                                            '</div>';
//                                                                    $('#user_list2').show();
//                                                                    $('#user_list2').html($str);
//            //                                                        $h += $('div[nid="'+$top+'"]').height();
//                                                                    $top++;
//                                                                }
//                                                           $data_count = $top;
//                                                           $top=0;
//                                                    },
//                                            error: function() {
//                                                 $('#user_list2').hide();
//
//                                            }
//                                     })
//                       }
//                       window.f=true;
//                       if((event.keyCode==40) || (event.keyCode==9)) {
//                           if($cur_sel<$data_count-1)
//                                     $cur_sel++;
//                           if($cur_sel<$data_count) {
//                                           $('div[nid="'+($cur_sel-1)+'"]').removeClass('user_selected');
//                                           $('div[nid="'+($cur_sel)+'"]').addClass('user_selected');
//                           }
//                       }
//                       if (event.keyCode==38) {
//                              if($cur_sel>0) {
//                                  if($cur_sel<$data_count) {
//                                        $('div[nid="'+($cur_sel)+'"]').removeClass('user_selected');
//                                        $('div[nid="'+($cur_sel-1)+'"]').addClass('user_selected');
//                                        if($cur_sel>0)
//                                            $cur_sel--;
//                                   }
//                               }
//                       }
//                       if(event.keyCode==13) {
//                           $("#user_id").val($('#user_list2').find('div.user_selected').attr('uid'));
//                           $("#search_users").val($('#user_list2').find('div.user_selected').find('div.one_user_name').html());
//                           $('#user_list2').hide();
//
//                           $data_count = 0;
//                           $cur_sel = 0;
//                           $top =0;
//                           return false;
//                       }
//
//                });
//                 $('#search_users').blur(function(){
//                     $('#user_list2').hide();
//                     $data_count = 0;
//                       $cur_sel = 0;
//                       $top =0;
//                       $('#search_users').attr('uid')='';
//                 });
            //});
            
            
            $('#fastsrch input').each(function(){
                if(this.value == $(this).attr('title'))
                    $(this).css({'color':'grey'});
            });

            $('#fastsrch input').focus(function() {
               if (this.value == $(this).attr('title')){
                   this.value = '';
               }

               if ($(this).attr('rel')=='') { $(this).attr('rel', $(this).attr('name'));}
               if(this.value != $(this).attr('title') && this.value != '' ){
                       $(this).css({'color':'black'});
               }
            });

            $('#fastsrch input').blur(function() {
                if (this.value == '' || this.value == ' ' ){
                    this.value =$(this).attr('title');
                    if ($(this).attr('rel')=='') { $(this).attr('rel', $(this).attr('name'));}
                    $(this).removeAttr('name');
                    $(this).css({'color':'grey'});
                }else {
                    $(this).attr('name', $(this).attr('rel'));
                    $(this).css({'color':'black'});
                }
            });

        };

        this.editAction = function ()
        {
            $('.tab_menu').click(function() {
			$('.tab_menu').removeClass('selected');
			$(this).addClass('selected');
			$(this).blur();
			$('.form').hide();
			$('#' + $(this).attr('rel') + '_form').show();
		});

                $('#typechanger').change(function(){
                    if($(this).val()==9){
                        $('.programholder').show();
                    }else{
                        $('.programholder').hide();
                    }
                });

                var uploadForm = new Form(
			'upload_form',
			{
				validators:
				{
				},
				success: function( response )
				{
                                        if(response==0)alert('Файл занадто великий');
                                        else if(response==1)alert('Можна завантажувати лише малюнки');
                                        tinyMCE.execCommand('mceInsertContent',false,response);
				},
				format: 'raw'
			}
		);

                //$('#mention').autocomplete({cache: false, minchars: 2, noresults: ' - ', ajax_get: function( key, cont ) { $.post(
                //        '/search/get_users',
                //        {key: key},
                //        function( r ) {
                //                var res = [];
                //                for( var i = 0; i < r.length; i ++ ) res.push({ id: r[i].user_id , value: r[i]['first_name'] + ' ' + r[i]['last_name'] , info: r[i]['details']});
                //                cont(res);
                //        },
                //        'json');
                //}, callback: function( data ) {
                //        $('#mention').val('');
                //        blogsController.addMentioned(data.id, data.value);
                //}});

                //for ( var i = 0; i < this.mentioned.length; i++ )
                //{
                //       this.addMentioned( this.mentioned[i]['id'], this.mentioned[i]['full_name'] );
                //}
        };

	this.addMentioned = function( id, fullName )
	{
		$('#mentions').append('<span><input type="hidden" name="mentioned[]" value="' + id + '"><a target="_blank" href="/profile-' + id + '" class="mr5">' + fullName + '</a><a class="maroon" href="javascript:;" onclick="$(this).parent().remove();">x</a></span>');
	};

	this.vote = function( positive )
	{
		$('#vote_pane').fadeOut(100);
           if(positive)
		$.post(
			'/blogs/vote',
			{id: blogsController.postId, positive: positive ? 1 : 0},
			function () {
				var voteDisplay = $('#vote_value').children( positive ? '.green' : '.red' );
				voteDisplay.html( parseInt(voteDisplay.text()) + 1 );
			}
		);
           else
               {
                   $('input[name="neg_msg"]').val(1);
                   $('#cancel_v').show('slow');
                   $('.form_bg:first').css('background-color','#ffeeee');
                   $('.column_head_small:first').css('background','url("static/images/common/down.gif") no-repeat 600px 5px scroll');
                   $('.column_head_small:first').css('line-height','33px');
               }
	};

	this.rateComment = function( object, id, positive )
	{
		$(object).parent().fadeOut(150);

		var rateEl = $(object).parent().parent().children('span.bold');
		var newRate = parseInt(rateEl.html()) + (positive ? 1 : -1);

		if ( newRate > 0 )
		{
			newRate = '+' + newRate;
			rateEl.css({color: 'green'});
		}
		else
		{
			rateEl.css({color: 'red'});
		}

		rateEl.html( newRate );

		$.post(
			'/blogs/comment_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

	this.postAction = function ()
        {
                    var form = new Form(
                            'comment_form',
                            {
                                    validators:
                                    {
                                            text: [validatorRequired]
                                    },
                                    success: function( response )
                                    {
                                            $('#no_comments').hide();
                                            $('#comments').append( response );
                                            form.getForm().hide( 150 );
                                            if($('input[name="neg_msg"]').val()==1) {
                                                var voteDisplay = $('#vote_value').children('.red' );
                                                voteDisplay.html( parseInt(voteDisplay.text()) + 1 );
                                            }
                                    },
                                    format: 'raw'
                            }
                    );

                    var replyForm = new Form(
                            'comment_reply_form',
                            {
                                    validators:
                                    {
                                            text: [validatorRequired]
                                    },
                                    success: function( response )
                                    {
                                            //data = eval("("+response+")");
                                            //if(!data.error) {
                                                $('#no_comments').hide();
                                                $('#child_comments_' + replyForm.get('parent_id').val()).append( response );
                                                replyForm.getForm().hide();
//                                            }
//                                            else {
//                                                alert(response.error);
//                                                replyForm.getForm().hide();
//                                            } 
                                                
                                    },
                                    format: 'raw'
                            }
                    );
    //                var replyQuoteForm = new Form(
    //			'comment_reply_quote_form',
    //			{
    //                                validators:
    //				{
    //					text: [validatorRequired]
    //				},
    //				success: function( response )
    //				{
    //					$('#no_comments').hide();
    //					$('#comments').append( response );
    //					replyQuoteForm.getForm().hide();
    //				},
    //				format: 'raw'
    //			}
    //		);

                    var updateForm = new Form(
                            'comment_update_form',
                            {
                                    validators:
                                    {
                                            text: [validatorRequired]
                                    },
                                    success: function( response )
                                    {
                                            response = eval("("+response+")");
                                            if(!response.error)
                                                Application.doComUpd(updateForm.getForm(),response.text);
                                            else {
                                                alert(response.error);
                                                Application.cancelComUpd();
                                            }
                                    },
                                    format: 'raw'
                            }
                    );

                    $('.comment_reply').bind('click', function(){
                            replyForm.get('text').val('');
                            $('#comment_reply_form').appendTo($(this).parent());
                            $('#comment_reply_form').show();
                            replyForm.get('parent_id').val( $(this).attr('rel') );
                            replyForm.get('text').focus();
                    });
                    $('.comment_quote_reply').bind('click', function(){

                            $username = $('#comment'+$(this).attr('rel')).find('.combody').prev().find('a').text();
                            $comment_date = $('#comment'+$(this).attr('rel')).find('.combody').prev().find('span').html();
                            $str = $('#comment'+$(this).attr('rel')).find('.combody').html();
                            $str = $str.replace(/^\s*([\S\s]*?)\s*$/, '$1');

                            form.get('text').val('<QUOTE><BOLD>'+$username+'</BOLD> '+'<DATE>'+$comment_date+'</DATE>'+'\n'+'<MSG>'+$str+'</MSG>'+'</QUOTE>\n');
                            $('#comment_reply_quote_form').appendTo($(this).parent());
                            $('#comment_reply_quote_form').show();
    //			replyQuoteForm.get('parent_id').val( $(this).attr('rel') );
                            form.get('text').focus();
                    });
        };


        this.changeType = function( obj, id, type )
	{
		$.post(
			'/blogs/change_type',
			{id: id, type: type}
		);
                $(obj).hide();
	};
};
//function mOver($key) {
//    $('#search_users').unbind('blur');
//    $nid = $('div[uid="'+$key+'"]').attr('nid');
//    $('#user_list2').find('div').removeClass('user_selected');
//    $('div[nid="'+$nid+'"]').addClass('user_selected');
//}
//
//function mOut($key) {
//    $('#search_users').bind('blur',function(){
//         $('#user_list2').hide();
//         $data_count = 0;
//           $cur_sel = 0;
//           $top =0;
//     });
//    $('#user_list2').find('div').removeClass('user_selected');
//}
//function mClick(key) {
//	console.log(key)
//   $("#user_id").val(key);
//   $("#search_users").attr('uid', key);
//   $("#search_users").val($('#user_list2').find('div.user_selected').find('div.one_user_name').html()); // 
//   $('#user_list2').hide();
//
//   $data_count = 0;
//   $cur_sel = 0;
//   $top =0;
//}
$(function(){
    $('#cancel_v').click(function(){
        $('input[name="neg_msg"]').val(0);
        $('.form_bg:first').css('background-color','#F7F7F7');
        $('.column_head_small:first').css('background','url("static/images/common/box_head_tiny.gif") no-repeat 0 0 scroll');
        $('.column_head_small:first').css('line-height','20px');
        $('#vote_pane').fadeIn(100);
        $(this).hide();
    });
});