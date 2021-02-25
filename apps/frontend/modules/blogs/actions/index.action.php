<?

load::app('modules/blogs/controller');

class blogs_index_action extends blogs_controller
{
    public function execute()
    {
        load::view_helper('tag', true);
        if (request::get('bookmark')) {
            load::model('bookmarks/bookmarks');
        }

        client_helper::set_title(t('Публикации'));
        if ($this->tag = trim(request::get('tag'))) {
            if ($tag_id = blogs_tags_peer::instance()->get_by_name($this->tag)) {
                $this->list = blogs_posts_peer::instance()->get_by_tag($tag_id);
                tag_helper::$rss = 'http://' . context::get('host') . '/blogs/rss?tag=' . urlencode(request::get('tag'));

                client_helper::set_title($this->tag . ' | ' . conf::get('project_name'));
            } else {
                $this->redirect('/blogs');
            }
        } elseif (request::get('submit')) {
            if (request::get_int('user_id')) $filters['user_id'] = request::get_int('user_id');
            if (request::get_int('sfera')) $filters['sfera'] = request::get_int('sfera');
            if (request::get_string('fast')) $filters['fast'] = addslashes(request::get_string('fast'));
            if (request::get_string('name')) $filters['name'] = addslashes(request::get_string('name'));
            if (request::get_string('text')) $filters['text'] = addslashes(request::get_string('text'));
            if (request::get_int('start_day') && request::get_int('start_month') && request::get_int('start_year')) $filters['start'] = user_helper::dateval('start');
            if (request::get_int('end_day') && request::get_int('end_month') && request::get_int('end_year')) $filters['end'] = user_helper::dateval('end');
            $this->list = blogs_posts_peer::instance()->indexsearch($filters);
        } else {
            if (request::get_int('type') == 1)
                $this->list = blogs_posts_peer::instance()->get_casted(blogs_posts_peer::TYPE_BLOG_POST);
            else
                $this->list = blogs_posts_peer::instance()->get_rated();
            tag_helper::$rss = 'http://' . context::get('host') . '/blogs/rss';
        }

        if (request::get("type") == "populars") {
            $list = array();
            foreach ($this->list as $id) {
                $item = blogs_posts_peer::instance()->get_item($id);
                if ($item["for"] > 20) {
                    $list[] = $id;
                }
            }
            $this->list = $list;
        }

        $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
        $this->list = $this->pager->get_list();


        //$this->new_posts = blogs_posts_peer::instance()->get_newest(0,9);
    }

    private function get_data($data_string)
    {
        $segments = explode('-', $data_string);
        return mktime(0, 0, 0, intval($segments[1]), intval($segments[0]), intval($segments[2]));
    }
}
