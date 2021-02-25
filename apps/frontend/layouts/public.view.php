<? header("Content-Type: text/html; charset=utf-8");
/*
$expires = 60*60*2;
header("Pragma: public"); */
//header("Cache-Control: no-cache, must-revalidate");
//header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<META NAME="webmoney.attestation.label" CONTENT="webmoney attestation label#2C88AF29-22DA-4A2E-9BD6-F4EA81BBCC02">
	<?=client_helper::get_title()?>
	<?=client_helper::get_meta()?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="google-site-verification" content="JWZVEPxZxl-58USuGQyCZEjTlE3yuJDLIAPlLdECDzY" />
	<link REL="SHORTCUT ICON" HREF="/favico.ico">
	<?=tag_helper::css('system.css') ?>
<style type="text/css">
html { height: 80% }
body { min-height: 100% }
* html body { height: 100% } /*Для IE */
</style>
<script type="text/javascript" src='/static/javascript/jquery/jquery-1.4.2.js'></script>
<script type="text/javascript" src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
<script type="text/javascript" src='/static/javascript/library/plugins/cookie.js'></script>

</head>
<body style="background-color: #8C0C0D;background: transparent url(/static/images/common/bg_enter.jpg) repeat scroll 0% 0%;">
<div style="height: 405px;">
<? /* 	<!--div class="head_pane" style="background: transparent url(/static/images/common/bg-header.jpg) repeat scroll 0% 0%; height: 107px;">
		<div class="root_container">
			<!--h1><a href="/"><?=t('Внутренняя сеть')?></a></h1-->

			<!--div class="right mt5 fs11 ml10">
				<form method="get" action="/search">
					<input name="keyword" type="text" class="text" onclick="this.value='';" value="<?=t('поиск')?>..." style="width: 100px;">
					<input type="submit" class="button" value=" <?=t('Найти')?> &raquo; ">
				</form>
			</div-->
 <div style="background: transparent url(/static/images/logos/logo2.png) repeat scroll 0% 0%; height: 25px; width: 271px; margin-top: 40px; margin-left: 110px; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; position: absolute;" class="left"></div>
<!--div style="height: 128px; width: 128px; margin-top: 18px; margin-left: 40px; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; position: absolute;" class="left"><a href="/"><img src="/static/images/logos/big-M.jpg" /></a></div-->
<!--div class="left" style="height:37px; width:382px; margin-top:48px; margin-left:180px; background: url(/static/images/logos/mery.jpg); position:absolute;"></div-->
<div class="left" style="font-size: 13px; color: rgb(102, 0, 0); font-weight: bold; font-family: tahoma; height: 17px; width: 322px; margin-top: 50px; margin-left: 747px; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; position: absolute;"><?=t('Меритократы всей Украины, объединяемся!')?></div>
<div class="menu" style="float:left; margin-top: 77px; margin-left: 110px;"><? include 'partials/menu.php'; ?></div>
<div class="right" style="color: gray; margin-top: 107px; margin-right: -42px; font-size: 11px;"><?=t('Тестовая версия')?></div>
 */ ?>		
<div style="margin-top:140px;" class="acenter"><img alt="logo" src="/static/images/logos/enter_logo_v.png"/>
    <div class="acenter mt10" style="margin-bottom: -6px;">
    <center>
    <table style="width: 280px;margin: 0">
        <tr>
            <td style="padding:2px 0">
							<a href="/sign/up"><img src="/static/images/common/button_b<?=(session::get('language')=='ru')?'_ru':''?>.png" alt="<?=t('Зарегистрироваться')?>" /></a>
            </td>
            <td class="aright">
		<div id="fb" class="fb-login-button" style="float: left; margin-right: 5px"
								     data-max-rows="1" data-size="icon"
								     data-scope="user_birthday, user_hometown, user_about_me, email, public_profile"
								     onlogin="checkLoginState()" data-show-faces="false"
								     data-auto-logout-link="false"></div>

                <a rel="nofollow" href="/sign/lang?code=ua" class="dib icoua<?= (session::get('language') == 'ua') ? 'a' : 'u' ?>"></a>
                <a rel="nofollow" href="/sign/lang?code=ru" class="dib icoru<?= (session::get('language') == 'ru') ? 'a' : 'u' ?>"></a>
                <? /*
                <a rel="nofollow" href="/sign/lang?code=en">
                    <img src="/static/images/icons/en<?= (session::get('language') == 'en') ? 'a' : 'u' ?>.png" alt="en">
                </a>
                 *
                 */?>
            </td>
        </tr>
    </table>
    </center>
    </div>
