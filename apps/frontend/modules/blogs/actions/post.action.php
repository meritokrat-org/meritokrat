<?php

use App\Component\BlogPost\Breadcrumbs;

load::app('modules/blogs/controller');

/**
 * @property string selected_menu
 */
class blogs_post_action extends blogs_controller
{
    public function execute()
    {
        $this->selected_menu = '/blogs';

        if (session::has_credential('redcollegiant') && !db::get_scalar(
                "SELECT post_id FROM blogs_views WHERE post_id=".request::get_int(
                    'id'
                )." AND user_id=".session::get_user_id()
            )) {
            db::exec(
                'INSERT INTO blogs_views ("post_id", "user_id") VALUES ('.request::get_int(
                    'id'
                ).','.session::get_user_id().')'
            );
        }
        load::model('blogs/posts');
        if (!($this->post_data = blogs_posts_peer::instance()->get_item(request::get_int('id')))) {
            throw new public_exception("Публикация не найдена");
        }
//
        if (!session::is_authenticated() && !$this->post_data["public"]) {
            throw new public_exception(
                "Публикация не публична и не доступна для распространения|Доступно только для участников сети"
            );
        }

        if (session::get_user_id(
            ) != $this->post_data["user_id"] && $this->post_data["type"] == blogs_posts_peer::TYPE_ARCHIVE_POST) {
            $this->redirect("/blogs");
        }

        if ($this->post_data['type'] == 5 && $this->post_data['group_id'] > 0) {
            $this->redirect('/groups/post?group_id='.$this->post_data['group_id'].'&id='.$this->post_data['id']);
        }

        //просмотров=кол-во юзеров открывших пост
        if (!db_key::i()->exists('post_viewed:'.$this->post_data['id'].':'.session::get_user_id())) {
            blogs_posts_peer::instance()->update(
                array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id'])
            );
            db_key::i()->set('post_viewed:'.$this->post_data['id'].':'.session::get_user_id(), true);
            blogs_posts_peer::instance()->add_view($this->post_data['id'], session::get_user_id());
        }
        if (!session::is_authenticated() && !db_key::i()->exists(
                'post_viewed:'.$this->post_data['id'].':'.ip2long(str_replace('::ffff:', '', $_SERVER['REMOTE_ADDR']))
            )) {
            blogs_posts_peer::instance()->update(
                array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id'])
            );
            db_key::i()->set(
                'post_viewed:'.$this->post_data['id'].':'.ip2long(str_replace('::ffff:', '', $_SERVER['REMOTE_ADDR'])),
                true
            );
        }
        /*
         *
                //уникальный просмотр на каждую новую сессию
                if ( !session::get('post_viewed_' . $this->post_data['id']) )
        {
            blogs_posts_peer::instance()->update(array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id']));
            session::set('post_viewed_' . $this->post_data['id'], true);
        }
        */

        client_helper::register_variable('postId', $this->post_data['id']);
        client_helper::set_title(stripslashes(htmlspecialchars($this->post_data['title'])));

        load::model('blogs/comments');
        $this->comments = blogs_comments_peer::instance()->get_by_post($this->post_data['id']);

        load::model('user/user_data');
        load::view_helper('user');

        load::model('blogs/posts_tags');
        $this->set_slot('context', 'partials/context.posts');
        $this->similar = blogs_posts_peer::instance()->get_similar($this->post_data['id'], 5);

        load::model('user/blacklist');
        $this->is_blacklisted = user_blacklist_peer::is_banned($this->post_data['user_id'], session::get_user_id());

        //load::model('bookmarks/bookmarks');
        //$this->post_data['favorite'] = bookmarks_peer::is_bookmarked(session::get_user_id(), 1, request::get_int('id'));

        //var_dump($this->post_data['title']);

        client_helper::set_meta(
            array(
                'name'    => 'description',
                'content' => stripslashes(htmlspecialchars($this->post_data['preview'])),
            )
        );
        client_helper::set_meta(
            array(
                'name'    => 'keywords',
                'content' => str_replace(' ', ', ', $this->post_data['title']),
            )
        );

        $this->breadcrumbs = $this->get_breadcrumb($this->post_data);
        seo_helper::set_title(stripslashes(htmlspecialchars($this->post_data['title'])));
    }

    public function get_breadcrumb($post_data)
    {
        $breadcrumbs = Breadcrumbs::create();

        if ($post_data['type'] !== blogs_posts_peer::TYPE_PROGRAMA_POST && $post_data['user_id'] === session::get_user_id(
            )) {
            /*<a class="" href="/blog-<?= session::get_user_id() ?>"><?= t('Мои записи') ?></a> &rarr; t('Просмотр')-->*/
            $breadcrumbs
                ->add(['text' => 'Мои записи', 'href' => sprintf('/blog-%d', session::get_user_id())])
                ->add(['text' => 'Просмотр']);
        } elseif ($post_data['mission_type'] === blogs_posts_peer::TYPE_NEWS_POST) {
            /*<a href="/blogs/news"><?= t('Новости МПУ') ?></a> &rarr; <?= t('Просмотр') ?>*/
            $breadcrumbs
                ->add(['text' => 'Новости МПУ', 'href' => '/blogs/news'])
                ->add(['text' => 'Просмотр']);
        } elseif ($post_data['mission_type'] === blogs_posts_peer::TYPE_DECLARATION_POST) {
            /*<a href="/blogs/declarations"><?= t('Объявления') ?></a> &rarr; <?= t('Просмотр') ?>*/
            $breadcrumbs
                ->add(['text' => 'Объявления', 'href' => '/blogs/declarations'])
                ->add(['text' => 'Просмотр']);
        } elseif ($post_data['type'] === blogs_posts_peer::TYPE_BLOG_POST) {
            /*<a href="/blogs/?type=1"><?= t('Блоги') ?></a> &rarr; <?= t('Просмотр') ?>*/
            $breadcrumbs
                ->add(['text' => 'Блоги', 'href' => '/blogs/?type=1'])
                ->add(['text' => 'Просмотр']);
        } elseif ($post_data['type'] === blogs_posts_peer::TYPE_PROGRAMA_POST) {
            /*<a href="/blogs/programs"><?= t('Успішна Україна') ?></a> &rarr; <?= t('Просмотр') ?>*/
            $breadcrumbs
                ->add(['text' => 'Успішна Україна', 'href' => '/blogs/programs'])
                ->add(['text' => 'Просмотр']);
        } else {
            /*<a href="/blogs"><?= t('Публикации') ?></a> &rarr; <?= t('Просмотр') ?>*/
            $breadcrumbs
                ->add(['text' => 'Публикации', 'href' => '/blogs'])
                ->add(['text' => 'Просмотр']);
        }

        return $breadcrumbs;
    }
}