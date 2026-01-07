<?php
/**
 * @var basic_controller $controller
 */

require_once '_counter.php';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='/static/javascript/jquery/jquery-1.4.2.js'></script>
    <script src='/static/javascript/jquery/jquery.ui.core.js'></script>
    <script src='/static/javascript/jquery/selects.js'></script>
    <script src='/static/javascript/jquery/jquery.ui.widget.js'></script>
    <script src='/static/javascript/jquery/jquery.ui.mouse.js'></script>
    <script src='/static/javascript/library/md5.js'></script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
    <script src='/static/javascript/library/plugins/cookie.js'></script>

    <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/static/css/material-icons.css">

    <script src="https://kit.fontawesome.com/7edf9be78e.js" crossorigin="anonymous"></script>

    <script>
        jQuery(document).ready(function () {
            $('#target_text').html($('#seo_text_container').html());
            $('#seo_text_container').remove();
        });
    </script>

    <?php if (isset($post_data) && empty($error_message) && 'blogs' === context::get_controller()->get_module(
            )) { ?><?php preg_match(
            '/\<title\>(.*)\<\/title\>/',
            seo_helper::get_title(
                    context::get_controller()->get_module(),
                    context::get_controller()->get_action(),
                    session::get('language', 'ua')
            ),
            $match
    ); ?><?php $user = user_data_peer::instance()->get_item($post_data['user_id']) ?>

        <?php

        $doc = new DOMDocument();
        @$doc->loadHTML($post_data['body']);

        $tags = $doc->getElementsByTagName('img');

        $image = 'https://meritokrat.org/static/images/logos/logo.png';
        foreach ($tags as $tag) {
            $image = $tag->getAttribute('src');
            break;
        }

        ?>

        <meta property="og:image" content="<?= $image ?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="https://meritokrat.org/blogpost<?= $post_data['id'] ?>"/>
        <meta property="og:title" content="<?= explode(' - ', $match[1])[0] ?>"/>
        <meta property="og:description" content="<?= str_replace('<br />', '', $post_data['preview']) ?>"/>
    <?php } ?>

    <?php if (!empty($error_message) && 'ooops' === context::get_controller()->get_module(
            )) { ?><?php $tokens = explode('|', $error_message); ?>
        <meta property="og:image" content="https://meritokrat.org/static/images/error.png"/>
        <meta property="og:url" content="https://meritokrat.org/<?= $_SERVER['REQUEST_URI'] ?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="Ошибка<?= !empty($tokens[0]) ? ': '.$tokens[0] : 'Документ, который Вы запрашиваете, не найден' ?>"/>
        <meta property="og:description" content="<?= isset($tokens[1]) ? $tokens[1] : 'Документ, который Вы запрашиваете, не найден. Возможно, ошиблись с вводом ссылки или документ перенесли в другой раздел' ?>"/>
    <?php } ?>

    <?php if ('shop' === context::get_controller()->get_module()) { ?><?php $tokens = explode('|', $error_message); ?>
        <meta property="og:image" content="https://meritokrat.org/static/images/logos/logo.png"/>
        <meta property="og:url" content="https://meritokrat.org/shop"/>
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="М-Магазин"/>
        <meta property="og:description" content="Интернет магазин партийных принадлежностей"/>
    <?php } ?>

    <title><?= seo_helper::getTitle(
                context::get_controller()->get_module(),
                context::get_controller()->get_action(),
                session::get('language', 'ua')
        ) ?></title>
    <?= seo_helper::get_meta(
            context::get_controller()->get_module(),
            context::get_controller()->get_action(),
            session::get('language', 'ua')
    ); ?>

    <meta name="googlebot" content="noarchive"/>
    <?= tag_helper::css('system.css') ?>
    <?= tag_helper::rss() ?>
    <style type="text/css">
        .submenu a {
            font-size: 12px;
            color: black;
            font-weight: bold;
        }

        .mlx {
            margin-left: 20px;
        }

        .rating_info {
            visibility: visible;
        }

        .rating_info a {
            display: inline-block;
            color: black;
            font-weight: normal;
        }

        a#logo:hover {
            color: inherit;
            text-decoration: inherit;
        }

        a#logo > div {
            margin-top: -10px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 8pt;
            text-align: justify;
            letter-spacing: 2px;
        }

        a#logo > div:after {
            content: "";
            display: inline-block;
            width: 100%;
        }
    </style>
    <link rel="shortcut icon" href="/favicon.ico"/>
</head>

<body class="text-body">

