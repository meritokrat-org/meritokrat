<?

load::model('program_quotes');
load::model('user/user_voter');

class profile_index_action extends frontend_controller
{
    #protected $authorized_access = true;

    public function execute()
    {
//            rating_helper::calculateRating(5968);
        load::model('lists/lists');
        load::model('lists/lists_users');
        load::model('ban/ban');
        load::model('ppo/ppo');
        load::model('ppo/members');

        $this->invited = user_auth_peer::instance()->get_invited_by_id(request::get_int('id', session::get_user_id()));
        $this->hiden_inviter = user_auth_peer::instance()->get_invited_by_id(request::get_int('id', session::get_user_id()), true);

        $this->sortable_list = user_auth_peer::instance()->get_sortable_list();

        $this->user = user_auth_peer::instance()->get_item(request::get_int('id') ? request::get_int('id') : session::get_user_id());
        load::model('user/user_desktop');

        //if(session::get_user_id()==1360)die(print_r($this->user));


        if ((!session::is_authenticated() || !session::has_credential("admin")) && $this->user["del"] > 0) {
            $this->redirect("/people");
        }

        load::model('user/user_data');
        $this->user_data = user_data_peer::instance()->get_item($this->user['id']);

        if (session::get_user_id()) {
            $this->is_regional_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id(), true);
            $this->is_raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id(), true);

