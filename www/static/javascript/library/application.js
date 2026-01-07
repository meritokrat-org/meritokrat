var Application = new function () {
	this.init = function () {
		var controllerName = context.module + 'Controller';
		var actionHandler = context.action + 'Action';

		eval(
			"if ( typeof(" + controllerName + ") != 'undefined' ) {" +
			" if ( typeof(" + controllerName + "." + actionHandler + ") != 'undefined' ) { " +
			"" + controllerName + "." + actionHandler + "();" +
			"}" +
			"}"
		);
		Application.initAutocomplete();
		Application.initSignInForm();
		Application.initRecoverForm();
	};

	this.initSignInForm = function () {
		var form = new Form(
			'signin_form',
			{
				validators: {
					'email': [validatorRequired, validatorEmail],
					'password': [validatorRequired]
				},
				success: function (response) {
					if (response.referer) {
						document.location = response.referer;
					}
					else {
						document.location.reload();
					}
				}
			}
		);

		form.get('email').focus();

	};
	this.initRecoverForm = function () {

		var recoverForm = new Form(
			'recover_form',
			{
				validators: {
					'email_recover': [validatorRequired, validatorEmail]
				},
				success: function () {
					$('#recover_form').html('<div class="success" style="color: #ccc; background-color: #f0f0f0;">Пароль надіслано на e-mail</div>').fadeOut(3000, function () {
						$('#signin_form').show();
						$('#floating_hint').hide();
					});


				},
				error_position: 'top'
			}
		);
	}

	this.goToPage = function (q, pageId) {
		$.get('/messages/share_user', {q: q, page: pageId},
			function (data) {
				$('#result').html(data);
				for (var i in Application.invitedFriends) {
					if (!Application.invitedFriends.hasOwnProperty(i)) continue;
					$('#result').find('#friend_' + Application.invitedFriends[i]).addClass('selected');
				}
				$('#user_pager').html('');
			});
	}

	this.goToSrchPage = function (q, pageId) {
		$.get('/messages/share_user', {q: q, page: pageId},
			function (data) {
				$('#search').html(data);
				for (var i in Application.invitedFriends) {
					if (!Application.invitedFriends.hasOwnProperty(i)) continue;
					$('#result').find('#friend_' + Application.invitedFriends[i]).addClass('selected');
				}
			});
	}

	this.goToPpoPage = function (q, pageId, ppoId, more_select) {
		$.get('/ppo/share_user', {q: q, page: pageId, ppo_id: ppoId, more_select: more_select},
			function (data) {
				$('#search').html(data);
				for (var i in Application.invitedFriends) {
					if (!Application.invitedFriends.hasOwnProperty(i)) continue;
					$('#result').find('#friend_' + Application.invitedFriends[i]).addClass('selected');
				}
			});
	}

	this.goToTeamPage = function (q, pageId, teamId, more_select) {
		$.get('/team/share_user', {q: q, page: pageId, team_id: teamId, more_select: more_select},
			function (data) {
				$('#search').html(data);
				for (var i in Application.invitedFriends) {
					if (!Application.invitedFriends.hasOwnProperty(i)) continue;
					$('#result').find('#friend_' + Application.invitedFriends[i]).addClass('selected');
				}
			});
	}

	this.changePage = function (q, pageId, url) {
		$.get(url, {q: q, page: pageId}, function (data) {
			$('#invited_selector').html(data);
		});
	}

	this.leaderJoin = function (userId) {
		Popup.show();
		$.get('/profile/join?id=' + userId, function (response) {
			Popup.setHtml(response);
			Popup.position();
		}, 'html');
	};

	this.leaderChange = function (userId) {
		$.get('/profile/leader?id=' + userId, function (response) {
			Popup.setHtml(response);
			setTimeout(function () {
				Popup.close(500);
			}, 1000);
			$('#menu_join').hide();
		}, 'raw');
	};

	this.addToFriends = function (userId) {
		Popup.show();
		$.get('/friends/add?id=' + userId, function (response) {
			Popup.setHtml(response);
			Popup.position();
		}, 'raw');
	};

	this.makeFriends = function (userId) {
		$.get('/friends/make?id=' + userId, function (response) {
			Popup.setHtml(response);
			setTimeout(function () {
				Popup.close(500);
			}, 3000);
			$('#menu_add_friends').hide();
		}, 'raw');
	};

	this.addToBlacklist = function (userId) {
		Popup.show();
		$.get('/friends/blacklist?id=' + userId, function (response) {
			Popup.setHtml(response);
			Popup.position();
		}, 'raw');
	};

	this.doBlacklist = function (userId) {
		$.get('/friends/ban?id=' + userId, function (response) {
			Popup.setHtml(response);
			setTimeout(function () {
				Popup.close(500);
			}, 1000);
			$('#menu_blacklist').hide();
		}, 'raw');
	};

	this.shareItem = function (type, id) {
		Popup.show();
		$.post(
			'/messages/share',
			{id: id, type: type},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.inviteItem = function (type, typ, id) {
		Popup.show();
		$.post(
			'/invites/invite',
			{'id': id, 'typ': typ, 'type': type},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.ShareProfile = function () {
		Popup.show();
		$.post(
			'/profile/share_profile',
			{},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.sendMessages = function (attrs) {
		Popup.show();
		if (typeof attrs == 'undefined')
			attrs = {};
		$.post(
			'/messages/groups_mailer',
			attrs,
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.showList = function (id) {
		Popup.show();
		$.post(
			'/lists/show_users',
			{'id': id},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	}

	this.showLists = function (id) {
		Popup.show();
		$.post(
			'/lists/show_lists',
			{'id': id},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	}

	this.getUsersList = function (id) {
		Popup.show();
		$.post(
			'/projects/get_list',
			{
				project_id: id
			},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.groupInvite = function () {
		Popup.show();
		$.post(
			'/invites/group_invite',
			{},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.showInternalList = function () {
		Popup.show();
		$.post(
			'/admin/internallisti',
			{},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	}

	this.showProjectsUsers = function (id, project_id, multi, moreselect) {

		Popup.show();
		$.post(
			'/reform/showusers',
			{project_id: project_id, multi: multi, more_select: moreselect},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
				$('input#act').val(id);
			}
		);

	}

	this.showUsers = function (id, ppo_id, multi, moreselect) {
		this.showUsersPopup({id, ppo_id, multi, moreselect});
	}

	this.showUsersPopup = function ({id, ...rest}) {
		Popup.show();
		$.post(
			'/ppo/showusers',
			{...rest},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
				$('input#act').val(id);
			}
		);
	}

	this.showTeamUsers = function (id, team_id, multi, moreselect) {

		Popup.show();
		$.post(
			'/team/showusers',
			{team_id: team_id, multi: multi, more_select: moreselect},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
				$('input#act').val(id);
			}
		);

	}

	this.groupsShowUsers = function (id, group_id, multi, moreselect) {

		Popup.show();
		$.post(
			'/groups/showusers',
			{group_id: group_id, multi: multi, more_select: moreselect},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
				$('input#act').val(id);
			}
		);

	}

	this.shareItemProcess = function () {
		if ($('.friend_check:checked').length == 0) {
			alert($('#share_form').attr('rel'));
			return false;
		}

		$.post('/messages/share', $('#share_form').serialize());

		Popup.setHtml('<div class="screen_message fs11">' + $('.popup_header').attr('rel') + '</div>');
		Popup.position();
		$('#popup_box').fadeOut(1500);

		return false;
	};

	this.invitedFriends = [];

	this.transferData = function (url) {
		if (this.invitedFriends.length == 0) {
			alert($('#share_form').attr('rel'));
			return false;
		}
		var data = $('#share_form').serialize();
		for (var i in this.invitedFriends) {
			if (!this.invitedFriends.hasOwnProperty(i)) continue;
			data += '&fr%5B%5D=' + this.invitedFriends[i];
		}
		$.post(url, data);
		this.invitedFriends = [];
		Popup.setHtml('<div class="screen_message fs11">' + $('.popup_header').attr('rel') + '</div>');
		Popup.position();
		$('#popup_box').fadeOut(2000);
		return false;
	}

	this.doInvite = function () {
		this.transferData('/invites/add');
		return false;
	};

	this.addToProject = function () {
		this.transferData('/reform/add_to_project');
		return false;
	};

	this.doGroupInvite = function () {
		this.transferData('/invites/add_group');
		return false;
	};

	this.doGroupJoin = function () {
		this.transferData('/invites/add_member');
		return false;
	};

	this.dotoGroup = function () {
		this.transferData('/groups/addmembers');
		return false;
	};

	this.dotoPpo = function () {
		this.transferData('/ppo/addmembers');
		return false;
	};

	this.dotoTeam = function () {
		this.transferData('/team/addmembers');
		return false;
	};

	this.dotoProjects = function () {
		this.transferData('/reform/addmembers');
		return false;
	};

	this.dotoEvent = function (confirm) {
		this.transferData('/events/confirm?multiple=1&confirm=' + confirm);
		return false;
	};

	this.doMessage = function () {
		this.transferData('/admin/internallisti');
		return false;
	};
	this.doRecommendation = function () {
		var data = $('#share_form').serialize();
		$.post('/help/send_message', data);
		Popup.setHtml('<div class="screen_message fs11">' + $('.popup_header').attr('rel') + '</div>');
		Popup.position();
		$('#popup_box').fadeOut(2000);
		return false;

	};

	this.friendSelect = function (id, do_select) {
		var counter = $('#friend_counter');
		var friends = $('#friends_checker').html();
		var friend_input = '<input type="hidden" id="friend_input_' + id + '" name="friends[]" value="' + id + '">';
		if (typeof do_select == 'undefined') {
			do_select = !$('#friend_' + id).hasClass('selected');
		}

		if (!do_select) {
			$('#friend_' + id).removeClass('selected');
			$('#friend_check_' + id).attr('checked', false);
			$('#friend_counter').attr('checked', false);
			if (parseInt(counter.html()) != '1') {
				var newCount = parseInt(counter.html()) - 1;
			}
			else {
				var newCount = 0;
			}
			$('#friend_input_' + id).remove();
		}
		else {
			$('#friend_' + id).addClass('selected');
			$('#friend_check_' + id).attr('checked', true);
			var newCount = parseInt(counter.html()) + 1;
			$('#friends_checker').html(friends + friend_input);
		}
		counter.html(newCount);
	};

	this.thisOneUserSelect = function (obj) {
		var friends = $('#friends_checker').html();
		var do_select = !$(obj).hasClass('selected');
		$('.item').removeClass('selected');
		if (!do_select) {
			$(obj).removeClass('selected');
			$(obj).prev('input').attr('checked', false);
			for (var i in this.invitedFriends) {
				if (!this.invitedFriends.hasOwnProperty(i)) continue;
				if ($(obj).attr('rel') == this.invitedFriends[i])
					this.invitedFriends.splice(i, 1);
			}
		}
		else {
			$(obj).addClass('selected');
			this.invitedFriends.push($(obj).attr('rel'));
			$(obj).prev('input').attr('checked', true);
		}
	};

	this.thisfriendSelect = function (obj) {
		var counter = $('#friend_counter');
		var friends = $('#friends_checker').html();
		var do_select = !$(obj).hasClass('selected');

		if (!do_select) {
			$(obj).removeClass('selected');
			$(obj).prev('input').attr('checked', false);
			for (var i in this.invitedFriends) {
				if (!this.invitedFriends.hasOwnProperty(i)) continue;
				if ($(obj).attr('rel') == this.invitedFriends[i])
					this.invitedFriends.splice(i, 1);
			}
			//$('#friend_counter').attr('checked', false);
			if (parseInt(counter.html()) != '1') {
				var newCount = parseInt(counter.html()) - 1;
			}
			else {
				var newCount = 0;
			}
		}
		else {
			$(obj).addClass('selected');
			this.invitedFriends.push($(obj).attr('rel'));
			$(obj).prev('input').attr('checked', true);
			var newCount = parseInt(counter.html()) + 1;
		}
		counter.html(newCount);
	};

	this.friendsToggle = function () {
		$('.friend').each(function () {
			Application.friendSelect($(this).attr('rel'), $('#select_all_friends').attr('checked'));
		});
		if ($('#select_all_friends').attr('checked')) {
			$('#friend_counter').html(12);
		}
	};

	this.bookmarkItem = function (type, id) {
		$('a[href$="#add_bookmark"].b' + type).hide();
		$('a[href$="#del_bookmark"].b' + type).fadeIn(500);
		$.post('/bookmarks/add', {type: type, id: id});

		return false;
	};

	this.unbookmarkItem = function (type, oid) {
		$('a[href$="#del_bookmark"].b' + type).hide();
		$('a[href$="#add_bookmark"].b' + type).fadeIn(500);
		$.post('/bookmarks/delete', {type: type, oid: oid});

		return false;
	};

	this.bookmarkThisItem = function (obj, type, id) {
		$(obj).hide().next('a').fadeIn(500);
		$.post('/bookmarks/add', {type: type, id: id});

		return false;
	};

	this.unbookmarkThisItem = function (obj, type, oid) {
		$(obj).hide().prev('a').fadeIn(500);
		$.post('/bookmarks/delete', {type: type, oid: oid});

		return false;
	};

	this.ShowHide = function (id) {
		if (!$("#" + id).is(":visible")) {
			$("#" + id).slideDown('slow');
			$("#" + id + "_off").hide();
			$("#" + id + "_on").show(100);
		}
		else {
			$("#" + id).slideUp('slow');
			$("#" + id + "_on").hide();
			$("#" + id + "_off").show(100);
		}
	};

	this.ShowNext = function (id) {
		$("#item" + id).hide('slide', {direction: 'left'}, 1000);
		$("#item" + (+id + 1)).show('slide', {direction: 'right'}, 1000);
	};

	this.ShowPrev = function (id) {
		$("#item" + id).hide('slide', {direction: 'right'}, 1000);
		$("#item" + (+id - 1)).show('slide', {direction: 'left'}, 1000);
	};

	this.initComUpd = function (id) {
		var why = prompt("Вкажiть причину редагування:", "");
		if (!why) {
			alert("Ви не можете редагувати без поважної причини");
			return false;
		}
		var obj = $("#comment" + id).find('div.combody');
		$('#comments').find('div.combody:hidden').show();
		obj.hide();

		$('#comment_update_form').find('#upd_id').val(id).end()
			.find('#why').val(why).end()
			.find('textarea').val(obj.text());
		$('#comment_update_form').insertAfter(obj).show();
	};
	this.initComUpdUser = function (id) {
		var obj = $("#comment" + id).find('div.combody');
		$('#comments').find('div.combody:hidden').show();
		obj.hide();
		$('#comment_update_form').find('#upd_id').val(id).end()
			.find('#why').val('').end()
			.find('textarea').val(obj.text());
		$('#comment_update_form').insertAfter(obj).show();
	};

	this.cancelComUpd = function () {
		$('#comment_update_form').find('textarea').val('').end().hide();
		$('#comments').find('div.combody:hidden').show();
	};

	this.doComUpd = function (obj, response) {
		$('#comment_update_form').find('textarea').val('').end().hide();
		obj.parent('div').find('div.combody:first').html(response).show();
	};

	this.delCom = function (id, url, type) {
		var why = '';
		if (type) {
			if (!confirm("Ви впевненi?"))return false;
		}
//                else{
//                    why = prompt("Вкажiть причину видалення:", "");
//                    if(!why){
//                        alert("Ви не можете видаляти без поважної причини");
//                        return false;
//                    }
//                }
		$.post(
			'/' + url + '?id=' + id,
			{'why': why},
			function (data) {
				resp = eval("(" + data + ")");
				if (resp.success)
					$('#comment' + id).hide();
				else
					alert(resp.error);
			}
		);
	};

	this.delItem = function (id, url) {
		var why = prompt("Вкажiть причину видалення:", "");
		if (!why) {
			alert("Ви не можете видаляти без поважної причини");
			return false;
		}
		window.location = 'http://' + window.location.hostname + '/' + url + '?id=' + id + '&why=' + why;
	};

	this.showInfo = function (alias) {
		Popup.showinfo();
		$.post(
			'/help/info',
			{'alias': alias},
			function (response) {
				Popup.setHtmlinfo(response);
				Popup.info_position();
			}
		);
	}

	this.checkLink = function (obj) {
		var check = $(obj).attr('rel').split(context.host);
		var httpcheck = $(obj).attr('rel').split('ttp://');
		var dotcheck = $(obj).attr('rel').split('.');
		if ((check.length < 2 && httpcheck.length > 1) || (check.length < 2 && dotcheck.length > 1)) {
			$('a').removeClass('outerlink');
			$(obj).addClass('outerlink');
			Popup.showinfo();
			$.post(
				'/help/info',
				{'alias': 'leaving_site'},
				function (response) {
					Popup.setHtmlinfo(response);
					Popup.info_position();
				}
			);
		} else {
			var subdomain = '';
			var filecheck = $(obj).attr('rel').split('f.' + context.host);
			var imagecheck = $(obj).attr('rel').split('image.' + context.host);
			var rucheck = $(obj).attr('rel').split('ru.' + context.host);
			var encheck = $(obj).attr('rel').split('en.' + context.host);
			if (filecheck.length > 1)
				subdomain = 'f.';
			if (imagecheck.length > 1)
				subdomain = 'image.';
			if (rucheck.length > 1)
				subdomain = 'ru.';
			if (encheck.length > 1)
				subdomain = 'en.';
			var link = $(obj).attr('rel').split('http://').join('').split('www.').join('').split(subdomain + context.host).join('').replace(/^\//, '');
			if (context.user_id == 5968)
				alert(link);
			window.open('http://' + subdomain + context.host + '/' + link);
		}
		return false;
	}

	this.doMembership = function () {
		Popup.show();
		$.post(
			'/reestr/members',
			{},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.getTeamList = function (id) {
		Popup.show();
		$.post(
			'/team/list',
			{
				id: id
			},
			function (response) {
				Popup.setHtml(response);
				Popup.position();
			}
		);
	};

	this.doMembers = function () {
		/*if(!$('#invnumber').val()){
		 alert("Усi поля обов'язковi для заповнення");
		 return false;
		 }*/
		if ($('#invnumber').val()) {
			if (!confirm('Ви впевненi, що хочете надати обраним учасникам статус члена МПУ?')) {
				return false;
			}
		}
		this.transferData('/reestr/addmembers');
		return false;
	};
	this.initAutocomplete = function () {
		$cur_sel = -1;
		$data_count = 0;
		$top = 0;
		$excodes = new Array(9, 13, 16, 18, 17, 20, 27, 38, 40);
		$('#search_users').keyup(function (event) {
			$post_data = $('#search_users').val();
			if ($.inArray(event.keyCode, $excodes) == -1) {
				$cur_sel = -1;
				if ($('#search_users').val().length > 2 || event.keyCode == 8)
					$.ajax({
						type: 'post',
						url: '/ajax/user_search',
						dataType: 'json',
						data: ({'fio': $post_data}),
						success: function ($jsdata) {
							$str = new String();
							for ($key in $jsdata) {
								$str += '<div class="one_user_box fl" onMouseOver="Application.mOver(' + $key + ')" onMouseOut="Application.mOut(' + $key + ')" onClick="Application.mClick(' + $key + ')" uid="' + $key + '" nid="' + $top + '">' +
									'<div class="one_user_ava_box fl" >' +
									'<img src="' + $jsdata[$key][2] + '">' +
									'</div>' +
									'<div class="one_user_info_block fl">' +
									'<div class="one_user_name bold fs11 cb" style="color: black;">' + $jsdata[$key][0] + '</div>' +
									'<div class="one_user_city fs11 cb" style="color: #000000;">' + $jsdata[$key][1] + '</div>' +
									'</div>' +
									'</div>';
								$('#user_list2').show();
								$('#user_list2').html($str);
								//                                                        $h += $('div[nid="'+$top+'"]').height();
								$top++;
							}
							$data_count = $top;
							$top = 0;
						},
						error: function () {
							$('#user_list2').hide();

						}
					})
			}
			window.f = true;
			if ((event.keyCode == 40) || (event.keyCode == 9)) {
				if ($cur_sel < $data_count - 1)
					$cur_sel++;
				if ($cur_sel < $data_count) {
					$('div[nid="' + ($cur_sel - 1) + '"]').removeClass('user_selected');
					$('div[nid="' + ($cur_sel) + '"]').addClass('user_selected');
				}
			}
			if (event.keyCode == 38) {
				if ($cur_sel > 0) {
					if ($cur_sel < $data_count) {
						$('div[nid="' + ($cur_sel) + '"]').removeClass('user_selected');
						$('div[nid="' + ($cur_sel - 1) + '"]').addClass('user_selected');
						if ($cur_sel > 0)
							$cur_sel--;
					}
				}
			}
			if (event.keyCode == 13) {
				$("#search_users").attr('uid', $('#user_list2').find('div.user_selected').attr('uid'));
				$("#search_users").val($('#user_list2').find('div.user_selected').find('div.one_user_name').html());
				$('#user_list2').hide();

				$data_count = 0;
				$cur_sel = 0;
				$top = 0;

			}
			if (event.keyCode == 27) {

				$('#user_list2').hide();
				$data_count = 0;
				$cur_sel = 0;
				$top = 0;
				//$('#search_users').attr('uid')='';
				//$('#search_users').val('');

			}

		});
//                 $('#search_users').blur(function(){
//                     $('#user_list2').hide();
//                       $data_count = 0;
//                       $cur_sel = 0;
//                       $top =0;
//                       $('#search_users').attr('uid')='';
//                 });
	}
	this.mOver = function ($key) {
		$('#search_users').unbind('blur');
		$nid = $('div[uid="' + $key + '"]').attr('nid');
		$('#user_list2').find('div').removeClass('user_selected');
		$('div[nid="' + $nid + '"]').addClass('user_selected');
	}
	this.mOut = function ($key) {
		$('#search_users').bind('blur', function () {
			$('#user_list2').hide();
			$data_count = 0;
			$cur_sel = 0;
			$top = 0;
		});
		$('#user_list2').find('div').removeClass('user_selected');
	}
	this.mClick = function ($key) {
		$("#search_users").attr('uid', $('#user_list2').find('div.user_selected').attr('uid'));
		$("#search_users").val($('#user_list2').find('div.user_selected').find('div.one_user_name').html());
		$('#user_list2').hide();

		$data_count = 0;
		$cur_sel = 0;
		$top = 0;
	}
}
$(document).ready(Application.init);