<div>

    <?= call_user_func(require __DIR__.'/partials/Header.php') ?>

    <div id="main_content">
        <div class="root_container container_bg row mb-5" style="margin-top: 6px;">
            <?php if ('election' !== request::get('module')) { ?>

                <div class="col p-0 m-0">
                    <?php include $controller->get_template_path() ?>
                </div>

                <div id="left" class="col-3 pl-1 m-0<?php if (!session::is_authenticated()) { ?> d-none<?php } ?>">
                    <?php if (!('up' === request::get('action') && 'sign' === request::get(
                                    'module'
                            ))) { ?><?php include __DIR__.'/partials/profile.php'; ?><?php } ?><?php if ($controller->get_slot_path(
                            'context'
                    )) { ?><?php include $controller->get_slot_path('context') ?><?php } ?>
                </div>

            <?php } else { ?><?php include $controller->get_template_path() ?><?php } ?>

            <?php if (0 && session::has_credential('admin') && $seo_text = seo_helper::getText(
                            context::get_controller()->get_module(),
                            context::get_controller()->get_action(),
                            session::get('language', 'ua')
                    )) { ?>
                <div class="mt10 mb10" id="target_text"></div>
            <?php } ?>
            <br/>
        </div>
    </div>

    <?= call_user_func(require __DIR__.'/partials/Footer.php') ?>

    <!-- POPUPS STARTED -->
    <?php $additional_data = user_data_peer::instance()->get_item(session::get_user_id()); ?>
    <?php if (
            5 !== ($userId = session::get_user_id()) && $userId > 10907
            && db_key::i()->get('all_visits_'.session::get_user_id()) > 2
            && !db_key::i()->get('seen_form'.date('md').':'.session::get_user_id())
    ) { ?><?php db_key::i()->set(
            'seen_form'.date('md').':'.$userId,
            true
    ); //сегодня этому товарищу больше не показывать ?>
        <div id="popup_opacity" class="hide" style="position: absolute; left: 0pt; top: 0pt; width: 1700px; height: 2700px; text-align: center; z-index: 10000; background: none repeat scroll 0% 0% rgb(0, 0, 0);  opacity: 0.5; filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50); -moz-opacity: 0.5; -khtml-opacity: 0.5;" id="popup_box" class="popup_box"></div><?php include 'popups/target.php'; ?>
        <script>
            jQuery(document).ready(function () {
                <?php if( !('blogs' == $_REQUEST['module'] && 'post' == $_REQUEST['action'] && 3910 == $_REQUEST['id'])){ ?>
                $('#footer').hide();
                $('#popup_opacity').show();
                $('#popup_content').show(1000);
                $('#popup_content_target').fadeIn(200);
                <?php } ?>

                $('#close_popup').click(function () {
                    $('#popup_content_target').fadeOut(100);
                    $('#popup_content').hide(300);
                    $('#popup_opacity').hide();
                    $('#footer').show();
                });
            });
        </script>

    <?php } ?>
    <!-- POPUPS END -->
    <!--<div id="footer" class="top_line_2 fs11"-->
    <!--     style="background: transparent url(/static/images/common/bg-footer.png) repeat scroll 0% 0%; height: 178px; ">-->
    <!---->
    <!--    <div-->
    <!--            style="background: transparent no-repeat scroll center center; height: 176px; -moz-background-clip: border;"-->
    <!--            class="top_line_2 fs11">-->
    <!--        <div class="root_container mt10 footer">--><?php //include __DIR__.'/_footer.php' ?><!--<br/>&nbsp;</div>-->
    <!--    </div>-->
    <!--    --><?php //if (!conf::get('enable_web_debug')) {
    //         include '_counter.php';
    //     } ?>
    <!--</div>-->
</div>

<script src="/bootstrap/js/bootstrap.bundle.js"></script>
<?= tag_helper::js('/lib/ui.js') ?>
<?= tag_helper::js('/lib/api.js') ?>
<?= tag_helper::js('system.js') ?>
<?php if ('home' !== context::get_controller()->get_module()): ?>
    <?= tag_helper::js('module_'.context::get_controller()->get_module().'.js') ?><?php endif ?>
<?php if (conf::get('javascript_debug') || session::has_credential('programmer')) { ?>
    <?= tag_helper::js('debug.js') ?><?php } ?>
<?php include __DIR__.'/_js_static.php' ?>
<?php if (true === conf::get('enable_web_debug')){ ?><!-- Executed in: <?= (microtime(
                true
        ) - APP_START_TS) ?>ms --><?php } ?>
</body>
</html>
