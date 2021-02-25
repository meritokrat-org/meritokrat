<?

load::app('modules/blogs/controller');

class blogs_edit_action extends blogs_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        $this->selected_menu = '/blogs';

        load::model('blogs/posts');
        load::model('blogs/programs');
        load::model('blogs/targets');

        if (request::get_file('file')) {
            $photo = request::get_file('file');
            load::system('storage/storage_simple');
            $storage = new storage_simple();
            $salt = substr(md5($photo['name']), 0, 10);
            $key = 'blogs/' . $salt . '.jpg';
            $storage->save_uploaded($key, request::get_file('file'));
            blogs_posts_peer::instance()->update(array('id' => request::get_int('id'), 'photo' => $photo['name']));
        }

        if (request::get_int('id')) {
            $this->post_data = blogs_posts_peer::instance()->get_item(request::get_int('id'));
            if ($this->post_data['type'] == blogs_posts_peer::TYPE_PROGRAMA_POST) {
                $this->programs = blogs_programs_peer::instance()->get_programs(request::get_int('id'));
                $this->targets = blogs_targets_peer::instance()->get_targets(request::get_int('id'));
            }
            $this->why = request::get_string('why');
            mem_cache::i()->set("blog_" . $this->post_data['id'] . "_" . session::get_user_id(), $this->why, 1800);


            if (($this->post_data['user_id'] != session::get_user_id()) && !session::has_credential('moderator')) {
                $this->redirect('/blog-' . session::get_user_id());
            }
        }
        if (!session::has_credential('admin'))
            if (!session::get_user_id() || ($this->post_data['user_id'] != session::get_user_id() && !user_auth_peer::instance()->get_rights(session::get_user_id(), 10) && !session::has_credential('editor'))) {
                throw new public_exception(t('У вас недостаточно прав'));
            }


        if (request::get('submit') && !request::get_file('file')) {
            if (in_array(request::get('type'), array(2, 3))) $mission_type = 1;
            if (trim(request::get('body')) && ((trim(request::get('anounces')) && !$mission_type) || $mission_type) && trim(request::get('title'))) {
                $tags = blogs_tags_peer::instance()->string_to_array(mb_strtolower(request::get('tags')));


                //if (session::get_user_id()==1299) $clean_text = stripslashes(request::get('body'));
                //else	$clean_text = blogs_posts_peer::instance()->clean_text(stripslashes(request::get('body')) );
                //$render_text = blogs_posts_peer::instance()->namize_text( $clean_text, $named_users );
                $clean_text = $render_text = stripslashes(request::get('body'));
                load::action_helper('text', true);
                $data = array(
                    'title' => mb_substr(trim(request::get('title')), 0, 256),
                    'body' => $clean_text,
                    'text_rendered' => $render_text,
                    'preview' => nl2br(text_helper::smart_trim(strip_tags($clean_text), 256)),
                    'anounces' => mb_substr(request::get_string('anounces'), 0, 400),
                    'tags_text' => implode(', ', $tags),
                    'public' => !request::get_int("private"),
                    'mission_type' => (in_array(request::get_int('type'), array(blogs_posts_peer::TYPE_NEWS_POST, blogs_posts_peer::TYPE_DECLARATION_POST, blogs_posts_peer::TYPE_BLOG_POST)) ? request::get_int('type') : request::get_int('mission_type')),
                    'favorite' => request::get_bool('favorite'),
                    'nocomments' => request::get_int('nocomments'),
                    'novotes' => request::get_int('novotes'),
                    'mpu' => request::get_int('mpu')
                );

                if (request::get_int('newtype') >= 0) {
                    $data['type'] = request::get_int('newtype', 1);
                    if ($data['type'] == blogs_posts_peer::TYPE_MIND_POST && (!session::has_credential('admin') || !session::has_credential('editor'))) $data['type'] = blogs_posts_peer::TYPE_BLOG_POST;
                }

                if (session::has_credential('admin') && request::get_int('views') > 1) {
                    $data['views'] = request::get_int('views');
                }

                if (session::has_credential('admin') && request::get_int('for') > 1) {
                    $data['for'] = request::get_int('for');
                }

                if (session::has_credential('admin')) {
                    if (request::get_int('show_in_mpu') == 1)
                        $data['show_in_mpu'] = true;
                    else
                        $data['show_in_mpu'] = false;
                }

                if (!$this->post_data) {
                    (request::get_int('type') == blogs_posts_peer::TYPE_NEWS_POST || request::get_int('type') == blogs_posts_peer::TYPE_DECLARATION_POST || $mission_type) ? $user_id = 31 : $user_id = session::get_user_id();

                    $data['created_ts'] = time();
                    $data['sort_ts'] = time();
                    $data['user_id'] = $user_id;

                    if (request::get_int("notchangeauthor") == 1) {
                        unset($data['user_id']);
                    }

                    $post_id = blogs_posts_peer::instance()->insert($data);
                    blogs_posts_peer::instance()->rate($post_id, session::get_user_id());

                    load::model('feed/feed');
                    load::view_helper('tag', true);

                    ob_start();
                    include dirname(__FILE__) . '/../../feed/views/partials/items/blog_post.php';
                    $feed_html = ob_get_clean();

                    load::model('bookmarks/bookmarks');

                    if (in_array($data['type'], array(blogs_posts_peer::TYPE_MIND_POST, blogs_posts_peer::TYPE_BLOG_POST))) {
                        $readers = array_unique(array_merge(
                            friends_peer::instance()->get_by_user(session::get_user_id()),
                            bookmarks_peer::instance()->get_users_who_like(session::get_user_id(), 6)));

                        feed_peer::instance()->add(session::get_user_id(), $readers, array(
                            'actor' => session::get_user_id(),
                            'text' => $feed_html,
                            'action' => feed_peer::ACTION_BLOG_POST,
                            'section' => feed_peer::SECTION_PERSONAL,
                            'oid' => $post_id,
                        ));
                    }
                } else {
                    $post_id = $data['id'] = $this->post_data['id'];
                    if (session::has_credential('admin'))
                        $data['created_ts'] = (strtotime(request::get('created_ts'))) ? ((request::get('created_ts') == date('d.m.Y', time())) ? time() : strtotime(request::get('created_ts'))) : 0;

                    if ($this->post_data['user_id'] != session::get_user_id()) {
                        $data['edit'] = session::get_user_id();

                        /*load::action_helper('user_email', false);
                        user_email_helper::admin_edit(array(
                            'user_id' => $this->post_data['user_id'],
                            'moderator' => session::get_user_id(),
                            'link' => 'http://'.conf::get('server').'/blogpost'.$this->post_data['id'],
                            'title'=> $this->post_data['title'],
                            'type' => t('пост')
                        ));*/
                        load::model('admin_feed');
                        admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_BLOG_POST, $text, $this->post_data, request::get_string('why'), 1);
                    }

                    if (session::has_credential('admin')) $data['edit'] = $this->post_data['user_id'];

                    blogs_posts_peer::instance()->update($data);
                    blogs_posts_tags_peer::instance()->delete_by_post($post_id);
                }

                if ($data['type'] == blogs_posts_peer::TYPE_PROGRAMA_POST) {
                    blogs_programs_peer::instance()->save_programs($post_id);
                    blogs_targets_peer::instance()->save_targets($post_id);
                }

                $this->rkey = "public_blogs_post_" . $post_id;

                //$mentions = (array)request::get('mentioned');
                //$mentions = array_unique(array_merge($mentions, $named_users));
                //blogs_mentions_peer::instance()->save_mentions($post_id, $mentions);

                foreach ($tags as $tag) {
                    $tag = mb_substr($tag, 0, 48);

                    $tag_id = blogs_tags_peer::instance()->obtain_id($tag);
                    blogs_posts_tags_peer::instance()->insert(array(
                        'post_id' => $post_id,
                        'tag_id' => $tag_id
                    ));
                }
                if (request::get_int('private') == 1)
                    db_key::i()->delete($this->rkey);
                else db_key::i()->set($this->rkey, 1); //
                if ($mission_type) $this->redirect('/blogs/edit?id=' . $post_id . '&tab=photo');
                $this->redirect('/blogpost' . $post_id);
            } else $this->warning = 1;
        } else
            $this->rkey = "public_blogs_post_" . request::get_int('id');
    }
}