</div>
<div class="clear"></div>
    <? include $controller->get_template_path() ?>
	<div class="clear"></div>

<? /*
<div class="top_line_2 fs11" style="background: transparent url(/static/images/common/bg-footer.png) repeat scroll 0% 0%; height: 178px; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">
<div style="background: transparent url(/static/images/common/footer.png) no-repeat scroll center center; height: 176px; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;" class="top_line_2 fs11">
	<div class="root_container mt10 footer"><? include dirname(__FILE__) . '/_footer.php' ?><br />&nbsp;</div>
</div>

 */ include '_counter.php' ?>

</div>					
<div id="connectAccFB" class="hide">
	<h1>
		<?= t("Пользователь с таким email'ом уже существует") ?>.<br>
		<?= t("Если это Ваш профиль, то Вы можете привязать") ?><br>
		<?= t("аккаунт FB к аккаунту в сети Меритократ") ?>
	</h1>

	<hr class="mt10 mb10">

	<div
		style="width: 100px; height: 150px; background-position: center; background-repeat: no-repeat; background-size: cover; float: left; margin-left: 46px;"
		id="old_photo"></div>
	<div
		style="width: 64px; height: 150px; background-position: center; background-repeat: no-repeat; background-size: contain; float: left; margin: 0 10px; background-image: url('/static/images/arrow.png');"></div>
	<div
		style="width: 100px; height: 150px; background-position: center; background-repeat: no-repeat; background-size: cover; float: left"
		id="new_photo"></div>

	<div style="clear: both"></div>

	<label style="font-weight: bold">
		<input style="margin-top: 10px;" type="checkbox" id="update_photo"/>
		- <?= t("Обновить фото") ?>
	</label>

	<div id="connect"
	     style="width: 130px; background: url('/static/images/common/box_head.gif') repeat-x; border-radius: 4px; cursor: pointer; margin: 10px auto 0 auto">
		<div class="p5 acenter"
		     style="font-family: inherit; font-weight: bold; color: #FFCC66; text-transform: uppercase"><?= mb_strtoupper(t('Привязать')) ?></div>
	</div>
	<a id="no_connect" style="cursor: pointer; color: #888"><?= t("Не привязывать") ?></a>