            if (
                session::has_credential('admin')
                OR
                (!strpos($this->user['credentials'], 'admin') && $this->user_data['region_id'] && is_array($this->is_regional_coordinator) && in_array($this->user_data['region_id'], $this->is_regional_coordinator))
                OR
                (!strpos($this->user['credentials'], 'admin') && $this->user_data['city_id'] && is_array($this->is_raion_coordinator) && in_array($this->user_data['city_id'], $this->is_raion_coordinator))
            ) {
                $this->access = 1;
            }
        }

        if (!$this->user
            || (!session::has_credential('admin')
                && !user_auth_peer::instance()->get_profile_access($this->user['suslik'], $this->user['id'], $this->user_data['share_users'])
                && $this->user['id'] != session::get_user_id())
        ) {
            throw new public_exception('Пользователь не найден'); #чето напортачили с условиями
        }

        if ($this->user['suslik'] && !session::has_credential('admin')) {
            throw new public_exception('Пользователь не найден');
        }

        if (!$this->user['active'] and !session::has_credential('admin')
            and (count($this->is_regional_coordinator) == 0 or !in_array($this->user_data['region_id'], $this->is_regional_coordinator))
            and (count($this->is_raion_coordinator) == 0 or !in_array($this->user_data['city_id'], $this->is_raion_coordinator))
            and (!$this->user['offline'] or $this->user['offline'] != session::get_user_id())
            and ($this->user['id'] != session::get_user_id())
            and (!user_auth_peer::instance()->is_user_inviter($this->user['id']))
        ) {
            throw new public_exception(t('Пользователь не найден'));
        }

        $this->is_predstavitel_vnz = db::get_scalar("SELECT user_id FROM user_desktop WHERE functions && '{14}' AND user_id=:uid", array('uid' => session::get_user_id()));
        $this->ppo_leaders = ppo_peer::instance()->leaders_by_user_data($this->user_data);

        if (session::has_credential('admin')
            OR (count($this->is_regional_coordinator) > 0 AND in_array($this->user_data['region_id'], $this->is_regional_coordinator))
            OR (count($this->is_raion_coordinator) > 0 AND in_array($this->user_data['city_id'], $this->is_raion_coordinator))
            OR ($this->is_predstavitel_vnz)
            OR (in_array(session::get_user_id(), $this->ppo_leaders) AND $this->user['id'] != session::get_user_id())
        ) {
            $this->show_info = true;
        }

        $leader_ppo_id = db::get_scalar('SELECT id FROM ppo WHERE active=1 AND id 
                    IN (SELECT group_id 
                    FROM ppo_members 
                    WHERE user_id=:user_id AND function IN (1,2) 
                    ORDER BY function DESC)
										AND category = (SELECT MAX(category) 
										FROM ppo 
										WHERE id IN (SELECT group_id 
                    FROM ppo_members 
                    WHERE user_id=:user_id AND function IN (1,2) 
                    ORDER BY function DESC))', array('user_id' => session::get_user_id()));

        if ($leader_ppo_id > 0) {
            $leader_ppo = ppo_peer::instance()->get_item($leader_ppo_id);
            $this->leader_ppo = $leader_ppo;
            $this->leader_ppo_category = $leader_ppo['category'];
            $children_members = ppo_members_peer::instance()->get_members($leader_ppo['id'], false, $leader_ppo);
            if (in_array($this->user['id'], $children_members)) {
                if (!$this->show_info)
                    $this->show_contact_info = true;
                $this->show_info = true;
            }
        }
        load::model('user/user_desktop');
        //

        $region_coordinator = $this->is_regional_coordinator;
        $raion_coordinator = $this->is_raion_coordinator;
        $ppo_leader = ppo_members_peer::instance()->ppo_info(session::get_user_id());
        if ($region_coordinator && count($region_coordinator) > 0) {
            $this->rk_access = 'region';
            $this->region = $region_coordinator;
        } elseif ($raion_coordinator && count($raion_coordinator) > 0) {
            $this->rk_access = 'city';
            $this->city = $raion_coordinator;
        } elseif ($ppo_leader && count($ppo_leader) > 0) {
            if ($ppo_leader[0]) {
                $this->rk_access = 'region';
                $this->region = $ppo_leader[0];
            } elseif ($ppo_leader[1]) {
                $this->rk_access = 'city';
                $this->city = $ppo_leader[1];
            }
        }

        if ($this->rk_access || session::has_credential('admin')) {
            load::model('user/user_view');

            $not_viewed_users = user_view_peer::instance()->get_new();
            $this->not_viewed_users = count($not_viewed_users);

            $not_viewed_additional = user_view_peer::instance()->get_not_viewed(session::get_user_id());
            $this->not_viewed_additional = count($not_viewed_additional);
        }


        if ($num = mem_cache::i()->get('reestr_' . session::get_user_id() . '_newz')) {
            $this->reestr_num = $num;
        } else {
            $reestr = user_auth_peer::instance()->get_reestr($this->region, $this->city, $ppo_leader, db_key::i()->get('user_' . session::get_user_id() . '_viewreestr_time'));

            $num = count($reestr);

            mem_cache::i()->set('reestr_' . session::get_user_id() . '_newz', $num, 60 * 5);
            $this->reestr_num = $num;
        }

        if ($num = mem_cache::i()->get('zayava_' . session::get_user_id() . '_newz')) {
            $this->zayava_num = $num;
        } else {
            load::model('user/zayava');
            $zayava = user_zayava_peer::instance()->get_by_status(0, false, $this->region, $this->city, db_key::i()->get('user_' . session::get_user_id() . '_viewzayava_time'));

            $num = count($zayava);

            mem_cache::i()->set('zayava_' . session::get_user_id() . '_newz', $num, 60 * 5);
            $this->zayava_num = $num;
        }

        if ($num = mem_cache::i()->get('pay_' . session::get_user_id() . '_newz')) {
            $this->pay_num = $num;
        } else {
            $pay = user_auth_peer::instance()->get_reestr_payments($this->region, $this->city, $ppo_leader, db_key::i()->get('user_' . session::get_user_id() . '_viewpay_time'));

            $num = count($pay);

            mem_cache::i()->set('pay_' . session::get_user_id() . '_newz', $num, 60 * 5);
            $this->pay_num = $num;
        }

        if (request::get("act") == "getMiniDesktopData") {

            header("Content-Type: text/plain; charset=UTF-8");

            $arr = array(
                'pay_num' => $this->pay_num,
                'zayava_num' => $this->zayava_num,
                'reestr_num' => $this->reestr_num,
                'not_viewed_additional' => $this->not_viewed_additional,
                'not_viewed_users' => $this->not_viewed_users,
                'rk_access' => $this->rk_access,
                'leader_ppo_id' => $leader_ppo['id'],
                'leader_ppo_number' => $leader_ppo['number']
            );
            echo json_encode($arr);
            exit();
        }

        //RATING
        $this->rating_types = rating_helper::get_costs();
        $this->ratingData = user_rating_peer::instance()->get_item($this->user['id']);
        $this->rating_by_user = rating_helper::calculate_by_all_users($this->user['id']);
        $this->ap_data = rating_helper::get_admin_points($this->user['id']);
        $this->ap_sum = rating_helper::get_admin_points($this->user['id'], true);
//                echo "<pre>";
//                var_dump($this->rating_by_user);
//                exit;
        //END


        $this->user_desktop = db::get_row("SELECT * FROM user_desktop WHERE user_id=:user_id", array('user_id' => $this->user['id']));

        $this->user_desktop['offline_count'] = db::get_scalar("SELECT COUNT(id) FROM user_auth WHERE offline = " . $this->user['id']);

        load::model('user/user_education');
        $this->user_education = user_education_peer::instance()->get_item($this->user_data['user_id']);

        load::model('user/user_work');
        $this->user_work = user_work_peer::instance()->get_item($this->user_data['user_id']);

        /**
         * new algorithm of user's works
         */
        $this->user_works = db::get_rows(
            'SELECT * FROM user_works WHERE user_id = :user_id ORDER BY date_finish ASC',
            ["user_id" => $this->user_data['user_id']]
        );

        load::model('user/user_bio');
        $this->user_bio = user_bio_peer::instance()->get_item($this->user_data['user_id']);
        if ($this->show_info) {
            load::model('user/user_shevchenko_data');
            $this->user_info = user_shevchenko_data_peer::instance()->get_item($this->user['id']);

            load::model('user/user_contact');
            if (($this->is_predstavitel_vnz OR count($this->is_raion_coordinator) > 0) && $leader_ppo['category'] < 2) {
                $this->user_contact = user_contact_peer::instance()->get_user_by_contacter($this->user['id'], session::get_user_id());
            } elseif (count($this->is_regional_coordinator) > 0 || $leader_ppo['category'] > 1) {
                $frst = user_contact_peer::instance()->get_user_by_contacter($this->user['id'], array(), 4);
                $scnd = user_contact_peer::instance()->get_user_by_contacter($this->user['id'], session::get_user_id());
                $this->user_contact = array_merge($scnd, $frst);
            } else {
                $this->user_contact = user_contact_peer::instance()->get_user_by_contacter($this->user['id']);
            }
            $this->user_contact = array_unique($this->user_contact);
            load::model('user/user_novasys_data');
            $this->user_novasys = user_novasys_data_peer::instance()->get_item($this->user['id']);
        }

        load::model('user/user_work_party');
        load::model('user/user_work_election');
        load::model('user/user_work_action');
        $this->work_party = $this->check_empty_array(user_work_party_peer::instance()->get_user($this->user['id']));
        $this->work_election = $this->check_empty_array(user_work_election_peer::instance()->get_user($this->user['id']));
        $this->work_action = $this->check_empty_array(user_work_action_peer::instance()->get_user($this->user['id']));

        $title = ''; //user_auth_peer::get_type($this->user['type']) . ' ';
        $title .= stripslashes(htmlspecialchars($this->user_data['first_name'] . ' ' . $this->user_data['last_name'])) . '. | ' . conf::get('project_name');
        client_helper::set_title($title);

        client_helper::set_meta(array(
            'name' => 'description',
            'content' => $title
        ));
        client_helper::set_meta(array(
            'name' => 'keywords',
            'content' => $title
        ));

        client_helper::register_variable('profileId', $this->user_data['user_id']);

        load::view_helper('user');

        load::model('candidates/candidates');
        $this->candidate = candidates_peer::instance()->get_item($this->user_data['user_id']);

        load::model('ideas/ideas');
        $list = ideas_peer::instance()->get_by_user($this->user['id']);
        $this->ideas_list = array_slice($list, 0, 7);

        load::model('blogs/posts');
        $list = blogs_posts_peer::instance()->get_by_users(array($this->user['id']));
        $this->count_blog_posts = count($list);
        $this->blog_list = array_slice($list, 0, 7);
        load::model('blogs/posts');

        load::model('groups/groups');
        $this->allowed_groups = groups_peer::instance()->get_new();

        $list = blogs_posts_peer::instance()->get_by_user($this->user['id'], blogs_posts_peer::TYPE_GROUP_POST, $this->allowed_groups);
        $this->count_groups_post = count($list);
        $this->groups_post_list = array_slice($list, 0, 7);

        $list = blogs_posts_peer::instance()->get_by_user($this->user['id'], blogs_posts_peer::TYPE_NOTATE_POST);
        $this->count_note_post = count($list);
        $this->notate_list = array_slice($list, 0, 7);

        $list = blogs_posts_peer::instance()->get_by_user($this->user['id'], blogs_posts_peer::TYPE_ARCHIVE_POST);
        $this->count_archive_post = count($list);
        $this->archive_list = array_slice($list, 0, 7);

        load::model('blogs/comments');
        $list = blogs_comments_peer::instance()->get_by_users(array($this->user['id']), false, array(blogs_posts_peer::TYPE_MIND_POST, blogs_posts_peer::TYPE_BLOG_POST, blogs_posts_peer::TYPE_GROUP_POST), $this->allowed_groups);
        arsort($list);
        $this->count_coms = count($list);
        $this->com_list = array_slice($list, 0, 5);


        load::model('polls/polls');
        load::model('polls/answers');
        load::model('polls/votes');

        $polls = polls_peer::instance()->get_by_user($this->user['id']);
        $this->polls = array_slice($polls, 0, 7);

        load::model('debates/debates');
        load::model('debates/arguments');

        $debates = debates_peer::instance()->get_by_user($this->user['id']);
        $this->debates = array_slice($debates, 0, 3);

        load::model('parties/members');
        load::model('parties/parties');
        load::view_helper('party');

        if ($this->party_id = parties_members_peer::instance()->get($this->user['id'])) {
            $this->party = parties_peer::instance()->get_item($this->party_id);
        }

        $this->have_trusted = user_data_peer::instance()->has_trusted($this->user_data['user_id'], session::get_user_id());
        $this->my_trust = user_data_peer::instance()->my_trust($this->user_data['user_id'], session::get_user_id());

        load::model('user/questions');
        $this->set_slot('context', 'partials/questions');
        if ($this->questions = user_questions_peer::instance()->get_by_profile($this->user['id'])) {
            $this->questions = array_slice($this->questions, 0, 5);
        }

        load::model('groups/members');
        if ($this->user_groups = groups_members_peer::instance()->get_groups($this->user['id'])) {
            load::model('groups/groups');
            load::view_helper('group');
            shuffle($this->user_groups);
        }

        //$this->count_blog_posts=db::get_scalar('SELECT count(id) FROM blogs_posts WHERE user_id=:user_id', array('user_id'=>$this->user['id']));
        $this->count_polls = db::get_scalar('SELECT count(id) FROM polls WHERE user_id=:user_id', array('user_id' => $this->user['id']));


        load::model('user/blacklist');

        load::model('friends/friends');
        if ((friends_peer::instance()->is_friend(session::get_user_id(), $this->user['id'])) || (session::has_credential('admin'))) {
            if ($this->friends = friends_peer::instance()->get_by_user($this->user['id'])) {
                shuffle($this->friends);
            }
            $this->shared_friends = friends_peer::instance()->shared_friends(session::get_user_id(), $this->user['id']);
        }

        /* load::model('library/files');
          $this->files = library_files_peer::instance()->get_list(array('object_id'=>$this->user['id'],"type"=>1),
          array(),array(),10); */
        load::model('library/files');
        load::model('library/files_dirs');


        $this->dirs_tree = $this->get_child_dirs(0);
        $this->dirs_tree[0] = "";

        $this->dirs = library_files_dirs_peer::instance()->get_list(
            array("object_id" => $this->user['id'], "type" => 1), array(), array('position ASC'));

        $this->dirs_lists = array(0 => t('немає'));
        foreach ($this->dirs as $id) {
            $dir = library_files_dirs_peer::instance()->get_item($id);
            $this->dirs_lists[$id] = stripslashes($dir['title']);
            $this->files[$id] = library_files_peer::instance()->get_list(
                array('dir_id' => $id, "object_id" => $this->user['id'], "type" => 1), array(), array('position ASC'));
        }
        $this->files[0] = library_files_peer::instance()->get_list(
            array('dir_id' => 0, "object_id" => $this->user['id'], "type" => 1), array(), array('position ASC'));
        $this->dirs[] = 0;
        $this->last_parent_dir = db::get_scalar("SELECT id FROM files_dirs  WHERE parent_id=0
                    AND type=1
                    AND object_id=" . $this->user['id'] . " ORDER BY position DESC LIMIT 1");


        load::view_helper('photo');
        load::model('photo/photo');
        $this->photos = photo_peer::instance()->get_by_obj($this->user['id'], 1);
        $this->photos = array_slice($this->photos, 0, 3);

        load::model('user/zayava');
        $zay = user_zayava_peer::instance()->get_user($this->user['id']);
        $this->zayava = intval($zay[0]);

        load::model('user/user_agitmaterials');
        $this->agitation = user_agitmaterials_peer::instance()->get_user_stat($this->user['id']);

        if (session::is_authenticated()) {
            feed_peer::reset_user_flag(session::get_user_id());

            $this->feed_list = feed_peer::instance()->get_by_user($this->user['id']);
            $this->feed_list = array_slice($this->feed_list, 0, 15);

            load::model('blogs/posts');
            $this->posts_id = blogs_posts_peer::instance()->get_not_viewed_by_user($this->user['id'], array(5, 31));
        }
        /*
                load::action_helper('pager', true);

                $this->pager = pager_helper::get_pager($this->blogsList, request::get_int('page'), 10);
                $this->blogsList = $this->pager->get_list();
        */
    }

    public function get_child_dirs($dir_id, $recursion = true)
    {
        $dirs = library_files_dirs_peer::instance()->get_list(array('parent_id' => $dir_id, "type" => 1, "object_id" => $this->user['id']), array(), array('position ASC'));
        if (!$recursion)
            return $dirs;
        if (!is_array($dirs))
            return false;
        else {
            foreach ($dirs as $dir) {
                $all_dirs[$dir] = $this->get_child_dirs($dir);
            }
        }
        return $all_dirs;
    }

    protected function check_empty_array($array)
    {
        if (!is_array($array) || count($array) == 0)
            $array = array('');
        return $array;
    }

}