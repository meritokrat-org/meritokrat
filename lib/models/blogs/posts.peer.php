<?php

load::lib('purifier/HTMLPurifier.auto');
load::lib('text/names.extractor');

class blogs_posts_peer extends db_peer_postgre
{
    protected $table_name = 'blogs_posts';

    const TYPE_MIND_POST = 0;//публикации
    const TYPE_BLOG_POST = 1;//личный блог
    const TYPE_NEWS_POST = 2;//новость оргкомитета
    const TYPE_DECLARATION_POST = 3;//объявление оргкомитета
    const TYPE_COPIED_POST = 4;
    const TYPE_GROUP_POST = 5; //пост группы
    const TYPE_HIDDEN_GROUP_POST = 6; // возможно прийдется использовать
    const TYPE_NOTATE_POST = 7; //нотатки
    const TYPE_ARCHIVE_POST = 8; //архів
    const TYPE_PROGRAMA_POST = 9; //програма

    /**
     * @return blogs_posts_peer
     */

    public static function instance()
    {
        return parent::instance('blogs_posts_peer');
    }

    public static function get_sferas()
    {
        return array(
            1 => t('Партстроительство'),
            5 => t('Идеология'),
            10 => t('Агитация и информработа'),
            30 => t('Другое')
        );
    }

    public static function get_sfera($id)
    {
        $list = self::get_sferas();
        if ($id > 0) return $list[$id];
        else return '';
    }

    public function get_by_user($id, $type = self::TYPE_MIND_POST, $groups = false)
    {
        if ($groups && count($groups) > 1 && is_array($groups)) {
            return db::get_cols("SELECT id FROM blogs_posts WHERE user_id=:user_id AND type=:type AND group_id in (" . implode(',', $groups) . ") ORDER BY id DESC", array('user_id' => $id, 'type' => $type));
        } else return parent::get_list(array('user_id' => $id, 'type' => $type));
    }

    // unusefull method?
    public function get_casted($type = self::TYPE_MIND_POST)
    {
        if (!session::is_authenticated()) {
            $public = 'and public = true';
        }
        $row = db::get_row('SELECT id FROM ' . $this->table_name . ' WHERE user_id = :value and type=:type ' . $public . ' ORDER by id DESC LIMIT 1', array('value' => 5, 'type' => $type));
        $sql = 'SELECT id
				FROM ' . $this->table_name . '
				WHERE visible = true and type=' . $type . ' ' . $public . '
				ORDER BY created_ts DESC LIMIT 1000';
        $result = db::get_cols($sql, array()/*'type' => self::TYPE_NEWS_POST)*/, $this->connection_name, array('posts_casted_' . $type, 60 * 4));
        array_unshift($result, $row['id']);
        return array_unique($result);
    }

    public function get_rated($type = self::TYPE_MIND_POST, $for = 6, $with_photo = true)
    {
        if (!session::is_authenticated()) {
            $public = 'and public = true';
        }
        if ($type == self::TYPE_MIND_POST) {
            $typestr = ' AND (type = ' . self::TYPE_MIND_POST . ' OR type = ' . self::TYPE_PROGRAMA_POST . ' OR onmain = 1) ';
        } else {
            $typestr = ' AND type = ' . $type . ' ';
        }
//                $row=db::get_row('SELECT id FROM ' . $this->table_name . ' WHERE user_id = :value '.$typestr.' '.$public.' ORDER BY created_ts DESC LIMIT 1', array('value' => 5));
        $sql = 'SELECT id
				FROM ' . $this->table_name . '
				WHERE visible = true ' . $typestr . ' ' . $public . '
				ORDER BY created_ts DESC LIMIT 1000';
        $result = db::get_cols($sql); //, array(),$this->connection_name, array('posts_rated_'.$type, 60*5)
        /*
            $where.=' and type='.$type;
            if ($with_photo) $where.=' and user_id in (SELECT user_id FROM user_data WHERE photo_salt is not NULL)';
            $where.=' and ("for">'.$for.' or user_id in (SELECT id FROM user_auth WHERE credentials like '."'%editor%'))";
            $row=db::get_row('SELECT id FROM ' . $this->table_name . ' WHERE user_id = :value and type=:type ORDER by id DESC LIMIT 1', array('value' => 5,'type' => $type));
            $sql = 'SELECT id
            FROM ' . $this->table_name . '
            WHERE visible = true '.
                            $where.'
            ORDER BY created_ts DESC LIMIT 1000';
            $result=db::get_cols($sql, array(),$this->connection_name, array('posts_rated_'.$type, 60*5));
//            */
//                if (session::get_user_id()==29) echo $sql;
//                array_unshift($result, $row['id']);

        return array_unique($result);
    }

