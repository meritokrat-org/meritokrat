<?

class leadergroups_peer extends db_peer_postgre
{
	const PRIVACY_PUBLIC = 1;
	const PRIVACY_PRIVATE = 2;
	const PRIVACY_HIDDEN = 3;

	protected $table_name = 'leadergroups';

	/**
	 * @return leadergroups_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_peer' );
	}

	public static function get_types()
	{
		return array(
                         1 => t('Государственное управление'),
                         2 => t('Государственные финансы'),
                         3 => t('Управление государственным имуществом'),
                         4 => t('Региональная политика'),
                         5 => t('Местное самоуправление'),
                         6 => t('Международные отношения и дипломатия'),
                         7 => t('Межэтнические отношения'),
                         8 => t('Национальная безопасность'),
                         9 => t('Вооруженные силы, армия'),
                        10 => t('Конституционное право'),
                        11 => t('Избирательное право'),
                        12 => t('Право'),
                        13 => t('Судебная система'),
                        14 => t('Правоохранительная деятельность'),
                        15 => t('Экономика'),
                        16 => t('Предпринимательство'),
                        17 => t('Налоговая система'),
                        18 => t('Финансы и инвестиции'),
                        19 => t('Страхование'),
                        20 => t('Банковское дело'),
                        21 => t('Инновации и инвестиции в развитие'),
                        22 => t('Информационные технологии'),
                        23 => t('Энергетика'),
                        24 => t('ЖКХ'),
                        25 => t('Транспортная система'),
                        26 => t('Связь'),
                        27 => t('Градостроительство и архитектура'),
                        28 => t('Строительство'),
                        29 => t('Легкая промышленность'),
                        30 => t('Тяжелая промышленность'),
                        31 => t('Сельское хозяйство'),
                        32 => t('Развитие села'),
                        33 => t('Земельные вопросы'),
                        34 => t('Управление природными ресурсами'),
                        35 => t('Экология'),
                        36 => t('Здоровье'),
                        37 => t('Рекреация'),
                        38 => t('Безопасность продуктов и товаров'),
                        39 => t('Медицина и фармакология'),
                        40 => t('Молодежная политика'),
                        41 => t('Семья, материнство, детство'),
                        42 => t('Социальное обеспечение'),
                        43 => t('Пенсионная система'),
                        44 => t('СМИ'),
                        45 => t('Наука'),
                        46 => t('Образование'),
                        47 => t('Спорт'),
                        48 => t('Туризм'),
                        49 => t('Культура'),
                        50 => t('Общественная мораль'),
                        51 => t('Религия'),
                        52 => t('История'),
                        53 => t('Гражданское общество'),
                        53 => t('Самоорганизация'),
		);
		/*return array(
			1 => t('Общественная организация'),
			2 => t('Молодежная организация'),
			3 => t('Профсоюз'),
			4 => t('Благотворительная организация'),
			5 => t('Фонд'),
			6 => t('Группа по интересам')
		);*/
	}

	public static function get_type( $id )
	{
		$types = self::get_types();
		return $types[$id];
	}


	public static function get_categories()
	{
		return array(
			1 => t('По интересам'),
			2 => t('Разработка программы'),
		);
	}

	public static function get_category( $id )
	{
		$types = self::get_categories();
		return $types[$id];
	}

	public static function get_teritories()
	{
		return array(
			1 => t('Всеукраинская'),
			2 => t('Обласная'),
			3 => t('Районная в области'),
			4 => t('Городская'),
			5 => t('Районная в городе'),
			6 => t('Сельская')
		);
	}

	public static function get_teritory( $id )
	{
		$teritories = self::get_teritories();
		return $teritories[$id];
	}

	public function regenerate_photo_salt( $id )
	{
		$salt = substr(md5(microtime(true)), 0, 8);

		$this->update(array('photo_salt' => $salt, 'id' => $id));
		return $salt;
	}

	public function get_new( $limit = 500 )
	{
		$where['active'] = 1;
                $where['project'] = 0;
                $where['hidden'] = 0;
		return $this->get_list($where, array(), array('ID DESC'), $limit);
	}

	public function get_user_leadergroups( $user_id )
	{
		$leadergroups=db::get_cols("SELECT leadergroup_id FROM user_id WHERE user_id=:user_id", array('user_id'=>$user_id));
		return $leadergroups;
	}

	public function get_project_new( $limit = 500 )
	{
		$where['active'] = 1;
                $where['project'] = 1;
                $where['hidden'] = 0;
		return $this->get_list($where, array(), array('ID DESC'), $limit);
	}

	public function get_hot( $type = null, $teritory = null, $limit = 500 )
	{
		if (!session::has_credential('admin'))
                    {
                    $where['active'] = 1;
                    $where['hidden'] = 0;
                    }
		if ( $type )
		{
			$where['type'] = $type;
		}

		if ( $teritory )
		{
			$where['teritory'] = $teritory;
		}
                $where['project'] = 0;
		return $this->get_list($where, array(), array('rate DESC'), $limit);
	}

	public function get_by_members_colls()
	{
		return db::get_cols("SELECT leadergroups.id FROM leadergroups LEFT JOIN leadergroups_members ON (leadergroups.id = leadergroups_members.leadergroup_id) GROUP BY leadergroups.id ORDER BY count(leadergroups.id) DESC;");
	}

	public function update_rate( $id, $value, $user_id = null )
	{
		if ( !$data = $this->get_item($id) )
		{
			return;
		}

		if ( $user_id )
		{
			$value = $value * user_data_peer::instance()->get_rate_multiplier($user_id);
		}

		$this->update(array(
			'id' => $id,
			'rate' => $data['rate'] + $value
		));
	}

	public function search( $keyword, $limit = 5 )
	{
		$keyword = str_replace(' ', ' | ', $keyword);
		$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE fti @@ to_tsquery(\'russian\', :keyword) LIMIT :limit;';
		return db::get_cols($sql, array('keyword' => $keyword, 'limit' => $limit), $this->connection_name);
	}

	public function reindex( $id )
	{
		$index_columns = array('title');
		$index_expr = 'coalesce(' . implode(',\'\') ||\' \'|| coalesce(', $index_columns) . ',\'\')';

		db::exec(
			'UPDATE ' . $this->table_name . ' SET fti = to_tsvector(\'russian\', ' . $index_expr . ') WHERE id = :id',
			array('id' => $id)
		);
	}

	public function update( $data, $keys = null )
	{
		parent::update($data, $keys);
		$this->reindex($data[$this->primary_key]);
	}

	public function insert($data, $ignore_duplicate = false)
	{
		$id = parent::insert($data, $ignore_duplicate);
		$this->reindex($id);

		return $id;
	}

	public function get_moderators( $id )
	{
		return db_key::i()->exists('leadergroups_moderators:' . $id) ? unserialize(db_key::i()->get('leadergroups_moderators:' . $id)) : array();
	}

	public function is_moderator( $id, $user_id )
	{
		if ( session::has_credential('admin') )
		{
			return true;
		}

		$data = $this->get_item($id);
		if ( $data['user_id'] == $user_id )
		{
			return true;
		}

		return in_array($user_id, $this->get_moderators($id));
	}

	public function add_moderator( $id, $user_id )
	{
		$moderators = $this->get_moderators($id);
		$moderators[] = $user_id;
		$moderators = array_unique($moderators);
                $this->types = array(
			'blog_post' => t('Запись в блоге'),
			'debate' => t('Дебаты'),
			'poll' => t('Опрос'),
			'idea' => t('Идея'),
			'leadergroup' => t('Группа'),
			'party' => t('Партия'),
		);
                if (!session::has_credential('admin'))
                    {
                        $this->type='leadergroup';
                        $this->data=self::instance()->get_item($id);
                        ob_start();
                        include dirname(__FILE__) . '/../../../apps/frontend/modules/messages/views/partials/share/' . $this->type . '.php';
                        $this->html = ob_get_clean();

                        load::model('messages/messages');
                        messages_peer::instance()->add(array(
                                'sender_id' => 31,
                                'receiver_id' => $user_id,
                                'body' => t('Добрый день! Вы были назначены модератором соообщества:'),
                                'attached' => $this->html));

                        load::action_helper('user_email', false);
                        /*user_email_helper::send(
                                $user_id,
                                31,
                                array(
                                        'subject' => t('Новое сообщение'),
                                        'body' => '%sender% ' . t('пишет') . ':' . "\n\n" . t('Добрый день! Вы были назначены модератором соообщества:') . "\n\n" .
                                                          t('Что-бы ответить, перейдите по ссылке:') . "\n" .
                                                          'http://' . context::get('host') . '/messages/view?id=' . $id
                                )
                        );*/
                        $options = array(
                                    '%link%' =>  'http://' . context::get('host') . '/messages/view?id=' . $id
                                    );
                        user_email_helper::send_sys('groups_add_moderator',$user_id,31,$options);
                    }

                if (!leadergroups_members_peer::instance()->is_member($id, $user_id )) leadergroups_members_peer::instance()->add( $id, $user_id );

		db_key::i()->set('leadergroups_moderators:' . $id, serialize($moderators));
	}

	public function delete_moderator( $id, $user_id )
	{
		$moderators = $this->get_moderators($id);
		$moderators = array_diff($moderators, array($user_id));

		db_key::i()->set('leadergroups_moderators:' . $id, serialize($moderators));
	}

	public function get_select_list()
	{
		$list = $this->get_list();
		$select = array();
		foreach ( $list as $id )
		{
			$data = $this->get_item($id);
			$select[$id] = $data['title'];
		}
		return $select;
	}
}