</div>
					<script>

						var clientId = '736035328580-2skdoekovpqgaogaeqt3md47ep1kmlpo.apps.googleusercontent.com';

						if (window.location.host == "meritokrat.dev2") {
							var apiKey = 'AIzaSyAC7AVCKqY1vc30Ltmpuud0vH8Fjq-wLBQ';
							var appId = '911856062208203';

						} else {
							var apiKey = 'AIzaSyCINJKE_RBNo1GOJUFWHOFrLCbyrxSMLik';
							var appId = '911506055576537';
						}

						var scopes = 'https://www.googleapis.com/auth/userinfo.profile';

						$(document).ready(function () {
							$("#logOutButton").click(function () {
								gapi.auth.signOut();
								FB.logout();
								window.setTimeout(function () {
									document.location.href = "/sign/out";
								}, 2);
							});
						});

						function checkLoginState() {
							FB.getLoginStatus(function (response) {
								statusChangeCallback(response);
							});
						}

						function statusChangeCallback(response) {
							if (response.status === 'connected') {
								if ($.cookie(md5("auth")) == null)
									signIn("reg");
							}
						}

						window.fbAsyncInit = function () {
							FB.init({
								appId: appId,
								cookie: true,
								xfbml: true,
								version: 'v2.3'
							});

							FB.getLoginStatus(function (response) {
								statusChangeCallback(response);
							});
						};


						function signIn(act) {
							if(act == "in")
								document.location.href = document.location.href;

							FB.api('/me', function (user) {
								$.post("/sign/fb", {
									valid: true,
									user: user
								}, function (response) {
									if (response.status == "registration")
										if(response.user.hometown)
											FB.api('/' + response.user.hometown["id"], function (registration) {
												$.post("/sign/fb", {
													valid: true,
													location: registration["location"]
												}, function () {
													signIn("in");
												}, "json");
											});
										else
											$.post("/sign/fb", {
												valid: true,
												location: "none"
											}, function () {
												signIn("in");
											}, "json");
									else if (response.status == "connect") {
										img = "https://image.meritokrat.org/r/user/" + response.user_exist.user_id + response.user_exist.photo_salt;
										upphoto = false;

										Popup.show();
										$(".frame").css("padding", "10px");

										$("#old_photo").css({
											backgroundImage: 'url(' + img + ')'
										});

										$("#new_photo").css({
											backgroundImage: 'url(' + response.user.image + ')'
										});

										Popup.setHtml($("#connectAccGplus").html());
										Popup.position();

										$("#no_connect").live("click", function () {
											Popup.close();
										});

										$("#update_photo").live('change', function () {
											if ($(this).is(':checked')) {
												upphoto = true;
											} else {
												upphoto = false;
											}
										});

										$("#connect").live("click", function () {
											$.post("/sign/fb", {
												valid: true,
												connect: true,
												upphoto: upphoto,
												contacts: response.user_exist.contacts ? response.user_exist.contacts : "a:0:{}",
												user_id: response.user_exist.user_id
											}, function (response) {
												if (response.status == "connected") {
													document.location.href = document.location.href;
												}
											}, "json");
										});
									}
									else if (response.status = "authorized") {
										document.location.href = document.location.href;
									}
								}, "json");
							});
						}

						function handleClientLoad() {
							gapi.client.setApiKey(apiKey);
							window.setTimeout(handleAuthResult(), 1);
						}

						function handleAuthResult(authResult) {
							if ((authResult) && (!authResult.error)) {
								$("#gplus").hide();
								makeApiCall();
							} else {
								$("#gplus").show();
								$("#gplus").click(function () {
									gapi.auth.authorize({
										client_id: clientId,
										scope: scopes,
										immediate: false
									}, handleAuthResult);
									return false;
								});
							}
						}

						function makeApiCall() {
							gapi.client.load('plus', 'v1', function () {
								var request = gapi.client.plus.people.get({
									'userId': 'me'
								});
								request.execute(function (resp) {
									$.post("/sign/gplus", {
										user: resp
									}, function (response) {
										if (response.status == "connect") {
											img = "https://image.meritokrat.org/r/user/" + response.user_exist.user_id + response.user_exist.photo_salt;
											upphoto = false;

											Popup.show();
											$(".frame").css("padding", "10px");

											$("#old_photo").css({
												backgroundImage: 'url(' + img + ')'
											});

											$("#new_photo").css({
												backgroundImage: 'url(' + response.user.image + ')'
											});

											Popup.setHtml($("#connectAccGplus").html());
											Popup.position();

											$("#no_connect").live("click", function () {
												Popup.close();
											});

											$("#update_photo").live('change', function () {
												if ($(this).is(':checked')) {
													upphoto = true;
												} else {
													upphoto = false;
												}
											});

											$("#connect").live("click", function () {
												$.post("/sign/gplus", {
													connect: true,
													upphoto: upphoto,
													contacts: response.user_contacts,
													user_id: response.user_auth.id
												}, function (response) {
													if (response.status == "connected") {
														document.location.href = document.location.href;
													}
												}, "json");
											});
										}
										else if (response.status = "authorized") {
											document.location.href = document.location.href;
										}
									}, "json");
								});
							});
						}
					</script>
					<script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>

</body>

<?=tag_helper::js('system.js') ?>
<?=tag_helper::js('module_' . context::get_controller()->get_module() . '.js' ) ?>

<? if ( conf::get('javascript_debug') ) { ?>
    <?=tag_helper::js('debug.js') ?>
<? } ?>

<? include dirname(__FILE__) . '/_js_static.php' ?>

</html>
