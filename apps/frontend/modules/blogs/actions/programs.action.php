<?

load::app('modules/blogs/controller');
class blogs_programs_action extends blogs_controller
{
	public function execute()
	{
		load::view_helper('tag', true);
                load::model('blogs/programs');
                load::model('blogs/targets');
                if(request::get('bookmark'))
                {
                    load::model('bookmarks/bookmarks');
                }
                client_helper::set_title(t('Публикации'));

                if(!session::is_authenticated())
                {
                    $public = ''; // ' AND public = true ';
                    $pub = ''; // ' AND b.public = true ';
				}

                if(!session::has_credential('admin'))
                {
                    $public .= ' AND visible = true ';
                    $pub .= ' AND b.visible = true ';
                }
                $this->themes = blogs_programs_peer::instance()->theme_list($pub);
                $this->targets = blogs_targets_peer::instance()->target_list($pub);
                $this->mputotal = db::get_scalar('SELECT COUNT(*) FROM blogs_posts
                    WHERE mpu = 1 AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public);
                $this->positiontotal = db::get_scalar('SELECT COUNT(*) FROM blogs_posts
                    WHERE mpu = 2 AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public);
                /*$this->talk = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_comments c
                    WHERE c.post_id = b.id '.$pub.' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                    ORDER BY c.created_ts DESC LIMIT 5');*/
                $this->talk = db::get_cols('SELECT post_id FROM
                    (SELECT DISTINCT ON(c.post_id) c.post_id,c.created_ts FROM blogs_comments c, blogs_posts b
                    WHERE c.post_id = b.id '.$pub.' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.') foo
                    ORDER BY created_ts DESC LIMIT 5');

                if(request::get_int('target'))
                {
                    $this->position = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_targets p
                        WHERE p.blog_id = b.id '.$pub.' AND b.mpu = 2 AND p.target_id = '.request::get_int('target').' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                        ORDER BY b.edit_ts DESC');
                    $this->mpu = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_targets p
                        WHERE p.blog_id = b.id '.$pub.' AND b.mpu = 1 AND p.target_id = '.request::get_int('target').' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                        ORDER BY b.edit_ts DESC');
                    $this->list = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_targets p
                        WHERE p.blog_id = b.id '.$pub.' AND b.mpu = 0 AND p.target_id = '.request::get_int('target').' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                        ORDER BY b.created_ts DESC');
                    $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
                    $this->list = $this->pager->get_list();
                }
                elseif(request::get('theme')!='mpu' && request::get('theme')!='position' && !request::get_int('theme'))
                {
                    if(request::get('type')=='populars')
                    {
                        $this->list = db::get_cols('SELECT id FROM blogs_posts
                            WHERE type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public.'
                            ORDER BY views DESC');
                        $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
                        $this->list = $this->pager->get_list();
                    }
                    elseif(request::get('type')=='last')
                    {
                        $this->list = db::get_cols('SELECT id FROM blogs_posts
                            WHERE mpu = 0 AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public.'
                            ORDER BY created_ts DESC');
                        $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
                        $this->list = $this->pager->get_list();
                    }
                    else
                    {
                        $this->popular = db::get_cols('SELECT id FROM blogs_posts
                            WHERE type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public.'
                            ORDER BY views DESC LIMIT 5');
                        $this->last = db::get_cols('SELECT id FROM blogs_posts
                            WHERE mpu = 0 AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.' '.$public.'
                            ORDER BY created_ts DESC LIMIT 5');
                        $this->list = db::get_cols('SELECT id FROM blogs_posts
                            WHERE mpu = 1 '.$public.' AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY created_ts DESC LIMIT 3');
                        $this->position = db::get_cols('SELECT id FROM blogs_posts
                            WHERE mpu = 2 '.$public.' AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY edit_ts DESC LIMIT 3');
                        $this->group = db::get_cols('SELECT post_id FROM
                            (SELECT DISTINCT ON(c.post_id) c.post_id,c.created_ts FROM blogs_comments c, blogs_posts b, groups g
                            WHERE c.post_id = b.id '.$pub.' AND b.type = '.blogs_posts_peer::TYPE_GROUP_POST.' AND b.group_id=g.id AND g.category=2 AND g.hidden=0) foo
                            ORDER BY created_ts DESC LIMIT 5');
                    }
                }
                else
                {
                    if(request::get('theme')=='mpu')
                    {
                        $this->list = db::get_cols('SELECT id FROM blogs_posts
                            WHERE mpu = 1 '.$public.' AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY edit_ts DESC');
                    }
                    elseif(request::get('theme')=='position')
                    {
                        $this->list = db::get_cols('SELECT id FROM blogs_posts
                            WHERE mpu = 2 '.$public.' AND type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY edit_ts DESC');
                    }
                    else
                    {
                        $this->position = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_programs p
                            WHERE p.blog_id = b.id '.$pub.' AND b.mpu = 2 AND p.program_id = '.request::get_int('theme').' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY b.edit_ts DESC');
                        $this->mpu = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_programs p
                            WHERE p.blog_id = b.id '.$pub.' AND b.mpu = 1 AND p.program_id = '.request::get_int('theme').' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY b.edit_ts DESC');
                        $this->list = db::get_cols('SELECT b.id FROM blogs_posts b, blogs_programs p
                            WHERE p.blog_id = b.id '.$pub.' AND b.mpu = 0 AND p.program_id = '.request::get_int('theme').' AND b.type = '.blogs_posts_peer::TYPE_PROGRAMA_POST.'
                            ORDER BY b.created_ts DESC');
                        $this->group = db::get_cols('SELECT b.id FROM blogs_posts b, groups g
                            WHERE b.type = '.blogs_posts_peer::TYPE_GROUP_POST.' '.$pub.' AND b.group_id=g.id AND g.hidden=0 AND g.category=2 AND g.type = '.request::get_int('theme').'
                            ORDER BY b.created_ts DESC LIMIT 5');
                        $this->group_data = db::get_row('SELECT * FROM groups WHERE category = 2 AND type = '.request::get_int('theme'));
                        $this->group_id = intval($this->group_data['id']);
                    }
                    $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
                    $this->list = $this->pager->get_list();
                }
	}
}