    public function get_by_tag($tag_id, $type = self::TYPE_MIND_POST)
    {
        $sql = '
		SELECT p.id
		FROM ' . $this->table_name . ' p
		JOIN ' . blogs_posts_tags_peer::instance()->get_table_name() . ' pt ON (pt.post_id = p.id)
		WHERE pt.tag_id = :tag_id AND p.visible = true
		GROUP BY p.id
		ORDER BY id DESC
		LIMIT 50';

        return db::get_cols($sql, array('tag_id' => $tag_id)); //, $this->connection_name, array('posts_by_tag' . $tag_id, 60*30)
    }

    public function get_similar($id, $limit = 5, $type = self::TYPE_MIND_POST)
    {
        $sql = '
		SELECT p.post_id
		FROM ' . blogs_posts_tags_peer::instance()->get_table_name() . ' p
		WHERE p.tag_id IN ( SELECT tag_id FROM ' . blogs_posts_tags_peer::instance()->get_table_name() . ' pt WHERE pt.post_id = :id ) AND  p.post_id in (SELECT id FROM blogs_posts WHERE type=:type)
		GROUP BY p.post_id
		ORDER BY p.post_id DESC
		LIMIT :limit';

        return db::get_cols($sql, array('id' => $id, 'type' => $type, 'limit' => $limit), $this->connection_name, array('posts_similar' . $id, 60 * 60 * 24));
    }

    // unusefull method?
    public function get_newest($type = self::TYPE_MIND_POST, $limit = false)
    {
        if ($limit > 0) $limit = 'LIMIT ' . $limit;
        if ($type == self::TYPE_MIND_POST) {
            $typestr = ' AND (type = ' . self::TYPE_MIND_POST . ' OR type = ' . self::TYPE_PROGRAMA_POST . ') ';
        } else {
            $typestr = ' AND type = ' . $type . ' ';
        }
        //$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE type = :type AND visible = true ORDER BY id DESC '.$limit;
        //return db::get_cols($sql, array('type' => $type), $this->connection_name, array('posts_newest' . $type, 60*2));
        $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE visible = true ' . $typestr . ' ORDER BY id DESC ' . $limit;
        return db::get_cols($sql, array(), $this->connection_name, array('posts_newest' . $type, 60 * 2));
    }

    public function get_news_posts()
    {
        //$sql_favorite = 'SELECT id FROM ' . $this->table_name . ' WHERE mission_type = :type AND visible = true AND favorite= true ORDER BY id DESC LIMIT 3';
        $sql_all = 'SELECT id FROM ' . $this->table_name . ' WHERE mission_type = :type AND visible = true ORDER BY id DESC LIMIT 100';
        //AND favorite= false
        /*return array_merge(
                        db::get_cols($sql_favorite, array('type' => self::TYPE_NEWS_POST), $this->connection_name, array('news_posts_favorite', 60*2)),
                        db::get_cols($sql_all, array('type' => self::TYPE_NEWS_POST), $this->connection_name, array('news_posts', 60*2))
                        );*/
        return db::get_cols($sql_all, array('type' => self::TYPE_NEWS_POST), $this->connection_name, array('news_posts', 60 * 2));
    }

