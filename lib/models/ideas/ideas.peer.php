<?

load::lib('purifier/HTMLPurifier.auto');
load::lib('text/names.extractor');

class ideas_peer extends db_peer_postgre
{
	protected $table_name = 'ideas';

	/**
	 * @return ideas_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ideas_peer' );
	}

	public static function get_segment_name( $id )
	{
		$list = self::get_segments();
		return $list[$id];
	}

	public static function get_segments()
	{
		return array(
			   1 => t('Идеология'),
			   2 => t('Партия'),
			  /* 1 => t('Национальная идея'),
			   2 => t('Президент'),
			   3 => t('ВРУ'),
			   4 => t('Кабинет министров'),
			   5 => t('Суд'),
			   6 => t('Органы власти'),
			   7 => t('Законодательство'),
			   8 => t('Наука и образование'),
			   9 => t('Внешняя политика'),
			  10 => t('Молодежная политика'),
			  11 => t('Социальная политика'),
			  12 => t('Кадровая политика'),
			  13 => t('Сельское хозяйство'),
			  14 => t('Медицина'),
			  15 => t('Антикоррупционная политика'),
			  16 => t('Инвестиционная политика'),
			  17 => t('Бизнес'),
			  18 => t('Транспорт'),
			  19 => t('Энергетика'),
			  20 => t('Промышленность'),
			  21 => t('Спорт'),
			  22 => t('Банковский сектор'),
			  23 => t('Регионы'),
			  24 => t('Экология'),
			  25 => t('ЖКХ'),
			  26 => t('Туризм и отдых'),
			  27 => t('Армия'),
			  28 => t('Пенсионнная система'),
			  29 => t('Духовная жизнь'),
			  30 => t('Культура'),
			  31 => t('Семья'),
			  32 => t('Финанси'),
			  33 => t('Экономика'),
			  34 => t('Связь'),
			  35 => t('Интернет'),
			  36 => t('Инновации'),
			  37 => t('Проблемы матери и ребенка'),
			  38 => t('Страхование'),
			  39 => t('Налоговая политика'),
			  40 => t('Культура'),
			  41 => t('Язык')*/
		);
	}

	public function get_by_user( $user_id )
	{
		return $this->get_list(array('user_id' => $user_id));
	}

	public function get_by_segment( $segment, $limit = 500)
	{
		$segment_id = array_keys(self::get_segments(), $segment);
		$segment_id = array_shift($segment_id);

		return $this->get_list(array('segment' => $segment_id, 'visible' => true), array(), array('rate DESC'), $limit);
	}

	public function get_best( $limit = 500)
	{
		return $this->get_list(array('visible' => true), array(), array('rate DESC'), $limit);
	}

	public function get_new( $limit = 500)
	{
		return $this->get_list(array('visible' => true), array(), array('id DESC'), $limit);
	}

	public function get_discussed( $limit = 100)
	{
		$sql = '
		SELECT i.id
		FROM ' . $this->table_name . ' i
		JOIN ' . ideas_comments_peer::instance()->get_table_name() . ' c ON (c.idea_id = i.id)
		WHERE visible = true
		GROUP BY i.id
		ORDER BY count(i.id) DESC
		LIMIT :limit';

		return db::get_cols($sql, array('limit' => $limit));
	}

	public function get_segments_cloud()
	{
		$sql = 'SELECT segment, count(id) as total FROM ' . $this->table_name . ' GROUP BY segment';
		$list = db::get_rows($sql, array(), $this->connection_name);
		return tags_helper::normalize($list, 'total');
	}

	public function has_voted( $id, $user_id )
	{
		return db_key::i()->exists('idea_vote:' . $id . ':' . $user_id);
	}

	public function vote( $id, $user_id )
	{
		db_key::i()->set('idea_vote:' . $id . ':' . $user_id, true);
	}

        public function reindex( $id )
	{
		$index_columns = array('title', 'text');
		$index_expr = 'coalesce(' . implode(',\'\') ||\' \'|| coalesce(', $index_columns) . ',\'\')';

		db::exec(
			'UPDATE ' . $this->table_name . ' SET fti = to_tsvector(\'russian\', ' . $index_expr . ') WHERE id = :id',
			array('id' => $id)
		);
	}

	public function clean_text( $text )
	{
		$config = HTMLPurifier_Config::createDefault();
		$config->set("HTML", "SafeEmbed", true);
		$config->set("HTML", "SafeObject", true);
		$config->set('HTML','Allowed','a[href|title|target],p,em,i,b,u,ul,li,strong,p,table,tr,td,img[src|width|height],embed,object[type|width|height|data],param[name|value]');
		$HTMLpurifier = new HTMLPurifier($config);
		return $HTMLpurifier->purify($text);
	}
        
	public function string_to_array( $string )
	{
		$list = explode(',', $string);
		$tags = array();
		foreach ( $list as $name )
		{
			if ( $name = trim(strip_tags($name)) )
			{
				$tags[] = $name;
			}
		}

		return array_unique($tags);
	}
	public function update( $data, $keys = null )
	{
		parent::update($data, $keys);
		//$this->reindex($data[$this->primary_key]);
	}
}
