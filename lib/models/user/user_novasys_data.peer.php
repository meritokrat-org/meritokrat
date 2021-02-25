<?
class user_novasys_data_peer extends db_peer_postgre
{
	protected $table_name = 'user_novasys_data';
        protected $primary_key = 'user_id';
	/**
	 * @return user_novasys_data_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_novasys_data_peer' );
	}

	public static function get_statuces()
	{
		return array(
                    1 => 'Соратник',
                    2 => 'Потенційний лідер',
                    3 => 'Активіст',
                    4 => 'Прихильник',
                    5 => 'Симпатик',
                    6 => 'Спостерігач',
                    7 => 'Цікава особистість',
                    8 => 'Кандидат на виключення'
		);
	}
	public static function get_status($id)
	{
                $list = self::get_statuces();
		return $list[$id];
	}


	public static function get_who_contacts()
	{
		return array(
                    1 => 'ІАШ',
                    2 => 'Керівник',
                    3 => 'Координатор',
                    4 => 'Рег. координатор',
                    5 => 'Рай. координатор',
                    6 => 'Організатор',
                    7 => 'Комунікатор',
                    8 => 'Керівник Секретаріату',
                    10 => 'Вільний',
                    11 => 'Представник у вузах',
                    12 => 'Керiвник ППО',
                    13 => 'Керiвник МПО',
                    14 => 'Керiвник РПО',
                    15 => 'Секретарiат'
		);
	}
	public static function get_who_contact($id)
	{
                $list = self::get_who_contacts();
		return $list[$id];
	}

}