    public function get_declarations_posts()
    {
        $sql_favorite = 'SELECT id FROM ' . $this->table_name . ' WHERE mission_type = :type AND visible = true AND favorite= true ORDER BY id DESC LIMIT 3';
        $sql_all = 'SELECT id FROM ' . $this->table_name . ' WHERE mission_type = :type AND visible = true AND favorite= false ORDER BY id DESC LIMIT 100';
        return array_merge(
            db::get_cols($sql_favorite, array('type' => self::TYPE_DECLARATION_POST), $this->connection_name, array('declarations_posts_favorite', 60 * 2)),
            db::get_cols($sql_all, array('type' => self::TYPE_DECLARATION_POST), $this->connection_name, array('declarations_posts', 60 * 2))
        );
    }

    public function get_favorites($user_id = 0)
    {
        $types = array(
            self::TYPE_BLOG_POST,
            self::TYPE_GROUP_POST,
            self::TYPE_MIND_POST,
        );
        $sql = 'SELECT oid FROM bookmarks WHERE type = 6 AND user_id = ' . $user_id . ' ORDER BY id DESC LIMIT 100';
        $num = db::get_cols($sql, array(), $this->connection_name);
        if (count($num) > 0) {
            $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE user_id IN (' . implode(',', $num) . ')  AND visible = true ORDER BY id DESC LIMIT 100';
            return db::get_cols($sql, array(), $this->connection_name);
        } else return array();
    }

    public function get_discussed($type = self::TYPE_MIND_POST)
    {
        $sql =
            'SELECT b.id FROM ' . $this->table_name . ' b ' .
            'JOIN ' . blogs_comments_peer::instance()->get_table_name() . ' bc ON (bc.post_id = b.id) ' .
            'WHERE visible = true AND b.created_ts > :time AND b.type=:type
			GROUP BY b.id
			ORDER BY count(bc.id) DESC LIMIT 100';
        return db::get_cols($sql, array('time' => time() - 60 * 60 * 24 * 14, 'type' => $type), $this->connection_name);
    }

    public function has_rated($post_id, $user_id)
    {
        return db_key::i()->exists('blog_post_rate:' . $post_id . ':' . $user_id);
    }

    public function rate($post_id, $user_id)
    {
        db_key::i()->set('blog_post_rate:' . $post_id . ':' . $user_id, true);
    }

    public function delete_item($id)
    {
        blogs_posts_tags_peer::instance()->delete_by_post($id);
        $post = parent::get_item($id);
        load::model('feed/feed');
        feed_peer::instance()->clear_by_content($id, 1, $post['user_id']);
        parent::delete_item($id);
    }

