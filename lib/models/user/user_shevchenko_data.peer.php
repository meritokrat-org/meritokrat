<?
class user_shevchenko_data_peer extends db_peer_postgre
{
	protected $table_name = 'user_shevchenko_data';
	protected $primary_key = 'user_id';
        
        /**
	 * @return user_shevchenko_data
	 */
	public static function instance()
	{
		return parent::instance( 'user_shevchenko_data_peer' );
	}
        
        public static function get_referals($id=null) {
            $types = array(
                3=>t('С рекламы в соц. сетях'),
                5=>t('От Игоря Шевченко'),
                7=>t('От родственников'),
                8=>t('От друзей/знакомых/колег'),
                6=>t('Другое')
            );
            return ($id) ? (isset($types[$id]) ? $types[$id] : false) : $types;
        }
}
