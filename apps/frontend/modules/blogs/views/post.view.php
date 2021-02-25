<?php //новости или обьявления

/**
 * @var array       $post_data
 * @var Breadcrumbs $breadcrumbs
 */

use App\Component\BlogPost\Breadcrumbs;

$mpu_types = [blogs_posts_peer::TYPE_NEWS_POST, blogs_posts_peer::TYPE_DECLARATION_POST];

?>
<style>
    .textcontainer img {
        margin: 0 10px 5px 0
    }
</style>
<div class="mb-2">
    <div class="column_head d-flex align-items-center p-0 px-2 m-0">
        <div class="flex-grow-1">
            <?= $breadcrumbs->render() ?>
        </div>
        <?php if ($post_data['user_id'] === session::get_user_id() || session::has_credential('moderator')) { ?>
            <div class="row-cols-auto">
                <a id="blogedit" title="<?= t('Редагувати') ?>" class="btn btn-sm btn-link link-light p-0"
                   href="<?= 5 === session::get_user_id() ? '/blogs/edit?id='.$post_data['id'] : 'javascript:;' ?>">
                    <i class="material-icons x-3">edit</i>
                </a>
                <?php if (session::is_authenticated()) { ?>
                    <?php $bkm = bookmarks_peer::instance()->is_bookmarked(
                        session::get_user_id(),
                        1,
                        $post_data['id']
                    ); ?>
                    <a class="btn btn-sm btn-link link-light p-0 b1 <?= ($bkm) ? 'hide' : '' ?>" href="#add_bookmark"
                       onclick="Application.bookmarkItem('1','<?= $post_data['id'] ?>'); return false;"
                       title="<?= t('В закладки') ?>">
                        <i class="material-icons x-3">star_outline</i>
                    </a>
                    <a class="btn btn-sm btn-link link-light p-0 b1 <?= ($bkm) ? '' : 'hide' ?>" href="#del_bookmark"
                       onclick="Application.unbookmarkItem('1','<?= $post_data['id'] ?>');return false;"
                       title="<?= t('Удалить из закладок') ?>">
                        <i class="material-icons x-3">star</i>
                    </a>
                <?php } ?>
                <?php if (session::has_credential('admin')) { ?>
                    <a href="/blogs/hide?id=<?= $post_data['id'] ?>" class="btn btn-sm btn-link link-light p-0"
                       title="<?= t(true === $post_data['visible'] ? 'Скрыть' : 'Показать') ?>">
                        <i class="material-icons x-3">visibility<?php if (true === $post_data['visible']) { ?>_off<?php } ?></i>
                    </a>
                <?php } ?>
                <a title="<?= t('Удалить') ?>" class="btn btn-sm btn-link link-light p-0"
                    <?php if (!in_array(session::get_user_id(), [5, $post_data['user_id']], true)) { ?>
                        onclick="Application.delItem('<?= $post_data['id'] ?>','blogs/delete')" href="javascript:void 0;"
                    <?php } else { ?>
                        href="/blogs/delete?id=<?= $post_data['id'] ?>"
                        onclick="return confirm('<?= t('Вы уверены?') ?>');"
                    <?php } ?>
                >
                    <i class="material-icons x-3">delete</i>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<div class="float-end">
    <?php if (session::has_credential('admin')) { ?>
        <a href="/blogs/valuable?id=<?= $post_data['id'] ?>" class="typechanger fs11"><?= t('Сделать важным') ?></a>
    <?php } ?>
    <?php if (5 === session::get_user_id()) { ?>
        <?php if ($post_data['type'] === blogs_posts_peer::TYPE_MIND_POST && !$post_data['group_id']) { ?>
            <a href="javascript:" class="typechanger fs11"
               onclick="blogsController.changeType(this,<?= $post_data['id'] ?>,<?= blogs_posts_peer::TYPE_BLOG_POST ?>)"><?= t(
                    'Перенести в блоги'
                ) ?></a>
        <?php } elseif ($post_data['group_id'] > 0 && $post_data['type'] === blogs_posts_peer::TYPE_MIND_POST) { ?>
            <a href="javascript:" class="typechanger fs11"
               onclick="blogsController.changeType(this,<?= $post_data['id'] ?>,<?= blogs_posts_peer::TYPE_GROUP_POST ?>)">Прибрати
                з публікацій</a>
        <?php } elseif ($post_data['type'] === blogs_posts_peer::TYPE_BLOG_POST) { ?>
            <a href="javascript:" class="typechanger fs11"
               onclick="blogsController.changeType(this,<?= $post_data['id'] ?>,<?= blogs_posts_peer::TYPE_MIND_POST ?>)"><?= t(
                    'Перенести в публикации'
                ) ?></a>
        <?php } ?>
    <?php } ?>
</div>
<div class="clear"></div>

<div class="row m-0">
    <div class="col-2 acenter m-0 p-0" id="leftcoll">
        <?php if (in_array($post_data['mission_type'], [2, 3], true)) { ?>
            <?php
            load::view_helper('image');
            if (!$post_data['photo']) {
                echo user_helper::photo(
                    31,
                    's',
                    [
                        'class'         => 'border1',
                        'id'            => 'photo',
                        'without-stats' => session::is_authenticated() ? false : true,
                    ]
                );
            } else {
                echo image_helper::photo(
                    $post_data['photo'],
                    's',
                    'blogs',
                    ['class' => 'border1', 'id' => 'photo']
                );
            }
            ?>
        <?php } else { ?>
            <?php if ($post_data['mpu'] > 0) { ?>
                <?php echo user_helper::photo(
                    31,
                    't',
                    ['class' => 'border1', 'without-stats' => session::is_authenticated() ? false : true],
                    true,
                    'user',
                    '',
                    true
                ) ?>
            <?php } else { ?>
                <?php echo user_helper::photo(
                    $post_data['user_id'],
                    't',
                    ['class' => 'border1', 'without-stats' => true],
                    true,
                    'user',
                    '',
                    false
                ) ?>
                <div id="userholder">
                    <?= user_helper::full_name(
                        $post_data['user_id'],
                        true,
                        ['class' => 'fw-bold'],
                        false
                    ) ?>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if (!in_array($post_data['mission_type'], $mpu_types, true)) { ?>
            <div class="fs11 mb10 mt10 left">
                <!--			<a href="/blog---><?php //=$post_data['user_id']?><!--">-->
                <?php //=t('Все публикации участника')?><!--</a>-->
            </div>

            <?php if (session::is_authenticated()) { ?>
                <?php load::model('bookmarks/bookmarks'); ?>
                <?php $bkm = bookmarks_peer::instance()->is_bookmarked(
                    session::get_user_id(),
                    6,
                    $post_data['user_id']
                ); ?>
                <div class="fs11 mb10">
                    <a href="#add_bookmark" class="b6 <?= ($bkm) ? 'hide' : '' ?>"
                       onclick="Application.bookmarkItem('6','<?= $post_data['user_id'] ?>');return false;"><?= t(
                            'Добавить в любимые авторы'
                        ) ?></a>
                    <a href="#del_bookmark" class="b6 <?= ($bkm) ? '' : 'hide' ?>"
                       onclick="Application.unbookmarkItem('6','<?= $post_data['user_id'] ?>');return false;"><?= t(
                            'Удалить из любимых авторов'
                        ) ?></a>
                </div>
            <?php } ?>

            <br/>

            <?php if (session::has_credential('admin')) { ?>
                <?php if (session::is_authenticated() && !$post_data['novotes']) { ?>
                    <div class="fs11 mb5 quiet"><?= t('Оценка') ?></div>

                    <div class="mb5 acenter" id="vote_value">
                        <span class="green"><?= $post_data['for'] ?></span>
                        <span class="red ml5"><?= $post_data['against'] ?></span>
                    </div>
                <?php } ?>

                <?php if (session::has_credential('admin') && !$post_data['novotes']) { ?>
                    <a class="fs10" href="/blogs/rate_history?id=<?= $post_data['id'] ?>"><?= t('История') ?></a>
                    <br/><br/>
                <?php } ?>

                <?php if (session::is_authenticated() && !$post_data['novotes'] && !$is_blacklisted
                    && !blogs_posts_peer::instance()->has_rated(
                        $post_data['id'],
                        session::get_user_id()
                    )) { ?>
                    <div id="vote_pane">
                        <a title="<?= t('Голосовать за') ?>" onclick="blogsController.vote( true );" href="javascript:"
                           class="dib icoupg"></a>
                        <a title="<?= t('Голосовать против') ?>" onclick="blogsController.vote( false );"
                           href="#comment_form" class="dib icodown"></a>
                    </div>
                <?php } ?>
            <?php } ?>

        <?php } ?>

        <?php //if ( session::is_authenticated() ) { ?>
        <div class="fs11 mb5 quiet"><?= t('Просмотров') ?></div>
        <div class="acenter bold mb10">
            <?php if (session::has_credential('admin')) { ?>
                <a href="/blogs/views_history?id=<?= $post_data['id'] ?>"><?= (int) $post_data['views'] ?></a>
            <?php } else { ?>
                <?= (int) $post_data['views'] ?>
            <?php } ?>
        </div>
        <?php //} ?>

        <?php if (1 == $post_data['type']
            && (session::has_credential('redcollegiant')
                || session::has_credential(
                    'superadmin'
                ))) { ?>
            <div class="acenter bold mt15">
                <?php if (session::has_credential('redcollegiant')
                    && !db_key::i()->exists(
                        'post_'.$post_data['id'].'_vote_'.session::get_user_id()
                    )
                    && !db_key::i()->exists('post_'.$post_data['id'].'_veto')) { ?>
                    <input type="bytton" class="button acenter" style="width:80px;height:17px;" id="to_publication"
                           rel="<?= $post_data['id'] ?>" value="<?= t('В публикации') ?>">
                <?php } elseif (!db_key::i()->exists('post_'.$post_data['id'].'_veto')) { ?>
                    <span class="fs12"><?= t('В публикации') ?></span>
                <?php } ?>
                <div class="fs16 mt10 green" id="post_voted">
                    <?= db::get_scalar(
                        "SELECT count(*) FROM blogs_votes WHERE post_id=:post_id",
                        ['post_id' => $post_data['id']]
                    ) ?>
                </div>
                <?php if (5 == session::get_user_id()) { ?>
                    <?php if (!db_key::i()->exists('post_'.$post_data['id'].'_veto')) { ?>
                        <input type="bytton" value="Вето" id="veto_publication"
                               style="width: 60px; height: 17px; margin-left: 9px;" class="button_gray mt10 acenter"
                               rel="<?= $post_data['id'] ?>">
                    <?php } ?>
                    <span class="fs11 cgray">вето на публікацію</span>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <div class="col m-0 p-0">
        <?php $blogs_types = [
            blogs_posts_peer::TYPE_BLOG_POST     => 'Блог',
            blogs_posts_peer::TYPE_NOTATE_POST   => t('Заметка'),
            blogs_posts_peer::TYPE_ARCHIVE_POST  => t('Архив'),
            blogs_posts_peer::TYPE_MIND_POST     => t('Публикация'),
            blogs_posts_peer::TYPE_NEWS_POST     => t('Новость'),
            blogs_posts_peer::TYPE_PROGRAMA_POST => '*'.t('Програма'),
        ];
        ?>
        <h1 class="mb10 mt10 fs18 cbrown"><?= stripslashes(htmlspecialchars($post_data['title'])) ?></h1>

        <div class="fs12 mb5">
            <?= ($post_data['edit'] && !in_array($post_data['user_id'], user_auth_peer::get_admins(), true)) ? t(
                    'Отредактировано'
                ).': '.strip_tags(
                    user_helper::full_name($post_data['edit']),
                    '<a>'
                ).(($post_data['edit_ts']) ? ' '.user_helper::com_date(
                        date($post_data['edit_ts'])
                    ) : '').'<br/>' : '' ?>
            <?php if ($post_data['tags_text']) { ?>
                <b class="quiet mr5"><?= t('Метки') ?>:</b>
                <?php foreach (explode(
                                   ', ',
                                   $post_data['tags_text']
                               ) as $tag) echo "<a href=\"/blogs/index?tag=".htmlspecialchars(
                        $tag
                    )."\" class=\"mr5\">".stripslashes($tag)."</a>" ?>
            <?php } ?>
        </div>
        <div class="fs11 left  mb10">
            <?= date_helper::human($post_data['created_ts'], ', ') ?> | <span
                    class="bold"><?= $blogs_types[$post_data['type']] ?></span>
        </div>
        <?php /* if ( $mentioned ) { ?>
			<div class="ml10 box_content right fs11" style="width: 200px;">
				<h6 class="column_head_small"><?=t('В этой статье упоминаются')?></h6>
				<div class="ml10 mr10 mb10">
					<? foreach ( $mentioned as $user_id ) { ?>
						<? $user = user_auth_peer::instance()->get_item($user_id) ?>
						<?=user_helper::photo($user_id, 's', array('class' => 'left'))?>
						<div style="margin-left: 60px;">
							<?=user_helper::full_name($user_id)?>
							<div class="mt10 quiet"><?=user_auth_peer::get_type($user['type'])?></div>
						</div>
						<div class="clear mb10"></div>
					<? } ?>
				</div>
			</div>
		<? } */ ?>

        <div class="clear"></div>
        <div class="mt15 textcontainer">
            <?php $post_data['body'] = preg_replace(
                '#<p><!\-\-\[if gte mso 9\]>(?:.*)<!\-\-\[endif\] \-\-></p>#Uis',
                '',
                $post_data['body']
            ); ?>
            <?= user_helper::get_links(stripslashes($post_data['body']), false) ?>
        </div>
        <div class="clear"></div>
        <div id="additional" style="border-top: 1px solid #EEE;">
            <div class="left mt5">

                <!-- AddThis Button BEGIN -->
                <?= user_helper::get_adds($post_data['title'], $post_data['preview'], $post_data['body']) ?>
                <!-- AddThis Button END -->

            </div>

            <!--			<div class="fs11 right ml10" style="margin-top:4px;"><a href="mailto:secretariat@shevchenko.ua?subject=<?= t(
                'Предложение по идеологии'
            ) ?>" class="promote right mb10"><?= t('Отправить предложение') ?></a></div>-->

            <?php if (session::is_authenticated()) { ?>
                <?= user_helper::share_item('blog_post', $post_data['id'], ['class' => 'mb10 ml5 right']) ?>
                <?php if (session::get_user_id() != $post_data['user_id']) { ?>
                    <a class="bookmark mb10 ml5 right b1" style="<?= ($bkm) ? 'display:none' : '' ?>"
                       href="#add_bookmark"
                       onclick="Application.bookmarkItem('1','<?= $post_data['id'] ?>');return false;"><b></b><span><?= t(
                                'В закладки'
                            ) ?></span></a>
                    <a class="unbkmrk mb10 ml5 right b1" style="<?= ($bkm) ? '' : 'display:none' ?>"
                       href="#del_bookmark"
                       onclick="Application.unbookmarkItem('1','<?= $post_data['id'] ?>');return false;"><b></b><span><?= t(
                                'Удалить из закладок'
                            ) ?></span></a>
                <?php } ?>
            <?php } ?>
            <div class="clear"></div>
        </div>

        <div class="acenter p10">
        </div>
        <?php if (!$post_data['nocomments']) { ?>
            <div class="column_head">
                <div class="left icocomments mr5" style="margin-top:3px"></div>
                <div class="left" style="margin-top:2px"><?= t('Комментарии') ?></div>
            </div>
            <div class="mt10 mb10" id="comments">
                <?php if (!$comments) { ?>
                    <div id="no_comments" class="acenter fs11 quiet"><?= t('Нет комментариев') ?></div>
                <?php } elseif (session::is_authenticated()/* || $post_data['type']==blogs_posts_peer::TYPE_MIND_POST*/) { ?>
                    <?php foreach ($comments as $id) {
                        include 'partials/comment.php';
                    } ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php load::model("user/user_auth"); ?>
        <?php $uauth = user_auth_peer::instance()->get_item(session::get_user_id()); ?>
        <?php if ((session::is_authenticated()
                && user_auth_peer::instance()->get_rights(
                    session::get_user_id(),
                    5
                )
                && 0 == $uauth["ban"]) or (session::is_authenticated() && $post_data['mpu'] > 0)) { ?>
            <?php if (!$is_blacklisted) { ?>
                <?php if (!$post_data['nocomments']) { ?>
                    <form class="form_bg" id="comment_form" action="/blogs/comment">
                        <h3 class="column_head_small mb5"><?= t('Добавить комментарий') ?></h3>
                        <div class="ml10 mr10 mb10">
                            <input type="hidden" name="neg_msg" value="0"/>
                            <input type="hidden" name="post_id" value="<?= $post_data['id'] ?>"/>
                            <textarea rel="<?= t('Напишите текст') ?>" style="width: 99%; height: 75px;"
                                      name="text"></textarea>
                            <input type="submit" name="submit" class="mt5 mb5 button left"
                                   value=" <?= t('Добавить') ?> "/>
                            <input type="button" name="cancel_v" class="mt5 ml5 mb5 left button_gray hide"
                                   value=" <?= t('Отменить') ?> "/>
                            <?= tag_helper::wait_panel() ?>
                        </div>
                    </form>

                    <form id="comment_reply_form" class="hidden" action="/blogs/comment">
                        <input type="hidden" name="post_id" value="<?= $post_data['id'] ?>"/>
                        <input type="hidden" name="parent_id"/>
                        <textarea rel="<?= t('Напишите текст') ?>" style="width: 99%; height: 50px;"
                                  name="text"></textarea>
                        <input type="submit" name="submit" class="mt5 mb5 button" value=" <?= t('Отправить') ?> "/>
                        <?= tag_helper::wait_panel() ?>
                        <input type="button" class="button_gray" value="<?= t('Отмена') ?>"
                               onclick="$('#comment_reply_form').hide();">
                    </form>

                    <form id="comment_update_form" class="hidden" action="/blogs/comment">
                        <input type="hidden" name="upd_id" id="upd_id"/>
                        <input type="hidden" name="why" id="why"/>
                        <input type="hidden" name="post_id" value="<?= $post_data['id'] ?>"/>
                        <textarea rel="<?= t('Напишите текст') ?>" style="width: 99%; height: 100px;"
                                  name="text"></textarea>
                        <input type="submit" name="submit" class="mt5 mb5 button" value=" <?= t('Сохранить') ?> "/>
                        <?= tag_helper::wait_panel() ?>
                        <input type="button" class="button_gray" value="<?= t('Отмена') ?>"
                               onclick="Application.cancelComUpd()">
                    </form>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <?php if (!session::is_authenticated()) { ?>
                <div class="mt10 p5 acenter fs12" style="border: 1px solid #E4E4E4; background: #F7F7F7;">
                    <a href="/sign/up" class="bold"><?= t('Зарегистрируйтесь') ?></a>&nbsp;<?= t('или') ?>&nbsp;<a
                            href="/sign"
                            class="bold"><?= t('войдите') ?></a>&nbsp;<?= t(
                        'на сайт чтобы иметь возможность оставлять комментарии'
                    ) ?>
                </div>
            <?php } else { ?>
                <?= user_helper::login_require(
                    t('Недостаточно прав').', '.t('чтобы оставлять комментарии'),
                    '/help/index?prava_v_merezhi'
                ) ?>
            <?php }
        } ?>
    </div>
</div>

<script type="text/javascript">
    $('#blogedit').click(function () {
        <?$why_text = mem_cache::i()->get("blog_".$post_data['id']."_".session::get_user_id());?>
        var why;
        var whytext = '<?=$why_text?>';
        <?php if (session::get_user_id() != $post_data['user_id'] && !session::has_credential('admin')) { ?>
        var why = prompt("Вкажiть причину редагування:", whytext);
        if (!why) {
            alert("Ви не можете редагувати без поважної причини");
            return false;
        }
        <?php } ?>
        window.location = 'http://' + window.location.hostname + '/blogs/edit?id=<?=$post_data['id']?>&why=' + why;
    });
    $(document).ready(function ($) {
        $('.comhide').unbind('click').click(function () {
            $(this).hide().next().show();
            $('#child_comments_' + $(this).attr('rel')).hide();
        });
        $('.comshow').unbind('click').click(function () {
            $(this).hide().prev().show();
            $('#child_comments_' + $(this).attr('rel')).show();
        });
        <?php if (!in_array($post_data['user_id'], [2, 5, 31, 1299, 10599], true)) { ?>
        $('.textcontainer').find('img').each(function () {
            var wdth = $(this).width();
            if (wdth < 400) {
                $(this).attr('align', 'left');
            } else {
                $(this).css({'margin-left': (620 - wdth) / 2 + 'px'});
            }
        });
        <?php } ?>

        $('#to_publication').click(function () {
            $('#to_publication').hide();
            $.get('/blogs/to_publication', {'id': $(this).attr('rel')}, function (data) {
                $('#post_voted').html(data);
            });
        });

        $('#veto_publication').click(function () {
            $.get('/blogs/to_publication', {'veto_id': $(this).attr('rel')}, function (data) {
                $('#post_voted').html(data);
            });
            $('#veto_publication').hide();
            $('#to_publication').hide();
        });

    });

    function doprint() {
        /*document.getElementById('additional').style.display = 'none';
         document.getElementById('footer').style.display = 'none';
         document.getElementById('header').style.display = 'none';
         document.getElementById('left').style.display = 'none';
         document.getElementById('comment_form').style.display = 'none';*/

        $('#additional, #footer, #header, #left, #comment_form, #comments, .column_head, #vote_pane, .actionpanel').remove();
        $('.root_container').css({width: '820px'});
        $('.textcontainer').addClass('fs16').css({width: '800px'});
        $('#leftcoll, div.addthis_toolbox').hide();
        $('.left').removeClass('left');
        $($('#userholder')).insertAfter($('h1'));
        $('<img src="/static/images/logos/printtop_' + context.language + '.png" alt="Меритократ.org" /><hr class="mt10" style="width:800px;border-top:1px solid black;"/>').insertBefore($('h1'));
        window.print();
    }
</script>