    //TODO: доработать поиск по типу постов (думки, обговорення в спільнотах)
    public function search($keyword, $limit = 5)
    {
        $keyword = str_replace(' ', ' | ', $keyword);
        $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE fti @@ to_tsquery(\'russian\', :keyword) LIMIT :limit;';
        return db::get_cols($sql, array('keyword' => $keyword, 'limit' => $limit), $this->connection_name);
    }

    //простой и расширенный поиск с страницы блогов
    public function indexsearch($filters)
    {
        if (count($filters) > 0) {
            $where = "";
            if ($filters['user_id']) {
                $where .= " AND user_id = :user_id ";
                $bind['user_id'] = $filters['user_id'];
            }
            if ($filters['sfera']) {
                $where .= " AND sfera = :sfera ";
                $bind['sfera'] = $filters['sfera'];
            }
            if ($filters['fast']) {
                $where .= " AND title ILIKE :fast OR fti @@ to_tsquery('russian', :rfast) ";
                $bind['fast'] = '%' . $filters['fast'] . '%';
                $bind['rfast'] = str_replace(' ', ' & ', trim($filters['fast']));
            }
            if ($filters['name']) {
                $where .= " AND title ILIKE :name ";
                $bind['name'] = '%' . $filters['name'] . '%';
            }
            if ($filters['text']) {
                $where .= " AND fti @@ to_tsquery('russian', :text) ";
                $bind['text'] = str_replace(' ', ' & ', trim($filters['text']));
            }
            if ($filters['start']) {
                $where .= " AND created_ts >= :start ";
                $bind['start'] = $filters['start'];
            }
            if ($filters['end']) {
                $where .= " AND created_ts <= :end ";
                $bind['end'] = $filters['end'];
            }
            if (!session::has_credential('admin')) {
                $groups = db::get_cols("SELECT group_id FROM groups_members WHERE user_id = " . session::get_user_id());
                $groups[] = 0;
                $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE group_id IN (' . implode(',', $groups) . ') ' . $where . ' ORDER BY created_ts DESC';
            } else
                $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE 1=1 ' . $where . ' ORDER BY created_ts DESC';
            return db::get_cols($sql, $bind, $this->connection_name);
        }
        return array();
    }

    public function reindex($id)
    {
        $index_columns = array('title', 'body', 'tags_text');
        $index_expr = 'coalesce(' . implode(',\'\') ||\' \'|| coalesce(', $index_columns) . ',\'\')';

        db::exec(
            'UPDATE ' . $this->table_name . ' SET fti = to_tsvector(\'russian\', ' . $index_expr . ') WHERE id = :id',
            array('id' => $id)
        );
    }

    public function update($data, $keys = null)
    {
        parent::update($data, $keys);
        $this->reindex($data[$this->primary_key]);
    }

    public function insert($data, $ignore_duplicate = false, $type = self::TYPE_MIND_POST)
    {
        if (!$data['type']) $data['type'] = $type;
        $id = parent::insert($data, $ignore_duplicate);
        $this->reindex($id);

        return $id;
    }

    public function clean_text($text)
    {

        $pattern_mso = "/<!--\[if !mso\]>.*<!\[endif\]-->/sU";
        $pattern_gte_mso = "/<!--\[if gte mso.*<!\[endif\]-->/sU";
        @preg_replace($pattern_mso, '', $text, -1, $count);
        @preg_replace($pattern_gte_mso, '', $text, -1, $count);

        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/Bootstrap.php';
        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier.includes.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/ConfigSchema.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/PropertyList.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/VarParser.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/VarParser/Flexible.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/Strategy.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/Strategy/Composite.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier/Strategy/Core.php';
//        require_once __DIR__.'/../../../lib/purifier/HTMLPurifier.php';
        $config = HTMLPurifier_Config::createDefault();
        $config->set("HTML", "SafeEmbed", true);
        $config->set("HTML", "SafeObject", true);
        $config->set('HTML', 'Allowed', 'a[href|title|target],br,p[style|align],span[style],em,i,b,u,ul,ol,li,strong,p,table,tr,td,img[src|width|height|style|align],embed,object[type|width|height|data],param[name|value]');
        $HTMLpurifier = new HTMLPurifier($config);
        return $HTMLpurifier->purify($text, $config);
    }

    public function namize_text($text, &$users)
    {
        return names_extractor::process($text, $users);
    }

    public function get_by_groups($groups = array(), $limit = 1)
    {
        return false;
        //return parent::get_list(array('group_id' => $group_id, 'type'=>self::TYPE_GROUP_POST));
    }


    public function get_by_group($group_id, $mpu = 0)
    {
        return parent::get_list(array('group_id' => $group_id, 'visible' => true, 'mpu' => intval($mpu)));//, 'type'=>self::TYPE_GROUP_POST));
    }

    public function get_by_ppo($ppo_id)
    {
        return parent::get_list(array('ppo_id' => $ppo_id, 'visible' => true, 'type' => self::TYPE_GROUP_POST));
    }

    public function get_by_team($team_id)
    {
        return parent::get_list(array('team_id' => $team_id, 'visible' => true, 'type' => self::TYPE_GROUP_POST));
    }

    public function get_last_form_group($group_id, $time = false)
    {
        /*    $time ? $time_passed=$time : $time_passed=12*3600;
           $sql="SELECT id FROM blogs_posts WHERE type=".blogs_posts_peer::TYPE_GROUP_POST." AND group_id=".$group_id." AND visible=true  AND created_ts=".(time()-$time_passed)." ORDER by id DESC";
   $post_id=db::get_scalar($sql);
           if ($post_id) return parent::get_item($post_id);
           else
           {
               //SELECT id, ("for"-("against"*1.5)) as raiting  FROM blogs_posts ORDER BY ("for"-("against"*1.5)) DESC

           }
       */
        //Реально последний пост
        $sql = "SELECT id FROM blogs_posts WHERE type=" . blogs_posts_peer::TYPE_GROUP_POST . " AND group_id=" . $group_id . " AND visible=true ORDER by id DESC";
        $post_id = db::get_scalar($sql);
        if ($post_id) return parent::get_item($post_id);
        else return false;
    }

    public function get_by_users($users, $type = array(self::TYPE_BLOG_POST, self::TYPE_MIND_POST, self::TYPE_GROUP_POST))
    {
        if (count($users) > 0) {
            if (in_array(blogs_posts_peer::TYPE_GROUP_POST, $type)) {
                $type = array_diff($type, array(blogs_posts_peer::TYPE_GROUP_POST));
                {
                    load::model('groups/groups');
                    $allowed_groups = groups_peer::instance()->get_new();
                    $sql = "or (type=5 AND group_id in (" . implode(',', $allowed_groups) . "))";
                }
            }
            return db::get_cols('SELECT id FROM ' . $this->table_name . ' WHERE (type IN (' . implode(',', $type) . ') ' . $sql . ') AND user_id IN (' . implode(',', $users) . ')  ORDER by id DESC');

        } else {
            return array();
        }
    }

    public function get_all_by_users($users)
    {
        if (count($users) > 0)
            return db::get_cols('SELECT id FROM ' . $this->table_name . ' WHERE user_id IN (' . implode(',', $users) . ')');
        else
            return array();
    }

    public function add_view($post_id, $user_id)
    {
        //(!$user_id && session::get_user_id())  ? $user_id=session::get_user_id() : $user_id=0;

        if (!$user_id) {
            return false;
        }

        if ($users = db::get_row("SELECT users FROM blogs_posts_views WHERE post_id=" . $post_id)) {
            db::exec("UPDATE blogs_posts_views SET users='" . $users['users'] . "," . $user_id . "' WHERE post_id=" . $post_id);
        } else {
            db::exec('INSERT INTO blogs_posts_views ("post_id", "users") VALUES (' . $post_id . ', ' . $user_id . ')');
        }

        if ($posts = db::get_row("SELECT posts FROM blogs_users_views WHERE user_id=" . $user_id)) {
            db::exec("UPDATE blogs_users_views SET posts='" . $posts['posts'] . "," . $post_id . "' WHERE user_id=" . $user_id);
        } else {
            db::exec('INSERT INTO blogs_users_views ("user_id", "posts") VALUES (' . $user_id . ', ' . $post_id . ')');
        }
    }

    public function get_not_viewed()
    {
        if (!session::has_credential('redcollegiant')) return array();
        else {
            return db::get_cols("SELECT id FROM blogs_posts WHERE type=1 AND created_ts>1312200000 AND id NOT IN (SELECT post_id FROM blogs_views WHERE user_id=" . session::get_user_id() . ") ORDER BY id DESC");
        }
    }

    public function get_not_viewed_by_user($user_id, $filter, $from_redis = false)
    {

        $posts = db::get_cols(
            "SELECT id FROM blogs_posts WHERE user_id IN (" . implode(",", $filter) . ") AND visible = TRUE AND type = 1 AND created_ts > " . mktime(0, 0, 0, 3, 1, 2011) . " ORDER BY id DESC",
            array(),
            null,
            array(get_class($this) . ".posts", 60 * 60)
        );

        if (!$from_redis) {
            $sql = "SELECT posts FROM blogs_users_views WHERE user_id=" . (int)$user_id;
            $viewed = db::get_row($sql);
            $viewed = explode(",", $viewed["posts"]);
        } else {
            $viewed = array();
            foreach ($posts as $post) {
                if (db_key::i()->exists('post_viewed:' . $post . ':' . $user_id))
                    $viewed[] = $post;
            }
        }

        return array_diff($posts, $viewed);

    }
}
